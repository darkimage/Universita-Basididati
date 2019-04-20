<?php
Session::getInstance()->startSession();
$test = ($this->model['test'] === 'true') ? true : false;
if($test){
    echo $this->getStatic('Body');
}
?>