
<div class="alert alert-danger text-center" role="alert">
    <?php
        if(isset($this->error))
            echo $this->error;
        else
            echo L::error_general
    ?>
</div>