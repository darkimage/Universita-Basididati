<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/ControllerLogic.php");

    class errors extends Controller{
        
        public function index(){
            $errorpage = new template\PageModel();
            $errorpage->templateFile = '/templates/error/error_page.php';
            if(isset($this->params['error'])){
                $errorpage->model = array(
                    "error" => $this->params['error']
                );
            }
            $this->render(L::error_title,$errorpage);
        }

        public function notauth(){
            $this->redirect("errors","index",["error"=>L::error_notauth]);
        }

        public function notfound(){
            $this->redirect("errors","index",["error"=>L::error_notfound]);
        }
    }

    require_once(ROOT."/private/Controller.php");

?>
