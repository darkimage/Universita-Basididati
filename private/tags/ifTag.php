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
}

?>