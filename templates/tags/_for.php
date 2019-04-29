<body>
<?php 
    for ($i=0; $i < $this->count; $i++) {
        $template = new template\PageModel();
        $template->model = [
            'index' => $i
        ];
        $template->addToModel($this->model);
        $template->setSourceString($this->getStatic('Body'));
        $res = $template->setUpTemplateDOM();
        $test = $res->saveHTML();
        echo $res->saveHTML($res->getElementsByTagName('body')->item(0));
    }
?>
</body>