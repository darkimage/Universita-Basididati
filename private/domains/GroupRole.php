<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/dbConnection.php');

class GroupRole extends Domain{
    public $id;
    public $Userid;
    public $Groupid;
    public $Roleid;

    public function belongsTo(){
        return ['Userid'=>'User','Groupid'=>'tGroup','Roleid'=>'Role'];
    }

    public function hasMany(){}

    public function primaryKey(){
        return 'id';
    }
}
?>