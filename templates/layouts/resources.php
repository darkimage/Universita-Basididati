<?php
if($this->model['type'] == 'css'){
    echo "<link rel='stylesheet'", "href='", "/stylesheets/".$this->model['value'].".css","'>";
}
if($this->model['type'] == 'js'){
    echo "<script src='","/scripts/".$this->model['value'].".js","'></script>";
}
?>