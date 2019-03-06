<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
	
	require_once(ROOT."/private/session.php");
    require_once(ROOT."/templates/template_file.php");
    require_once(ROOT."/private/utils.php");

    $loginFormModel = new template\PageModel();
    $loginFormModel->templateFile = '/templates/login/login_form.php';
    $loginFormModel->model = array(
        'referer' => array_key_exists('HTTP_REFERER',$_SERVER) ? $_SERVER['HTTP_REFERER'] : '/',
        'static' => array(
            '<!--Method-->' => 'post',
            '<!--Action-->' => 'authenticate.php'
        )
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

    //$test = (new class{ function test(){ echo "it worked";} })->test();
?>