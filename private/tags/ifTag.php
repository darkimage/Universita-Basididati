<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/tagProcessor.php");
require_once(ROOT."/private/session.php");

class ifTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        parent::__construct($doc,$node,$page);
        $this->name = "if";
    }

    protected function getModel(){
        
    }

    protected function getAttributes(){
        parent::getAttributes();
        $attributes = $this->node->attributes;
        $count = $attributes->length;
        for ($i=0; $i < $count; $i++) {
            $att = $attributes->item($i);
            if($att->nodeName == 'test'){
                if($pageAttr = $this->isPageAttribute(urldecode($att->value))){
                    $this->model[$att->name] = ($this->processPageAttr($pageAttr[2][0])) ? true : false;
                }else if($pageAttr = $this->isCompileAttribute(urldecode($att->value))){
                    $this->model[$att->name] = eval('return ('.$pageAttr[2][0].') ? true : false; ?>');
                }else{
                    $this->model[$att->name] = ($att->value == 'true') ? true : false;
                }
            }
        }
    }
}

?>