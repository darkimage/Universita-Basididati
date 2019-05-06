<t-form controller="task" action="process" domain="task">
    <div class ="container-fluid">
        <h1 class="border-0"><!--Title--></h1>
        <div class="pt-3 pl-5">
            <input type="hidden" name="id" id="id" value="@{task->id:[]}"/>
            <input type="hidden" name="DataCreazione" id="DataCreazione" value="@{task->DataCreazione:[${return date('Y-m-d');}]}"/>
            <input type="hidden" name="Completata" id="Completata" value="@{task->Completata:[0]}">
            <input type="hidden" name="User" id="User" value="@{task->User->id:[${return $this->user->id;}]}">
            <input type="hidden" name="Project" id="Project" value="@{task->Project->id:[${return $this->project->id;}]}">
            <input type="hidden" name="update" id="update" value="@{update:[false]}">
            <input type="hidden" name="Assignee" id="Assignee" value="@{task->Assignee->id:[]}"/>
            <input type="hidden" name="TaskList" id="TaskList" value="@{tasklist->id:[]}"/>

            <label for="Nome"><?php echo L::task_formname ?>:</label>
            <input type="text" class="form-control" required id="Nome" name="Nome" value="@{task->Nome:[]}" placeholder="<?php echo L::task_formname ?>"/>

            <label class="pt-2" for="Descrizione"><?php echo L::project_formdescription ?>:</label>
            <textarea class="form-control" required id="Descrizione" name="Descrizione" placeholder="${ return L::project_formdescription}"><?php if(isset($this->task->Descrizione)) echo $this->task->Descrizione; else ""; ?></textarea>

            <label class="pt-2" for="DataScadenza"><?php echo L::project_formduedate ?>:</label>
            <input type="date" class="form-control" required id="DataScadenza" name="DataScadenza" value="@{task->DataScadenza:[${return date('Y-m-d');}]}"/>

            <label class="pt-2" for="_Assignee"><?php echo L::task_formassignee ?>:</label>
            <input type="text" class="form-control" id="_Assignee" name="_Assignee" autocomplete="off" value="${
                if(isset($this->task)){
                    if(isset($this->task->User)) return $this->task->User->NomeUtente.' ('.$this->task->User->Nome.')';
                    else return $this->task->tGroup->Nome;
                }else return '';
            }"
            onkeyup="${return 'searchUsers(this,\'userdropdown\','.$this->project->id.',5)'}"
            onfocus="showDropDown('userdropdown',this)"
            onfocusout="hideDropDown('userdropdown')"/>
            <div id="userdropdown" class="dropdown-content d-none"></div>

            <t-if test="@{update}">
            <div>
                <button id='tasksadd' type="button" class="${ return 'btn mt-4 btn-primary '.(($this->tasklist)?'d-none':'')}" 
                onclick="${return 'addTaskList(\'tasks\','.$this->task->id.','.$this->task->Project->id.')'}">
                    <i class="fas fa-plus"></i>  <?php echo L::task_addtlist ?>
                </button>
                <button id='tasksremove' type="button" class="${ return 'btn mt-4 btn-danger '.((!$this->tasklist)?'d-none':'')}"
                onclick="${if($this->tasklist) return 'removeTaskList(\'tasks\','.$this->tasklist->id.')'}">
                    <i class="fas fa-minus"></i>  <?php echo L::task_removetlist ?>
                </button>
            </div>
            <div id="tasks" class="${return (!$this->tasklist)?'d-none':''}">
            <label class="pt-2" for="tasks"><?php echo L::task_add ?>:</label>
            <div>
                <input type="text" class="form-control" id="_tasks" name="_tasks" autocomplete="off"
                onfocus="showDropDown('tasksdropdown',this)"
                onfocusout="hideDropDown('tasksdropdown')"/>
                <div id="tasksdropdown" class="dropdown-content"></div>
                <ul id="taskslist" class="list-group">

                </ul>
            </div>
            </div>
            </t-if>

            <div class="d-flex justify-content-end">
            <input type="submit" class="mt-2 btn btn-primary" value="<?php echo L::common_submit ?>" name="Submit">
            </div>
        </div>
    </div>
</t-form>