<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/tagProcessor.php");


class accessTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        parent::__construct($doc,$node,$page);
        $this->name = "access";
    }

    protected function getModel(){
        return [
            "expression" => null,
            "args" => null
        ];
    }
}

?>