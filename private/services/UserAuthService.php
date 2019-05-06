<?php
if(!defined('ROOT'))
	define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	
require_once(ROOT."/private/session.php");
require_once(ROOT."/private/template_file.php");
require_once(ROOT."/private/utils.php");
require_once(ROOT."/private/dbConnection.php");
require_once(ROOT."/private/ControllerLogic.php");


class UserAuth {

	function getCurrentUser(){
		$user = Session::getInstance()->user;
		if(isset($user)){
			return $user;
		}else{
			return false;
		}
	}

	function requireUserLogin(){
		$user = $this->getCurrentUser();
		if($user)
			return true;
		$url = $_SERVER['REQUEST_URI'];
		Controller::redirect("login","index",[ "referee" => URL.substr($url, 1)]);
	}

	function UserHasAllAuths(...$auths){
		$user = $this->getCurrentUser();
		if(!$user) 
			return false;
		$userRoles = UserRole::findAll("SELECT * FROM @this WHERE userid=:id",['id'=>$user->id]);
		if($userRoles){
			$res = 0;
			foreach ($userRoles as $key => $value) {
				foreach ($auths as $akey => $athority) {
					if($value->Roleid->Authority == $athority){
						$res++;
					}
				}
			}
			if($res == count($auths))
				return true;
		}
		return false;
	}

	function UserHasAnyAuths(...$auths){
		$user = $this->getCurrentUser();
		if(!$user) 
			return false;
		$userRoles = UserRole::findAll("SELECT * FROM @this WHERE userid=:id",['id'=>$user->id]);
		if($userRoles){
			foreach ($userRoles as $key => $value) {
				foreach ($auths as $akey => $authority) {
					if($value->Roleid->Authority == $authority){
						return true;
					}
				}
			}
		}
		return false;
	}

	function UserHasAuth(String $auth){
		$user = $this->getCurrentUser();
		if(!$user) 
			return false;
		$userRoles = UserRole::findAll("SELECT * FROM @this WHERE userid=:id",['id'=>$user->id]);
		if($userRoles){
			foreach ($userRoles as $key => $value) {
				if($value->Roleid->Authority == $auth){
					return true;
				}
			}
		}
		return false;
	}
}
?>