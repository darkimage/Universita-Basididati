<?php
Session::getInstance();
$test = ($this->test === 'true') ? true : false;
if($test){
    echo $this->getStatic('Body');
}
?>