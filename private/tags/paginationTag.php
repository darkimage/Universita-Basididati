<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}

class paginationTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        parent::__construct($doc,$node,$page);
        $this->name = "pagination";
    }

    protected function getModel(){
        return [
            'count' => 0,
            'list' => null,
            'params' => ['page' => 0],
        ];
    }
}

?>