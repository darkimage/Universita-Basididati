<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT.'/session.php');
    require_once(ROOT."/templates/template_file.php");
    
	$model = array(
		'test' => 'TESTING STUFF'
    );
    
	template\renderPage('index',NULL,$model);
?>