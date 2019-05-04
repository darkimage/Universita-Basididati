<h1><!--Title--></h1>
<t-each collection="@{tasks}" item="task">
    <div class="mt-2 material-container">
        <div class="item inline" style="font-size: large; width:100%;">
        <t-link controller="task" action="show" class="capitalize" overwrite params="${return ['id'=>$this->task->id];}">
            ${echo $this->task->Nome;}
        </t-link>
        <t-if test="@{task->Completata}">
            <span class="badge badge-pill badge-success">
                ${echo L::task_completed;}
            </span>
        </t-if>
        </div>
        <div class="container-fluid">
        <div class="row">
        <div class="col-sm pt-1">
            <div class="row">
                <span class="truncate">${echo $this->task->Descrizione;}</span>
            </div>
            <div class="row">
                <div class="text-secondary font-weight-bold">${echo L::project_title.":"}</div>
                <span class="ml-1">${echo $this->task->Project->Nome;}</span>
            </div>
            <div class="row">
                <div class="text-secondary font-weight-bold">${echo L::task_dependencies.":"}</div>
                <span class="ml-1">${echo $this->task->TaskListCount.' Tasks';}</span>
            </div>
            <div class="row">
                <div class="text-secondary font-weight-bold">${echo L::task_assignee.":"}</div>
                <t-if test="${$this->task->Assignee->User}">
                    <span class="ml-1">
                        <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->task->Assignee->User->id]}">
                            ${echo $this->task->Assignee->User->NomeUtente;}
                        </t-link>
                    </span>
                </t-if>
                <t-if test="${$this->task->Assignee->tGroup}">
                    <span class="ml-1">
                        <t-link controller="group" action="show" overwrite params="${return ['id'=>$this->task->Assignee->tGroup->id]}">
                            ${echo $this->task->Assignee->tGroup->Nome;}
                        </t-link>
                    </span>
                </t-if>
            </div>
        </div>
        <div class="col-sm pt-3">
            <div class="row">
            <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->task->User->id];}" class="badge badge-primary">
                ${echo L::task_creator.": ".$this->task->User->Nome;}
            </t-link>
            </div>
            <div class="row">
                <span class="badge badge-pill badge-primary mt-2">
                    ${echo L::task_creationdate.": ".date("d/m/Y",strtotime($this->task->DataCreazione));}
                </span>
            </div>
            <div class="row">
                <span class="badge badge-pill badge-primary mt-2">
                    ${echo L::project_enddate.": ".date("d/m/Y",strtotime($this->task->DataScadenza));}
                </span>
            </div>
        </div>
        </div>
        </div>
    </div>
</t-each>
<t-pagination 
    count="@{tasksCount}" 
    list="@{tasks}" 
    params="@{params}"
    controller="task"
    action="index"/>