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
            // $this->redirect('prova','test',array("test"=>'test','test1'=>'test1'));
        }

        public function logout(){
            Session::getInstance()->destroy();
        }

        public function testerror(){
            $session = Session::getInstance();
            $session->startSession();
            $session->error = "TESTING ERRORS";
            $this->redirect("errors");
        }

        public function test500(){ //not funziona ancora con ErrorDocument di htaccess (troppo tardi? server config?)
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            exit;
        }

        public function resource(){
            $innerBody = new template\PageModel();
            $innerBody->templateFile = '/templates/prova/prova_resource.php';
            $this->render("test resource",$innerBody);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function project(){
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/project_form.php';
            $body->model = [ "user" => $this->UserAuth->getCurrentUser()];
            $this->render("Form Testing",$body);
        }

        public function testAttr(){

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