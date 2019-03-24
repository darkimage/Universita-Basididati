<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    
    require_once(ROOT."/scssphp/scss.inc.php");
    require_once(ROOT."/scssphp/example/Server.php");
    use Leafo\ScssPhp\Server;
    use Leafo\ScssPhp\Compiler;
    
    $test = $_GET;
    $scss = new Compiler();
    $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
    $scss->setImportPaths('assets/');
    // $compiledcss = $scss->compile('@import "bootstrap/scss/bootstrap.scss";');
    // echo $compiledcss;
    $server = new Server('assets/', null, $scss);
    $server->serve();
?>