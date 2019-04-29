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

        if(is_array($this->params) && $this->overwrite == 'false'){
            $this->params = array_merge($this->getParams(),$this->params);
        }else if($this->overwrite){
            $this->params = $this->getParams();
        }

        if($this->controller){
            $link = "";
            $link .= $this->controller."/".$this->action;
            foreach ($this->params as $key => $value) {
                if ($key === array_key_first($this->params))
                    $link .= "?".$key."=".$value;
                else
                    $link .= "&".$key."=".$value;
            }
            $this->model["href"] = URL.$link;
        }else if(!$this->href){
            $this->model["href"] = "/";
        }
    }

    protected function getModel(){
        return [ 'overwrite' => 'false'];
    }

    private function getParams(){
        $query = $_SERVER['QUERY_STRING'];
        $paramsArray = explode('&',$query);
        $params = [];
        for ($i=0; $i < count($paramsArray); $i++) {
            $keys = explode("=",$paramsArray[$i]);
            if($keys[0] != 'action'){
                $params[$keys[0]] = $keys[1];
            }
        }
        return $params;
    }
}

?>