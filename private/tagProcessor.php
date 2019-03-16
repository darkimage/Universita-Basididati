<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/template_file.php");

class TagLoader {
    private $tags = [];
    private static $instance = null;

    public static function getInstance()
    {
      if(self::$instance == null)
      {   
         $c = __CLASS__;
         self::$instance = new $c;
      }
      
      return self::$instance;
    }

    function __construct(){
        foreach (scandir(ROOT.'/private/tags') as $filename) {
            $path = ROOT.'/private/tags/'.$filename;
            if (is_file($path)) {
                require_once($path);
                preg_match_all('/(.+)Tag/',pathinfo($filename)['filename'],$matches);
                $ntag ='t-'.$matches[1][0];
                if(!array_key_exists($ntag,$this->tags)){
                    $this->tags[$ntag] = pathinfo($filename)['filename'];
                }
            }
        }
    }

    function getTags(){
        return $this->tags;
    }
}

class TagProcessor{
    private $doc;
    private $pageModel;
    private $tags = [];
    private $model;

    function __construct(String $html,template\PageModel $pageModel){
        $this->doc = new DOMDocument();
        $this->pageModel = $pageModel;
        $this->model = $pageModel->model;
        $libxmlErrors = libxml_use_internal_errors(true);
        $this->doc->loadHTML($html);
        libxml_use_internal_errors($libxmlErrors);
        $this->tags = TagLoader::getInstance()->getTags();
    }

    private function processDOMNode(DOMNode $node,$iter) {
        $test = $this->doc->saveHTML($node);
        foreach($node->childNodes as $curr){
            $tagnode = NULL;
            foreach ($this->tags as $name => $tagClass) {
                if($curr->nodeName == $name){
                    $tag = new $tagClass($this->doc,$curr,$this->pageModel);
                    $tagnode = $tag->renderTag();
                    break;
                }
            }
            if($tagnode){
                if($tagnode->hasChildNodes()) {
                    $this->processDOMNode($tagnode,$iter+1);
                }
            }else{
                if($curr->hasChildNodes()) {
                    $this->processDOMNode($curr,$iter+1);
                }else{
                    $this->evalNode($curr);
                }
            }
        }
    }

    private function evalNode(DOMNode $baseNode){
        $doc = new DOMDocument();
        $test1 = $this->doc->saveHTML();
        $test = $this->doc->saveHTML($baseNode);
        $isPhp = preg_match_all('/\${(.+)}/s',$this->doc->saveHTML($baseNode),$matches);
        if($isPhp){
            ob_start();
            eval('?><?php '.htmlspecialchars_decode($matches[1][0]).' ?>');
            $evalued = ob_get_contents();
            ob_end_clean();
            if($evalued){
                $doc->loadHTML($evalued);
                $node = $this->doc->importNode($doc->getElementsByTagName('body')->item(0),true);
                $baseNode->parentNode->replaceChild($node, $baseNode);
                $test1 = $this->doc->saveHTML();
            }
        }
    }

    public function processTags(){
        $this->processTagsDOM();
        return $this->doc->saveHTML();
    }

    public function processTagsDOM(){
        $this->processDOMNode($this->doc,0);
        return $this->doc;
    }
    
}

abstract class htmlTag{
    private $doc;
    private $node;
    protected $name = NULL;
    private $model = [];
    private $tagNode;
    private $page;

    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        $this->doc = $doc;
        $this->node = $node;
        $this->page = $page;
    }

    abstract protected function getModel();

    protected function getAttributes(){
        $attributes = $this->node->attributes;
        $count = $attributes->length;
        for ($i=0; $i < $count; $i++) {
            $att = $attributes->item($i);
            if(!( $att->nodeName == 'static')){
                if($pageAttr = $this->isPageAttribute($att->value)){
                    $this->model[$att->name] = $this->processPageAttr($pageAttr);
                }else if($pageAttr = $this->isCompileAttribute($att->value)){
                    $this->model[$att->name] = $this->evaluateAttr($pageAttr);
                }else{
                    $this->model[$att->name] = $att->value;
                }
            }
        }
    }

    private function evaluateAttr($val){
        ob_start();
        eval('?><?php '.htmlspecialchars_decode($val).' ?>');
        $evaluated = ob_get_contents();
        ob_end_clean();
        return $evaluated;
    }

    private function processPageAttr($val){
        //check per tipo di inserzione esempio method:[post] dove method e il nome dell'attributo
        //ma se non specificato defaulta all'espressione presente tra le parentesi [] in questo caso 
        //alla stringa post
        $count = preg_match_all('/^(.+):\[(.+)\]$/', $val, $matches);
        if($count == 1){
           if(array_key_exists($matches[1][0],$this->page->model)){
               return $this->page->model[$matches[1][0]];
            }else{
               if($default = $this->isCompileAttribute($matches[2][0])){
                   return $this->evaluateAttr($default);
                }else{
                   return $matches[2][0];
                }
            }
        }else{
            return $this->page->model[$val];
        }
    }

    private function isPageAttribute(String $value){
        $count = preg_match_all('/^\@{(.+)}$/', $value, $matches);
        if($count == 1){
            return $matches[1][0];
        }else{
            return false;
        }
    }

    private function isCompileAttribute(String $value){
        $count = preg_match_all('/^\${(.+)}/s', $value, $matches);
        if($count == 1){
            return $matches[1][0];
        }else{
            return false;
        }
    }

    public function getTagNode(){
        return $this->tagNode;
    }

    private function addToModel($model){
        if($model){
            $this->model = array_merge($model,$this->model);
        }
        return $this->model;
    }

    public function renderTag(){
        $this->addToModel($this->getModel());
        $this->addToModel($this->page->model);
        $this->getAttributes();
        $tagModel = new template\PageModel();
        $tagModel->templateFile = '/templates/tags/'.'_'.$this->name.".php";
        $tagModel->model = $this->model;
        $tagModel->body = $this->getInnerHTML($this->node);
        $tagModel->setRaw();
        $this->tagNode = $this->importHTML($this->doc,$this->node, $tagModel->setUpTemplate());
        $res = $this->node->parentNode->replaceChild($this->tagNode, $this->node);
        if($res){
            return $this->tagNode;
        }else{
            return NULL; //NEED ERROR
        }
    }

    private function importHTML(DOMDocument $doc,DOMNode $node,String $file){
        $doc1 = new DOMDocument;
        $libxmlErrors = libxml_use_internal_errors(true);
        $doc1->loadHTML($file);
        libxml_use_internal_errors($libxmlErrors);
        $container = $doc->createElement('div');//Create new <br> tag
        $tempNode = $doc->importNode($doc1->getElementsByTagName('body')->item(0),true);
        $container->appendChild($tempNode);
        return $container;
    }

    private function getInnerHTML(DOMNode $node){
        $res = '';
        if($node->hasChildNodes()){
            foreach ($node->childNodes as $value) {
                $res .= $this->doc->saveHTML($value);
            }
        }
        return $res;
    }
}

?>