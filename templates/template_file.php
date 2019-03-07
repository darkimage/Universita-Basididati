<?php
namespace template;
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/i18n.php');
require_once(ROOT.'/private/utils.php');

class PageModel{
    public $resources = [];
    public $title = "";
    public $body = NULL;
    public $header = NULL;
    public $templateFile = '/templates/layouts/main.php';
    public $model = [];


    private function generateStaticModel(){
        $this->model['static']['<!--Title-->'] = $this->title;
        $this->model['static']['<!--Header-->'] = $this->header;
        $this->model['static']['<!--Body-->'] = $this->body;
        $this->model['static']['<!--Resources_Header-->'] = array_key_exists('header',$this->resources) ? $this->addResource($this->resources['header']) : NULL;
        $this->model['static']['<!--Resources_Body-->'] = array_key_exists('body',$this->resources) ? $this->addResource($this->resources['body']) : NULL;
    }

    function getModel(){
        if(array_key_exists('static',$this->model)){
            $this->generateStaticModel();
        }else{
            $this->model['static'] = [];
            $this->generateStaticModel();
        }
        return $this->model;
    }

    function setUpTemplate(){
        $model = $this->getModel();
        ob_start();
        require(ROOT . $this->templateFile);
        $contents = ob_get_contents();
        ob_end_clean();
    
        $out = $contents;
        if(array_key_exists('static',$model)){
            foreach ($model['static'] as $key => $value) {
                $out = preg_replace('/'.$key.'/',$value,$out);
            }
        }
        return $out;
    }

    function render(){
        echo $this->setUpTemplate();
    }

    function addResource($values){
        foreach ($values as $key => $value) {
            $model = array(
                'type' => $key,
                'value' => $value
            );
            return setUpTemplate(ROOT.'/templates/layouts/resources.php',$model);   
        }
    }
}
?>