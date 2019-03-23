<?php
	if(!defined('ROOT'))
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	
	require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/utils.php");

    $redirect = array_key_exists('HTTP_REFERER',$_SERVER) ? $_SERVER['HTTP_REFERER'] : '/';
    if(isset($_GET['referee'])){
        $redirect = $_GET['referee'];
    }

    $loginFormModel = new template\PageModel();
    $loginFormModel->templateFile = '/templates/login/login_form.php';
    $loginFormModel->model = array(
        'method' => 'post',
        'action' => '/login/authenticate'.'?referer='.$redirect
    );
    $modelTest = new template\PageModel();
    $modelTest->title = L::login_title;
    $modelTest->resources = array(
        'header' => array(
            'css' => relativePath(__DIR__,"login.css")
        )
    );
    $modelTest->body = $loginFormModel->setUpTemplate();
    $modelTest->render();
?>