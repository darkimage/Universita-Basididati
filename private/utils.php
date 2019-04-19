<?php

if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/session.php");

function relativePath(String $dir,String $file){
    $root = explode("/", $_SERVER['DOCUMENT_ROOT']);
    $path = explode("\\", $dir);
    foreach ($root as $key => $value) {
        foreach ($path as $keypath => $valuepath){
            if($value == $valuepath){
                unset($path[$keypath]);
            }
        }
    }
    $path = "/".implode("/",$path)."/".$file;
    return $path;
}

function redirectToError(String $error){
    if(!isset($_SESSION)) 
        session_start();
    $_SESSION['error'] = $error;
    header('location:'.URL."error");
}

function getReferee($fromquery=true){
    $redirect = "";
    if(!$fromquery)
        $redirect = array_key_exists('HTTP_REFERER',$_SERVER) ? $_SERVER['HTTP_REFERER'] : '';
    if(!$redirect){
        if(isset($_GET['referee'])){
            foreach ($_GET as $key => $value) {
                if($key == 'referee'){
                    $startpath = explode("?",$value);
                    foreach ($startpath as $key => $value) {
                        $redirect .= (!$key) ? $value."?" : $value;
                    }
                }
                else
                    $redirect .= "&".$key."=".$value;
            }
        }
    }
    return $redirect;
}

?>