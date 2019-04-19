<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class testcontroller extends Controller{
        public $UserAuth;

        /**
         * @service pre bool UserAuth->requireUserLogin()
         * @service pre bool UserAuth->UserHasAllAuths("ADMIN")
         * @method post void redirect("error")
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
            $innerBody->templateFile = '/templates/forms/project_form.php';
            $this->render(L::project_formadd,$innerBody);
            // $this->redirect('prova','test',array("test"=>'test','test1'=>'test1'));
        }

        public function logout(){
            Session::getInstance()->destroy();
        }
    }

    require_once(ROOT."/private/Controller.php");

?>