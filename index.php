<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
	
	require_once(ROOT."/session.php");
	require_once(ROOT."/templates/template_file.php");

	$body_model = array(
		'<!--Test1-->' => 'Test1',
		'<!--Test2-->' => 'Test2',
		'<!--Test3-->' => 'Test3',
	);

	$model_body = array(
		'static' => $body_model
	);

	$body = template\setUpTemplate("test.html",$model_body);


	$model = array(
		'test' => 'TESTING STUFF'
	);

	template\renderPage('index',$body,$model);
?>