<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class Orders extends Domain{
    public $id;
    public $OrderNumber;
    public $PersonID;

    public function belongsTo(){
        return array('PersonID'=>'Persons');
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>