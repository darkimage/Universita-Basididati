<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/ControllerLogic.php");

    $calling_controller = $_SERVER["SCRIPT_NAME"];
    $calling_controller_name = array_reverse(explode("/",$calling_controller))[1];
    $controller = new ControllerDecorator(new $calling_controller_name);
    $controller->index();
    // $controller->serve();
?>