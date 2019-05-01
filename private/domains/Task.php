<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class task extends Domain{
    public $id;
    public $Nome;
    public $Descrizione;
    public $Completato;
    public $DataCreazione;
    public $DataCompletamento;
    public $DataScadenza;
    public $Completata;
    public $User;
    public $Project;
    public $Assignee;
    public $TaskList;

    public function belongsTo(){
        return ['User'=>'User','Project'=>'Project','Assignee','Assignee','TaskList'=>'TaskList'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>