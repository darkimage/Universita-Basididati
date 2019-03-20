<?php
if(!defined('ROOT'))
	define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	
require_once(ROOT."/private/session.php");
require_once(ROOT."/private/template_file.php");
require_once(ROOT."/private/utils.php");
require_once(ROOT."/private/dbConnection.php");

class UserAuth {

	function isUserLogged(){
		if(isset(Session::getInstance()->auth)){
			return Session::getInstance()->auth;
		}else{
			return false;
		}
	}

	function getCurrentUser(){
		if(isset($_SESSION['user'])){
			return $_SESSION['user'];
		}else{
			return false;
		}
	}

	function isUserAuth(String $auth){
		$user = getCurrentUser();
		$userRoles = UserRole.findAll("SELECT * FROM @this WHERE userid=:id",['id'=>$user->id]);
		if($userRoles){
			foreach ($userRoles as $key => $value) {
				if($value->role->authority == $auth){
					return true;
				}
			}
			return false;
		}else
			header("location:".URL."login");
	}
}
?>