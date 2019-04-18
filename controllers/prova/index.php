<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/services.php");
    require_once(ROOT."/private/ControllerLogic.php");


    $Userauth = Services::getInstance()->UserAuth;
    // $Userauth->requireUserLogin();
    
    // $auth = UserRole::find("SELECT * FROM @this WHERE id=1");
    // $test = new testClass();
    // $controller = new ControllerDecorator($test);
    // $controller->index();

    $modelTest = new template\PageModel();
    $modelTest->title = L::index_title;
    $modelTest->model = array(
        'array' => array('a','b','c'),
        'array2' => array('a1','b1','c3'),
        'userlogged' => $Userauth->getCurrentUser(),
        'false' => false,
        'true' => true,
        'int' => 100,
        'float' => 99.99,
        'string' => 'CAVABONGA',
        'nullval' => null
    );
    $modelTest->templateFile = '/templates/prova/prova_template.php';
    $modelTest->render();
?>
