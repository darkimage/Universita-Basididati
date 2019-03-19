<?php $unique = substr(base64_encode(mt_rand()), 0, 15); ?>
<form id="<?php echo $unique; ?>" action="<?php echo $this->model['action'];?>" method="<?php echo $this->model['method'];?>">
    <input type="hidden" name="domain" value="<?php echo $this->model['domain'];?>">
    <input type="hidden" name="referee" value="<?php echo $this->model['referee'];?>">
    <?php echo $this->getStatic('Body'); ?>
</form>