<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/i18n/i18n.class.php");
	require_once(ROOT. "/private/session.php");
	require_once(ROOT. "/private/dbConnection.php");

	Session::getInstance();
    $i18n = new i18n(ROOT.'/lang/lang_{LANGUAGE}.ini', ROOT.'/i18n/langcache/');
    $i18n->init();
?>