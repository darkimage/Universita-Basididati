<?php
require_once(ROOT.'/private/services.php');

if($this->expression && $this->args){
    if(Services::getInstance()->UserAuth->{$this->expression}(...$this->args)){
        $this->user = Services::getInstance()->UserAuth->getCurrentUser();
        echo $this->getStatic('Body');
    }
}else if($this->expression){
    if(Services::getInstance()->UserAuth->{$this->expression}()){
        $this->user = Services::getInstance()->UserAuth->getCurrentUser();
        echo $this->getStatic('Body');
    }
}
?>