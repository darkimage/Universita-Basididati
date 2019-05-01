<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class ProjectGroup extends Domain{
    public $id;
    public $tGroup;
    public $Project;
    
    public function belongsTo(){
        return ['tGroup'=>'tGroup','Project'=>'Project'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>