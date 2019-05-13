<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class provaclass{
        public $test;
        public $prova; 
    }
    
    class testController extends Controller{
        public $UserAuth;

        public function index(){
            $innerBody = new template\PageModel();
            $innerBody->model = array(
                'static' => array(
                    '<!--Test1-->' => 'Test1',
                    '<!--Test2-->' => 'Test2',
                    '<!--Test3-->' => 'Test3'
                )
            );
            $innerBody->templateFile = '/templates/index/main_body.php';
            $this->render(L::project_add,$innerBody);
        }

        public function testerror(){
            $this->redirect("errors");
        }

        public function attributes(){

            $body = new template\PageModel();
            $body->templateFile = '/templates/prova/prova_attr.php';
            $testclass = new provaclass();
            $testclass->test = "ciao";
            $testclass->prova = 'ciao1';
            $body->model = array(
                "arr" => $testclass,
                "testarr" => ["hi" => ["ciao","prova"] ,2,"test"]
            );
            $this->render("Testing syntax",$body);
        }
    }

    require_once(ROOT."/private/Controller.php");

?>