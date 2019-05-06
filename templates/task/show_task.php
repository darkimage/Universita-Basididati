<t-flashmessage />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 material-container-static">
        <div class="row ml-1 inline justify-content-end d-inline-flex" style="width:100%">
        <t-if test="@{authorized}">
            <t-link controller="task" action="edit" overwrite params="${return ['id'=>$this->task->id]}" class="btn btn-warning ml-2">
                ${echo L::common_edit;}
            </t-link>
            <t-link controller="task" action="complete" overwrite params="${return ['id'=>$this->task->id]}" class="${ return 'btn btn-success ml-2 '.((!$this->task->Completata)?'':'disabled')}">
                ${echo L::task_completebutton;}
            </t-link>
        </t-if>
        </div>
        <div class="header inline" style="width:100%">
            <div class="inline capitalize">${echo 'Task: '.$this->task->Nome}</div>
            <t-if test="@{task->Completata}">
                <span class="badge badge-pill badge-success">
                    ${echo L::task_completed;}
                </span>
            </t-if>
        </div>
        <div class="row mt-2">
            <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->task->User->id];}" class="badge badge-primary ml-2">
                ${echo L::task_creator.": ".$this->task->User->Nome;}
            </t-link>
            <span class="badge badge-pill badge-primary ml-2">
                ${echo L::task_creationdate.": ".date("d/m/Y",strtotime($this->task->DataCreazione));}
            </span>
            <span class="badge badge-pill badge-primary ml-2">
                ${echo L::task_enddate.": ".date("d/m/Y",strtotime($this->task->DataScadenza));}
            </span>
            <t-if test="@{task->Completata}">
                <span class="badge badge-pill badge-success ml-2">
                    ${echo L::task_completed.": ".date("d/m/Y",strtotime($this->task->DataCompletamento));}
                </span>
            </t-if>
        </div>
        <div class="row pt-2 mr-2">
            <div class="col-sm pt-2 mr-2">
                <div class="text-primary font-weight-bold">
                    ${echo L::task_formdescription.":"}
                </div>
                <div class="multiline">${echo $this->task->Descrizione;}</div>
            </div>
        </div>
        <t-if test="@{list}">
        <div class="row mt-2 mr-2">
            <div class="col-sm mr-2 ">
                <div class="text-primary font-weight-bold">
                    ${echo L::task_dependencies.":"}
                </div>
                <ul class="list-group">
                    <t-each collection="@{list}" >
                        <li class="list-group-item">
                        <div class="row">
                        <div class="col-sm">
                        <div clas="row">
                        <t-link controller="task" action="show" overwrite params="${return ['id'=>$this->item->Task->id]}">
                            ${echo $this->item->Task->Nome;}
                        </div>
                        <div clas="row">                
                        <span class="badge badge-pill badge-secondary ml-2">
                            ${echo L::task_enddate.": ".date("d/m/Y",strtotime($this->item->Task->DataScadenza));}
                        </span></div>
                        </div>
                        <t-if test="${$this->item->Task->Completata}">
                        <div class="col-sm d-flex justify-content-end"><i class="fas fa-2x fa-check-circle" style="color:#33cc33"></i></div>
                        </t-if>
                        </div>
                        </li>
                    </t-each>
                </ul>
            </div>
        </div>
        </t-if>
        </div>
        <div class="col-sm-4 material-container">
            <div class="row ml-1">
                <div class="col-sm">
                    <div class="text-primary font-weight-bold row">
                        ${echo L::task_assignee.":"}
                    </div>
                </div>
            </div>
            <t-if test="@{task->Assignee->User}">
            <div class="row mt-2 ml-1">
                <div class="material-container-static inline" style="width:100%">
                <span class="badge badge-pill badge-secondary ml-2">${echo L::common_user}</span>
                <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->task->Assignee->User->id]}">
                    ${echo $this->task->Assignee->User->Nome.' ('.$this->task->Assignee->User->NomeUtente.')'}
                </t-link>
                </div>
            </div>
            </t-if>
            <t-if test="@{task->Assignee->tGroup}">
            <div class="row mt-2 ml-1">
                <div class="material-container-static inline" style="width:100%">
                <span class="badge badge-pill badge-secondary ml-2">${echo L::common_group}</span>
                <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->task->Assignee->tGroup->id]}">
                    ${echo $this->task->Assignee->tGroup->Nome}
                </t-link>
                </div>
            </div>
            </t-if>
            <t-if test="${!$this->task->Assignee}">
            <div class="row mt-2 ml-1">
                <div class="material-container-static inline" style="width:100%">
                <div class="alert alert-secondary text-center" role="alert">
                    ${echo L::task_noassignee}
                </div>
                </div>
            </div>
            </t-if>
        </div>
    </div>
</div>