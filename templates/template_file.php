<?php
namespace template;
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/i18n.php');

function setUpTemplate(String $file, $model){
    ob_start();
    require_once($file);
    $contents = ob_get_contents();
    ob_end_clean();

    $out = $contents;
    if($model['static']){
        foreach ($model['static'] as $key => $value) {
            $out = preg_replace('/'.$key.'/',$value,$out);
        }
    }
    return $out;
}

function renderTemplate(String $file, $model){
    echo setUpTemplate($file, $model);
}

function renderPage(String $title,$body = NULL,$model = NULL ,$header_model = NULL){
    $model['static'] = array(
        '<!--Title-->' => $title,
        '<!--Header-->' => $header_model,
        '<!--Body-->' => $body
    );
	renderTemplate(ROOT . "/templates/layouts/main.php",$model);
}

?>