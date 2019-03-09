<?php 
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/private/tagProcessor.php");

foreach ($this->model['collection'] as $key => $value) {
    $template = new template\PageModel();
    $template->model = array(
        $this->model['key'] => $key,
        $this->model['item'] => $value
    );
    $template->setSourceString($this->getStatic('Body'));
    $res = $template->setUpTemplateDOM();
    echo $res->saveHTML($res->getElementsByTagName('body')->item(0));
}
?>