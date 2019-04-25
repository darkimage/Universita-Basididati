<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/dbConnection.php");
    require_once(ROOT."/private/i18n.php");

    class testAPI extends Controller{
        public $UserAuth;

        public function index(){
            $project = Project::find("SELECT * FROM @this WHERE id=:id",["id"=>$this->params['id']]);
            $project->Creatore->Password = "*****";
            echo json_encode($project,JSON_PRETTY_PRINT);
        }
    }

    require_once(ROOT."/private/Controller.php");
?>
