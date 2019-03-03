<?php
namespace template;

function setUpTemplate(String $file, $model){
    $template_src = implode("",file($file));
    $out = $template_src;
    foreach ($model as $key => $value) {
        $out = preg_replace('/'.$key.'/',$value,$out);
    }
    return $out;
}

function renderTemplate(String $file, $model){
    echo setUpTemplate($file,$model);
}

function renderPage(String $title,$model,$header_model = NULL){
    $model = array(
		'<!--Title-->' => $title,
		'<!--Header-->' => $header_model,
		'<!--Body-->' => $model
	);
	renderTemplate("templates/layouts/main.html",$model);
}

?>