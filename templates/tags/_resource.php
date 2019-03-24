<?php
if(isset($this->model['type']))
{
    $type = $this->model['type'];
    if($type == 'stylesheet'){
        echo "<link rel='stylesheet' href=".$this->model['src'].">";
    }
}
?>