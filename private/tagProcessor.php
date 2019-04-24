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
        if($html){
            $libxmlErrors = libxml_use_internal_errors(true);
            $this->doc->loadHTML($html);
            libxml_use_internal_errors($libxmlErrors);
        }
        $this->tags = TagLoader::getInstance()->getTags();
    }

    private function processDOMNode(DOMNode $node,$iter) {
        $test = $this->doc->saveHTML($node);
        $rm = [];
        foreach($node->childNodes as $curr){
            $standardTag = true;
            foreach ($this->tags as $name => $tagClass) {
                if($curr->nodeName == $name){
                    $tag = new $tagClass($this->doc,$curr,$this->pageModel);
                    $temp = $tag->renderTag();
                    if($temp){
                        array_push($rm, $temp);
                    }
                    $standardTag = false;
                    break;
                }
            }
            if($standardTag){
                if($curr->nodeType == 1){
                    $tag = new standardTag($this->doc,$curr,$this->pageModel);
                    $tag->renderTag();
                }
            }
            if($curr->hasChildNodes()) {
                $this->processDOMNode($curr,$iter+1);
            }else{
                $temp = $this->evalNode($curr);
                if($temp){
                    array_push($rm, $temp);
                }
            }
        }
        foreach ($rm as $key => $value) {
            $node->removeChild($value);
        }

    }

    private function evalNode(DOMNode $baseNode){
        if($baseNode->nodeType == 3){
            $doc = new DOMDocument();
            $isPhp = preg_match_all('/\${([^}]+)/',$this->doc->saveHTML($baseNode),$matches);
            if($isPhp){
                ob_start();
                eval('?><?php '.htmlspecialchars_decode($matches[1][0]).' ?>');
                $evalued = ob_get_contents();
                if(!$evalued){
                    $evalued = "<div></div>";
                }
                ob_end_clean();
                $doc->loadHTML($evalued);
                $node = $this->doc->importNode($doc->getElementsByTagName('body')->item(0),true);
                $parent = $baseNode->parentNode;
                $res = $parent->appendChild($node);
                if($res)
                    return $baseNode;
            }
            return null;
        }
        return null;
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
    protected $node;
    protected $name = NULL;
    protected $model = [];
    private $tagNode;
    protected $page;
    protected $sourceTag = 'body';

    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        $this->doc = $doc;
        $this->node = $node;
        $this->page = $page;

        $this->addToModel($this->getModel());
        $this->addToModel($this->page->model);
        $this->getAttributes();
    }

    abstract protected function getModel();

    protected function getAttributes(){
        $attributes = $this->node->attributes;
        $count = $attributes->length;
        for ($i=0; $i < $count; $i++) {
            $att = $attributes->item($i);
            if(!( $att->nodeName == 'static')){
                if($pageAttr = $this->isPageAttribute(urldecode($att->value))){
                    $this->model[$att->name] = $this->processPageAttr($pageAttr[2][0]);
                }else if($pageAttr = $this->isCompileAttribute(urldecode($att->value))){
                    $this->model[$att->name] = $this->evaluateAttr($pageAttr[2][0]);
                }else{
                    $this->model[$att->name] = $att->value;
                }
            }
        }
    }

    protected function evaluateAttr($val){
        ob_start();
        eval('?><?php '.htmlspecialchars_decode($val).' ?>');
        $evaluated = ob_get_contents();
        ob_end_clean();
        return $evaluated;
    }

    protected function processPageAttr($val){
        //check per tipo di inserzione esempio method:[post] dove method e il nome dell'attributo
        //ma se non specificato defaulta all'espressione presente tra le parentesi [] in questo caso 
        //alla stringa post ^((.+)->(.+)|(.+)):\[(.+)\]$
        // $count = preg_match_all('/^(.+):\[(.+)\]$/', $val, $matches);
        $count = preg_match_all('/^(([^->]+)->(.+)|.+):\[(.+)\]|([^->]+)->(.+)$/', $val, $matches);
        if($count == 1){
           if(array_key_exists($matches[2][0],$this->page->model)){
               if($matches[3][0])
                    return eval('return $this->page->model[$matches[2][0]]->'.$matches[3][0]."?>");
                else
                    return $this->page->model[$matches[2][0]];
            }else if($matches[4][0]){
               if($default = $this->isCompileAttribute($matches[4][0]))
                    return $this->evaluateAttr($default[2][0]);
                else
                    return $matches[4][0];
            }else if($matches[5][0]){
                return eval('return $this->page->model[$matches[5][0]]->'.$matches[6][0]."?>");
            }
        }else{
            return $this->page->model[$val];
        }
    }

    // if($matches[7][0]){
    //     return $this->page->model[$matches[7][0]][$matches[9][0]];
    //  }else{
    //      return $matches[10][0];
    //  }

    protected function isPageAttribute(String $value){
        $count = preg_match_all('/^(.+)?\@{(.+)}(.+)?$/', $value, $matches);
        if($count == 1){
            return $matches;
        }else{
            return false;
        }
    }

    protected function isCompileAttribute(String $value){
        $count = preg_match_all('/^(.+)?\${(.+)}(.+)?/s', $value, $matches);
        if($count == 1){
            return $matches;
        }else{
            return false;
        }
    }

    public function getTagNode(){
        return $this->tagNode;
    }

    protected function addToModel($model){
        if($model){
            $this->model = array_merge($model,$this->model);
        }
        return $this->model;
    }

    public function renderTag(){

        $tagModel = new template\PageModel();
        $tagModel->templateFile = '/templates/tags/'.'_'.$this->name.'.php';
        $tagModel->model = $this->model;
        $tagModel->body = $this->getInnerHTML($this->node);
        $this->tagNode = $this->importHTML($this->doc,$this->node, $tagModel->setUpTemplate());
        if($this->tagNode){
            $parent = $this->node->parentNode;
            $res = $parent->insertBefore($this->tagNode,$this->node);
        }
        return $this->node;
    }

    private function importHTML(DOMDocument $doc,DOMNode $node,String $file){
        $doc1 = new DOMDocument;
        $libxmlErrors = libxml_use_internal_errors(true);
        $doc1->loadHTML($file);
        libxml_use_internal_errors($libxmlErrors);
        $importnode = $doc1->getElementsByTagName($this->sourceTag)->item(0);
        if($importnode){
            $tempNode = $doc->importNode($importnode,true);
            return $tempNode;
        }else{
            return null;
        }
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

class standardTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        parent::__construct($doc,$node,$page);
    }

    protected function getModel(){ }

    protected function getAttributes(){
        $attributes = $this->node->attributes;
        $count = $attributes->length;
        for ($i=0; $i < $count; $i++) {
            $att = $attributes->item($i);
            if(!( $att->nodeName == 'static')){
                if($pageAttr = $this->isPageAttribute(urldecode($att->value))){
                    $pval = $this->processPageAttr($pageAttr[2][0]);
                    if(is_bool($pval)){
                        $att->value = $pageAttr[1][0].(($pval) ? 'true' : 'false').$pageAttr[3][0];
                    }else{
                        $att->value = $pageAttr[1][0].((string)htmlspecialchars($pval)).$pageAttr[3][0];
                    }
                }else if($pageAttr = $this->isCompileAttribute(urldecode($att->value))){
                    $pval = $this->evaluateAttr($pageAttr[2][0]);
                    if(is_bool($pval)){
                        $att->value = $pageAttr[1][0].(($pval) ? 'true' : 'false').$pageAttr[3][0];
                    }else{
                        $att->value = $pageAttr[1][0].((string)htmlspecialchars($pval)).$pageAttr[3][0];
                    }
                }
            }
        }
    }

    public function renderTag(){
        $this->addToModel($this->getModel());
        $this->addToModel($this->page->model);
        $this->getAttributes();
    }
}


?>