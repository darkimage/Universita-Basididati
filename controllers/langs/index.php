<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/ControllerLogic.php");

    class langsController extends Controller{
        public $UserAuth;
        
        public function index(){
            $this->redirect("/");
        }

        public function set(){
            if(!isset($this->params['lang']))
                $this->redirect('errors');
            $lang = $this->params['lang'];
            $referee = $_SERVER["HTTP_REFERER"];
            Session::getInstance()->lang = $lang;
            header("location: $referee");
            exit;
        }

    }

    require_once(ROOT."/private/Controller.php");
?>
