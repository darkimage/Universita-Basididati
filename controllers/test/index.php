<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
	
	require_once(ROOT."/private/session.php");
	require_once(ROOT."/private/template_file.php");
	require_once(ROOT."/private/dbConnection.php");


	$test = User::find("SELECT * FROM @this WHERE id=1");
	$test->Nome = "Test";
	$test->save();

	$test1 = new User(['Nome','Test1'],['Cognome','TestCogn'],['DataNascita','2019-03-01'],['NomeUtente','test'],['Password',password_hash("test123",PASSWORD_BCRYPT)]);
	$test1->save();

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