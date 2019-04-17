<t-form action="projectController?action=add" domain="project">
<div class ="container-fluid">
<h1 class="border-0"><?php echo L::project_formadd; ?></h1>
<div class="pt-3 pl-5">
<label for="name"><?php echo L::project_formname ?>:</label>
<input type="text" class="form-control" required id="name" name="name" value="@{nome:[${echo '';}]}" placeholder="<?php echo L::project_formname ?>">

<label class="pt-2" for="descrizione"><?php echo L::project_formdescription ?>:</label>
<textarea class="form-control" required id="descrizione" name="descrizione" value="@{descrizione:[${echo '';}]}" placeholder="<?php echo L::project_formdescription ?>"></textarea>

<label class="pt-2" for="datascadenza"><?php echo L::project_formduedate ?>:</label>
<input type="date" class="form-control" required id="datascadenza" name="datascadenza" value="@{nome:[${echo '';}]}" placeholder="<?php echo date('d-m-y');?>">

<input type="submit" class="mt-2 btn btn-primary" value="<?php echo L::common_submit ?>" name="Submit">
</div>
</div>
</t-form>
<button onclick="navigateAnchor(this);" value="Test">