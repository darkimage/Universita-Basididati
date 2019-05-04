<?php $this->formid = substr(md5(time()), 0, 16); ?>
<form id="<?php echo $this->formid; ?>" action="@{action}" method="@{method}">
    <t-if test=@{hidden}>
        <input type="hidden" name="domain" value="@{domain}">
        <input type="hidden" name="referee" value="@{referee}">
    </t-if>
    <?php echo $this->getStatic('Body'); ?>
</form>