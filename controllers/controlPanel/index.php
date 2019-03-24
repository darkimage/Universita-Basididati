<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/services.php");
    require_once(ROOT."/private/utils.php");

    $Userauth = Services::getInstance()->UserAuth;
    if($Userauth->requireUserLogin()){
        return;
    }
    if(!$Userauth->UserHasAnyAuths('SUPERADMIN','ADMIN','USER')){
        redirectToError(L::error_notauth);
        return;
    }
    
    $controlPanel = new template\PageModel();
    $controlPanel->model = array();
    $controlPanel->title = L::controlpanel_title;
    $controlPanel->templateFile = "/templates/controlPanel/main_panel.php";

    $mainPage = new template\PageModel();
    $mainPage->title = L::controlpanel_title;
    $mainPage->body = $controlPanel->setUpTemplate();
    $mainPage->render();
?>
