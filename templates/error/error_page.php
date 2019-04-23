
<div class="alert alert-danger text-center" role="alert">
    <?php
        if(isset($this->model['error']))
            echo $this->model['error'];
        else
            echo L::error_general
    ?>
</div>