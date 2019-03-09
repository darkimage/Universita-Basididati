
<?php
    $template = new template\PageModel();
    $template->model = $this->model;
    $template->body = $this->getStatic('Body');
    $template->templateFile = $this->model['file'];
    $template->render();
?>