<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class User extends Domain{
    public $id;
    public $Nome;
    public $Cognome;
    public $DataNascita;
    public $NomeUtente;
    public $Password;

    public function belongsTo(){}

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}

?>