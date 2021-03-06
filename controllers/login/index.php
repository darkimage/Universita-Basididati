<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");
    require_once(ROOT."/private/ControllerLogic.php");

    class loginController extends Controller{
        public $UserAuth;

        public function index(){
            $session = Session::getInstance();
            $session->startSession();
            if(!isset($this->params['referee']))
                Controller::redirect("/");
            if($this->UserAuth->getCurrentUser()){
                header("location:".$this->params['referee']);
                exit;
            }

            $loginFormModel = new template\PageModel();
            $loginFormModel->templateFile = '/templates/forms/login_form.php';
            $loginFormModel->resources = [
                'header' => [
                    'stylesheet' => "login.css"
                ]
            ];
            $loginFormModel->model = array(
                'referee' => $this->params['referee']
            );
            $this->render(L::login_title,$loginFormModel);
        }

        public function authenticate(){
            $user = User::fromData($this->params);
            if(!$user)
                $this->redirect("errors");
            $session = Session::getInstance();
            $user_db = User::find("SELECT * FROM @this WHERE NomeUtente=:username",['username'=>$user->NomeUtente]);
            if($user_db){
                if(password_verify($user->Password,$user_db->Password)){
                    Session::getInstance()->destroy();
                    Session::getInstance()->startSession();
                    $session->user = $user_db;
                    header("location:".(($this->params['referee']) ? $this->params['referee'] : URL));
                    exit;
                }
            }
            $session->startSession();
            $session->flash = ['class'=> 'alert-danger','message'=>L::login_error];
            $this->redirect("login","index",$this->params);
        }
        
    }

    require_once(ROOT."/private/Controller.php");
    
?>