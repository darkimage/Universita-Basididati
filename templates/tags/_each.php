<div>
<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/tagProcessor.php");

    foreach ($this->collection as $key => $value) {
        $template = new template\PageModel();
        $template->model = array(
            $this->key => $key,
            $this->item => $value
        );
        $template->addToModel($this->model);
        $template->setSourceString($this->getStatic('Body'));
        $res = $template->setUpTemplateDOM();
        echo $res->saveHTML($res->getElementsByTagName('body')->item(0));
    }
?>
</div>