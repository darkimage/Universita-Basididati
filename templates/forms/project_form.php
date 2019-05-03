<t-form controller="project" action="add" domain="project">
    <div class ="container-fluid">
        <h1 class="border-0"><!--Title--></h1>
        <div class="pt-3 pl-5">
        <input type="hidden" name="id" id="id" value="@{project->id:[]}">
        <input type="hidden" name="Completato" id="Completato" value="@{project->Completato:[0]}">
        <input type="hidden" name="DataInizio" id="DataInizio" value="@{project->DataInizio:[${return date('Y-m-d');}]}">
        <input type="hidden" name="DataScadenza" id="DataScadenza" value="@{project->DataScadenza:[]}">
        <input type="hidden" name="Creatore" id="Creatore" value="@{project->Creatore->id:[${return $this->user->id;}]}">
        <input type="hidden" name="update" id="update" value="@{update:[false]}">
        <label for="Nome"><?php echo L::project_formname ?>:</label>
        <input type="text" class="form-control" required id="Nome" name="Nome" value="@{project->Nome:[]}" placeholder="<?php echo L::project_formname ?>">

        <label class="pt-2" for="Descrizione"><?php echo L::project_formdescription ?>:</label>
        <textarea class="form-control" required id="Descrizione" name="Descrizione" placeholder="<?php echo L::project_formdescription ?>"><?php if(isset($this->project->Descrizione)) echo $this->project->Descrizione; else ""; ?></textarea>

        <label class="pt-2" for="DataScadenza"><?php echo L::project_formduedate ?>:</label>
        <input type="date" class="form-control" required id="DataScadenza" name="DataScadenza" value="@{project->DataScadenza:[${return date('Y-m-d');}]}">

        <t-if test="${$this->update == true}">
        <div class="cust-dropdown">
            <label for="addGroup" class="pt-2"><?php echo L::project_addgroup ?>:</label>
            <input type="text" class="form-control" placeholder="<?php echo L::project_addgroup ?>" 
            onkeyup="${return 'searchGroups(this,\'groupdropdown\','.$this->project->id.')'}" 
            onfocus="showDropDown('groupdropdown',this)"
            onfocusout="hideDropDown('groupdropdown')">
            <div id="groupdropdown" class="dropdown-content d-none">
            </div>
        </div>
        <div class="group-error alert alert-danger d-none mt-2">
            <div class="inline">${echo L::error_cantaddgroup;}</div>
        </div>
        <div class="group-container pt-2">
            <t-each collection="@{groups}" item="group">
            <span id="${return 'group'.$this->group->id}" class="badge badge-secondary inline">${echo $this->group->Nome}
                <button type="button" class="close" aria-label="Close"
                onclick="${ return 'removeGroup(\'#group'.$this->group->id.'\','.$this->group->id.','.$this->project->id.')'}">
                    <i class="fas fa-window-close fa-lg"></i>
                </button>
            </span>
            </t-each>
        </div>
        </t-if>

        <input type="submit" class="mt-2 btn btn-primary" value="<?php echo L::common_submit ?>" name="Submit">
        </div>
    </div>
</t-form>