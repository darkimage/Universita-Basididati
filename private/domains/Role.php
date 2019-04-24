<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class Role extends Domain{
    public $id;
    public $Authority;
    
    public function belongsTo(){}

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}

?>