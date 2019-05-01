<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class Assignee extends Domain{
    public $id;
    public $User;
    public $tGroup;

    public function belongsTo(){
        return ['User'=>'User','tGroup'=>'tGroup'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>