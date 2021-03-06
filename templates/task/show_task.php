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
            <t-link controller="task" action="remove" overwrite params="${return ['id'=>$this->task->id]}" class="btn btn-danger ml-2">
                <i class="fas fa-trash-alt pt-1"></i>
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
            <t-if test="${isset($this->task->Assignee) ? $this->task->Assignee->User : false}">
            <div class="row mt-2 ml-1">
                <div class="material-container-static inline" style="width:100%">
                <span class="badge badge-pill badge-secondary ml-2">${echo L::common_user}</span>
                <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->task->Assignee->User->id]}">
                    ${echo $this->task->Assignee->User->Nome.' ('.$this->task->Assignee->User->NomeUtente.')'}
                </t-link>
                </div>
            </div>
            </t-if>
            <t-if test="${isset($this->task->Assignee) ? $this->task->Assignee->tGroup : false}">
            <div class="row mt-2 ml-1">
                <div class="material-container-static inline" style="width:100%">
                <span class="badge badge-pill badge-secondary ml-2">${echo L::common_group}</span>
                <t-link controller="group" action="show" overwrite params="${return ['id'=>$this->task->Assignee->tGroup->id]}">
                    ${echo $this->task->Assignee->tGroup->Nome}
                </t-link>
                </div>
            </div>
            </t-if>
            <t-if test="${!$this->task->Assignee}">
            <div class="row mt-2 ml-1">
                <div class="alert alert-secondary text-center inline pt-2" role="alert" style="width:100%">
                    ${echo L::task_noassignee}
                </div>
            </div>
            </t-if>
            <div class="row ml-1 pt-2">
                <div class="col-sm">
                    <div class="text-primary font-weight-bold row">
                        ${echo L::task_sharedwith.":"}
                    </div>
                </div>
            </div>
            <div id="user-share-container">
            <t-if test="${$this->task->SharedTask}">
            <div id="user-share" class="row mt-2 ml-1">
                <div class="material-container-static inline" style="width:100%">
                <span class="badge badge-pill badge-secondary ml-2">${echo L::common_group}</span>
                <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->task->Condivisore->id]}">
                    ${echo $this->task->Condivisore->Nome.' ('.$this->task->Condivisore->NomeUtente.')'}
                </t-link>
                </div>
            </div>
            </t-if>
            </div>
            <div id="share" class="${return ($this->task->SharedTask)?'d-none':''}">
            <div class="alert alert-secondary text-center inline mt-2" role="alert" style="width:100%">
                ${echo L::task_noshared}
            </div>
            <t-if test="@{authorized}">
            <div class="d-flex justify-content-end">
            <div class="mr-2" style="width:100%">
                <input type="text" class="form-control" id="shareinput" autocomplete="off"
                onkeyup="${return 'searchUserShare(this,\'userdropdown\','.$this->task->id.')'}"
                onfocus="showDropDown('userdropdown',this)"
                onfocusout="hideDropDown('userdropdown')"/>
                <div id="userdropdown" class="dropdown-content d-none"></div>
            </div>
            <div><button type="button" id="sharebtn" class="btn btn-primary inline"><i class="fas fa-share-alt mr-1"></i>${echo L::task_share}</button></div>
            </div>
            </div>
            </t-if>
            <div class="d-flex justify-content-end">
                <div><button type="button" id="sharebtnremove" class="${return 'btn btn-danger inline mt-2 '.(($this->task->SharedTask)?'':'d-none')}"
                onclick="${return 'unShareTask('.$this->task->SharedTask.')'}"
                ><i class="fas fa-user-times mr-1"></i>${echo L::task_unshare}</button></div>
            </div>
        </div>
    </div>
</div>