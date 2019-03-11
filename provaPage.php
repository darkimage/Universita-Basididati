<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/template_file.php");

    $modelTest = new template\PageModel();
    $modelTest->title = L::index_title;
    $modelTest->model = array(
        'array' => array('a','b','c'),
        'array2' => array('a1','b1','c3')
    );
    $modelTest->templateFile = '/prova_template.php';
    $modelTest->render();
?>