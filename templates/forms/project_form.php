<t-form controller="projectcontroller" action="add" domain="project">
    <div class ="container-fluid">
        <h1 class="border-0"><?php echo L::project_formadd; ?></h1>
        <div class="pt-3 pl-5">
        <label for="Nome"><?php echo L::project_formname ?>:</label>
        <input type="text" class="form-control" required id="Nome" name="Nome" value="@{Nome:[${echo '';}]}" placeholder="<?php echo L::project_formname ?>">

        <label class="pt-2" for="Descrizione"><?php echo L::project_formdescription ?>:</label>
        <textarea class="form-control" required id="Descrizione" name="Descrizione" placeholder="<?php echo L::project_formdescription ?>"><?php if(isset($this->model['Descrizione'])) echo $this->model['Descrizione']; else ""; ?></textarea>

        <label class="pt-2" for="DataScadenza"><?php echo L::project_formduedate ?>:</label>
        <input type="date" class="form-control" required id="DataScadenza" name="DataScadenza" value="@{DataScadenza:[${echo date('Y-m-d');}]}">

        <input type="submit" class="mt-2 btn btn-primary" value="<?php echo L::common_submit ?>" name="Submit">
        </div>
    </div>
</t-form>