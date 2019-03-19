<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/tagProcessor.php");


class formTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        parent::__construct($doc,$node,$page);
        $this->name = "form";
    }

    protected function getModel(){
        $referee = null;
        if(isset($_SERVER['HTTP_REFERER'])) {
            $referee = $_SERVER['HTTP_REFERER'];
        }  

        return array(
            'method' => 'post',
            'domain' => 'none',
            'referee' => $referee
        );
    }
}

?>