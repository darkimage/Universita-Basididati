<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    $redirect = array_key_exists('HTTP_REFERER',$_SERVER) ? $_SERVER['HTTP_REFERER'] : '';
    if(isset($_GET['referee'])){
        $redirect = $_GET['referee'];
    }
    $UserAuth = Services::getInstance()->UserAuth;
    if($UserAuth->getCurrentUser()){
        header("location:".$redirect);
    }

    $loginFormModel = new template\PageModel();
    $loginFormModel->templateFile = '/templates/login/login_form.php';
    $loginFormModel->model = array(
        'method' => 'post',
        'action' => '/login/authenticate'.'?referer='.$redirect
    );
    $mainPage = new template\PageModel();
    $mainPage->title = L::login_title;
    $mainPage->resources = array(
        'header' => array(
            'css' => "login"
        )
    );
    $mainPage->body = $loginFormModel->setUpTemplate();
    $mainPage->render();



    
?>