<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/services.php");

    $Userauth = Services::getInstance()->UserAuth; 

    $modelTest = new template\PageModel();
    $modelTest->title = L::index_title;
    $modelTest->model = array(
        'array' => array('a','b','c'),
        'array2' => array('a1','b1','c3'),
        'userlogged' => $Userauth->isUserLogged()
    );
    $modelTest->templateFile = '/templates/prova/prova_template.php';
    $modelTest->render();
?>
