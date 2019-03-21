<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class Orders extends Domain{
    public $id;
    public $OrderNumber;
    public $PersonID;

    protected function belongsTo(){
        return array('PersonID'=>'Persons');
    }

    protected function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>