<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/templates/template_file.php");

class TagProcessor{
    private $doc;
    private $pageModel;
    private $tags = [];

    function __construct(String $html,template\PageModel $pageModel){
        $this->doc = new DOMDocument();
        $this->pageModel = $pageModel;
        $libxmlErrors = libxml_use_internal_errors(true);
        $this->doc->loadHTML($html);
        libxml_use_internal_errors($libxmlErrors);
        $this->LoadTags();
    }

    private function LoadTags(){
        foreach (scandir(ROOT.'/private/tags') as $filename) {
            $path = ROOT.'/private/tags/'.$filename;
            if (is_file($path)) {
                require_once($path);
                preg_match_all('/(.+)Tag/',pathinfo($filename)['filename'],$matches);
                $this->addTag('t-'.$matches[1][0],pathinfo($filename)['filename']);
            }
        }
    }

    private function addTag(String $nodeName, String $className){
        $this->tags[$nodeName] = $className;
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
                }/*else{
                    $this->evalNode();
                }*/
            }else{
                if($curr->hasChildNodes()) {
                    $this->processDOMNode($curr,$iter+1);
                }/*else{
                    $this->evalNode();
                }*/
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
                    $this->model[$att->name] = $this->page->model[$pageAttr];
                }else{
                    $this->model[$att->name] = $att->value;
                }
            }
        }
    }

    private function isPageAttribute(String $value){
        $count = preg_match_all('/^{(.+)}$/', $value, $matches);
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
        $this->getAttributes();
        $this->addToModel($this->getModel());
        $tagModel = new template\PageModel();
        $tagModel->templateFile = '/templates/tags/'.'_'.$this->name.".php";
        $tagModel->model = $this->model;
        $tagModel->body = $this->getInnerHTML($this->node);
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
        $tempNode = $doc->importNode($doc1->getElementsByTagName('*')->item(0),true);
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