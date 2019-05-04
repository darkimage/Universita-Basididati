<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/tagProcessor.php");


class formTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        parent::__construct($doc,$node,$page);
        $this->name = "form";
        if(isset($this->model['controller']))
            $this->model['action'] = URL.$this->model['controller']."/".$this->model['action'];

    }

    protected function getModel(){
        return array(
            'method' => 'post',
            'domain' => 'none',
            'hidden' => true,
            'referee' => $_SERVER['REQUEST_URI']
        );
    }
}

?>