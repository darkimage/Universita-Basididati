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
            }
        }
    }

    public function addTag(String $nodeName, String $className){
        $this->tags[$nodeName] = $className;
    }

    private function processDOMNode($iter) {
        if($iter >= 256){
            return;
        }
        $found = 0;
        foreach ($this->tags as $key => $value) {
            $nodes = $this->doc->getElementsByTagName($key);
            if(count($nodes) > 0){
                $testTAG = new $value($this->doc,$nodes[0],$this->pageModel);
                $testTAG->renderTag();
                $found += 1;
            }
        }
        if($found > 0){
            $this->processDOMNode($iter+1);
        }
    }

    public function processTags(){
        $this->processDOMNode(0);
        return $this->doc->saveHTML();
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
        $this->node->parentNode->replaceChild($this->tagNode, $this->node);
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