<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class UserRole extends Domain{
    public $id;
    public $Userid;
    public $Roleid;

    protected function belongsTo(){
        return array('Userid'=>'User','Roleid'=>'Role');
    }

    protected function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}

?>