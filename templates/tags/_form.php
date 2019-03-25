<?php $unique = substr(base64_encode(mt_rand()), 0, 15); ?>
<form id="<?php echo $unique; ?>" action="@{action}" method="@{method}">
    <input type="hidden" name="domain" value="@{domain}">
    <input type="hidden" name="referee" value="@{referee}">
    <?php echo $this->getStatic('Body'); ?>
</form>