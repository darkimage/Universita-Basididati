<?php
$template = new template\PageModel();
$template->templateFile = $this->path;
$template->model = $this->model['model'];
$template->body = $this->getStatic('Body');
$template->render();
?>