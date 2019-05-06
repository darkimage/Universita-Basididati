<t-form controller="group" action="process" domain="tGroup">
<h1 class="border-0"><!--Title--></h1>
<div class="pt-3 pl-5">
<input type="hidden" name="id" id="id" value="@{group->id:[]}">
<input type="hidden" name="update" id="update" value="@{update:[false]}">
<label for="Nome"><?php echo L::group_formname ?>:</label>
<input type="text" class="form-control" required id="Nome" name="Nome" value="@{group->Nome:[]}" placeholder="${return L::group_formname}">
<div class="d-flex justify-content-end">
    <input type="submit" class="mt-2 btn btn-primary" value="${return L::common_submit}" name="Submit">
</div>
</div>
</t-form>