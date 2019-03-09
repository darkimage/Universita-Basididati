<?php
if($this->model['type'] == 'css'){
    echo "<link rel='stylesheet'", "href='", $this->model['value'],"'>";
}
?>