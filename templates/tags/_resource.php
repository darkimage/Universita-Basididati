<?php
if(isset($this->type))
{
    $type = $this->type;
    $src = $this->src;
    if($type == 'stylesheet'){
        if( $src[0] != '.')
            $src = "/stylesheets"."/".$src;
        else
            $src = substr($src,1);
        echo "<link rel='stylesheet' href='".$src."'>";
    }
    if($type == 'script'){
        if($this->getStatic("Body")){
            echo "<script>".$this->getStatic("Body")."</script>";
        }else{
            echo "<script src='/scripts/". $src."'/>";
        }
    }
}
?>