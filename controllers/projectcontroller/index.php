<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/dbConnection.php");
    require_once(ROOT."/private/i18n.php");

    class projectcontroller extends Controller{
        public $UserAuth;

        public function index(){
        }

        /**
         * @service pre bool UserAuth->requireUserLogin()
         * @service pre bool UserAuth->UserHasAllAuths("ADMIN")
         * @method post void redirect("errors","notauth")
         */
        public function add(){
            print_r($this->params);
            // $test = Domain::fromFormData(FORM_METHOD_POST);
            $test1 = Project::fromData($this->params);
            $test = 1;
        }
    }

    require_once(ROOT."/private/Controller.php");

?>