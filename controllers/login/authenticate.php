<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/session.php");
require_once(ROOT."/private/utils.php");
require_once(ROOT."/private/dbConnection.php");


function AutheticateUser($user){
    // $db = dbConnection::getInstance();
    $user_db = User::find("SELECT * FROM @this WHERE NomeUtente=:username",['username'=>$user->NomeUtente]);
    if($user_db){
        if(password_verify($user->Password,$user_db->Password)){
            echo "LOGGED";
        }else{
            echo "REJECTED NO PASSWORD";
        }
    }else{
        echo "REJECTED NO USER";
    }
}

$user = Domain::fromFormData(FORM_METHOD_POST);
if($user){
    AutheticateUser($user);
}else{
    echo "MALFORMED";
}

?>