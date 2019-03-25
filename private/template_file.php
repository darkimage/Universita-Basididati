<?php
namespace template;
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT.'/private/i18n.php');
require_once(ROOT.'/private/utils.php');
require_once(ROOT.'/private/tagProcessor.php');

class PageModel{
    private $fromString = false;
    private $isRaw = false;
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

    function setSourceString(String $source){
        $this->fromString = true;
        $this->templateFile = $source;
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

    public function setRaw(){
        $this->isRaw = true;
    }

    function getStatic(String $attr){
        return $this->model['static']['<!--'.$attr.'-->'];
    }

    private function setUp(){
        $this->getModel();
        $contents = '';
        if(!$this->isRaw){
            ob_start();
            if(!$this->fromString){
                require(ROOT . $this->templateFile);
            }else{
                eval('?>'.$this->templateFile);
            }
            $contents = ob_get_contents();
            ob_end_clean();
        }else{
            if(!$this->fromString){
                $contents = file_get_contents(ROOT . $this->templateFile);
            }else{
                $contents = $this->templateFile;
            }
        }


        $out = $contents;
        if(array_key_exists('static',$this->model)){
            foreach ($this->model['static'] as $key => $value) {
                $out = preg_replace('/'.$key.'/',$value,$out);
            }
        }

        //Remove comments before processind HTML nodes
        $out = preg_replace('/<!--(.*?)-->/s',"",$out);


        $processor = new \TagProcessor($out,$this);
        return $processor;
    }

    function setUpTemplateDOM(){
        return $this->setUp()->processTagsDOM();
    }

    function setUpTemplate(){
        return $this->setUp()->processTags();
    }

    function render(){
        echo $this->setUpTemplate();
    }

    public function addToModel($model){
        if($model){
            $this->model = array_merge($model,$this->model);
        }
        return $this->model;
    }

    function addResource($values){
        $res = '';
        foreach ($values as $key => $value) {
            $resource = new PageModel();
            $resource->templateFile = '/templates/layouts/resources.php';
            $resource->model = array(
                'type' => $key,
                'value' => $value
            );
            $res .= $resource->setUpTemplate();   
        }
        return $res;
    }
}
?>