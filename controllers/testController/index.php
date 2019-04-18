<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class testcontroller extends Controller{
        public $UserAuth;

        function test1(){
            return "ciao";
        }

        public function test(String $test1, String $test2){
            return false;
        }

        /**
         * @method pre bool test("test1","test2")
         * @service post void UserAuth->getCurrentUser()
         * @method post void redirect("prova","test",{array("test"=>"test","test1"=>"test1")})
         */
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
            $this->render(L::project_formadd,$innerBody);
            // $this->redirect('prova','test',array("test"=>'test','test1'=>'test1'));
        }
    }

    require_once(ROOT."/private/Controller.php");

?>