<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class TaskList extends Domain{
    public $id;
    public $Task;
    public $Completata;
    
    public function belongsTo(){
        return ['Task'=>'Task'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>