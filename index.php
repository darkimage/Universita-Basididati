<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
	
	require_once(ROOT."/private/session.php");
	require_once(ROOT."/templates/template_file.php");
	$body_model = array(
		'static' => array(
			'<!--Test1-->' => 'Test1',
			'<!--Test2-->' => 'Test2',
			'<!--Test3-->' => 'Test3'
		)
	);
	$innerBody = new template\PageModel();
	$innerBody->model = $body_model;
	$innerBody->templateFile = '/templates/index/main_body.php';

	$modelTest = new template\PageModel();
    $modelTest->title = L::index_title;
    $modelTest->body = $innerBody->setUpTemplate();
    $modelTest->render();
?>