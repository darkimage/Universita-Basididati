<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class Project extends Domain{
    public $id;
    public $Nome;
    public $Descrizione;
    public $Complatato;
    public $DataInizio;
    public $DataCompletamento;
    public $DataScadenza;
    public $Creatore;

    protected function belongsTo(){
        return array('Creatore'=>'User');
    }

    protected function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>