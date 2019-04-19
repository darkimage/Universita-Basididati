<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/ControllerLogic.php");

    $errorpage = new template\PageModel();
    $errorpage->templateFile = '/templates/error/error_page.php';

    $mainPage = new template\PageModel();
    $mainPage->title = L::error_title;
    $mainPage->body = $errorpage->setUpTemplate();
    $mainPage->render();

    class error extends Controller{
        
        public function index(){
            $errorpage = new template\PageModel();
            $errorpage->templateFile = '/templates/error/error_page.php';
            $this->render(L::error_title,$errorpage);
        }

    }

    require_once(ROOT."/private/Controller.php");

?>
