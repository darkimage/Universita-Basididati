<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class SharedTask extends Domain{
    public $id;
    public $User;
    public $Task;

    public function belongsTo(){
        return ['User'=>'User','Task'=>'Task'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}

?>