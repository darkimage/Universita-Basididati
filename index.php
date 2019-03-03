<?php
	require("templates/template_file.php");

	$body_model = array(
		'<!--Test1-->' => 'Test1',
		'<!--Test2-->' => 'Test2',
		'<!--Test3-->' => 'Test3',
	);
	$body = template\setUpTemplate("test.html",$body_model);

	template\renderPage('index',$body);
?>