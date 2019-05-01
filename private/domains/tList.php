<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class tList extends Domain{
    public $id;
    public $Task;
    public $TaskList;
    
    public function belongsTo(){
        return ['Task'=>'Task','TaskList'=>'TaskList'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}

?>