<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class Persons extends Domain{
    public $id;
    public $LastName;
    public $FirstName;
    public $Address;
    public $City;

    public function belongsTo(){}

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }   
}

?>