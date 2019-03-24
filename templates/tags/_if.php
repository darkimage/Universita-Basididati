<?php
if(!isset($_SESSION)) 
    session_start();
$test = $this->model['test'];
if($this->model['test']){
    echo $this->getStatic('Body');
}
?>