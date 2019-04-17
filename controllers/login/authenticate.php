<?php
if(!defined('ROOT'))
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(ROOT."/private/i18n.php");
require_once(ROOT."/private/session.php");
require_once(ROOT."/private/utils.php");
require_once(ROOT."/private/dbConnection.php");

function loginError(){
    if(!isset($_SESSION)) 
    session_start();
    $_SESSION['flash'] = L::login_error;
}

function AutheticateUser($user){
    $referee = '';
    if(isset($_GET['referer'])){
        $referee = "?referee=".$_GET['referer'];
    }
    $user_db = User::find("SELECT * FROM @this WHERE NomeUtente=:username",['username'=>$user->NomeUtente]);
    if($user_db){
        if(password_verify($user->Password,$user_db->Password)){
            Session::getInstance()->destroy();
            $session = Session::getInstance();
            $session->user = $user_db;
            if(isset($_GET['referer'])){
                header("location:".$_GET['referer']);
                return;
            }
            header("location:".URL);
            return;
        }
    }
    loginError();
    header("location:".URL.'login'.$referee);
    return;
}


$user = Domain::fromFormData(FORM_METHOD_POST);
if($user){
    AutheticateUser($user);
}else{
    header("location:".URL);
}

?>