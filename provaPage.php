<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/templates/template_file.php");

    $modelTest = new template\PageModel();
    $modelTest->title = L::index_title;
    $modelTest->model = array(
        'array' => array('a','b','c')
    );
    $modelTest->setSourceString('<t-each collection="{array}" key="key" item="value"><t-test test="{value}">
    Ciao</t-test></t-each>');
    $modelTest->render();
?>