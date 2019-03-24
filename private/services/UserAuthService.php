<?php
if(!defined('ROOT'))
	define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	
require_once(ROOT."/private/session.php");
require_once(ROOT."/private/template_file.php");
require_once(ROOT."/private/utils.php");
require_once(ROOT."/private/dbConnection.php");

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
			return false;
		$url = $_SERVER['REQUEST_URI'];
		$path = substr(parse_url($url, PHP_URL_PATH),1);
		$query = parse_url($url, PHP_URL_QUERY);
		$url = $path.(($query) ? "?".$query : "");
		header("location:".URL."login?referee=".$url);
		return true;
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
					if($value->role->authority == $athority){
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
		$userRoles = UserRole.findAll("SELECT * FROM @this WHERE userid=:id",['id'=>$user->id]);
		if($userRoles){
			foreach ($userRoles as $key => $value) {
				if($value->role->authority == $auth){
					return true;
				}
			}
		}
		return false;
	}
}
?>