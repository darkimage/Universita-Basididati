<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/tagProcessor.php");
require_once(ROOT."/private/session.php");

class linkTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node,template\PageModel $page){
        parent::__construct($doc,$node,$page);
        $this->name = "link";

        if($this->controller){
            $link = "";
            $link .= $this->controller."/".$this->action;
            if(is_array($this->params)){
                foreach ($this->params as $key => $value) {
                    if ($key === array_key_first($value))
                        $link .= "?".$key."=".$value;
                    else
                        $link .= "&".$key."=".$value;
                }
            }
            $this->model["href"] = URL.$link;
        }else if(!$this->href){
            $this->model["href"] = "/";
        }
    }

    protected function getModel(){

    }
}

?>