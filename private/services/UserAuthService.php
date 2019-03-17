<?php
if(!defined('ROOT'))
	define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	
require_once(ROOT."/private/session.php");
require_once(ROOT."/private/template_file.php");
require_once(ROOT."/private/utils.php");

class UserAuth {

	function isUserLogged(){
		if(isset(Session::getInstance()->auth)){
			return Session::getInstance()->auth;
		}else{
			return false;
		}
	}
}
?>