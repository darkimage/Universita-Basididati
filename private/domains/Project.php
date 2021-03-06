<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class Project extends Domain{
    public $id;
    public $Nome;
    public $Descrizione;
    public $Completato;
    public $DataInizio;
    public $DataCompletamento;
    public $DataScadenza;
    public $Creatore;

    public function belongsTo(){
        return ['Creatore'=>'User'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>