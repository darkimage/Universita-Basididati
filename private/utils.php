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

?>