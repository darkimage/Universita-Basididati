<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class TaskList extends Domain{
    public $id;
    public $Completata;
    
    public function belongsTo(){}

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>