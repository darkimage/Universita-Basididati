<t-flashmessage />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 material-container-static">
        <div class="row ml-1 inline justify-content-end d-inline-flex" style="width:100%">
        <t-if test="@{authorized}">
            <t-link controller="project" action="edit" overwrite params="${return ['id'=>$this->project->id]}" class="btn btn-warning ml-2">
                ${echo L::common_edit;}
            </t-link>
            <t-link controller="project" action="complete" overwrite params="${return ['id'=>$this->project->id]}" class="${ return 'btn btn-success ml-2 '.((!$this->project->Completato)?'':'disabled')}">
                ${echo L::project_completebutton;}
            </t-link>
        </t-if>
        </div>
        <div class="header inline" style="width:100%">
            <div class="inline capitalize">${echo $this->project->Nome}</div>
            <t-if test="@{project->Completato}">
                <span class="badge badge-pill badge-success">
                    ${echo L::project_completed;}
                </span>
            </t-if>
        </div>
        <div class="row mt-2">
            <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->project->Creatore->id];}" class="badge badge-primary ml-2">
                ${echo L::user_creator.": ".$this->project->Creatore->Nome;}
            </t-link>
            <span class="badge badge-pill badge-primary ml-2">
                ${echo L::project_startdate.": ".date("d/m/Y",strtotime($this->project->DataInizio));}
            </span>
            <span class="badge badge-pill badge-primary ml-2">
                ${echo L::project_enddate.": ".date("d/m/Y",strtotime($this->project->DataScadenza));}
            </span>
            <t-if test="@{project->Completato}">
                <span class="badge badge-pill badge-success ml-2">
                    ${echo L::project_completed.": ".date("d/m/Y",strtotime($this->project->DataCompletamento));}
                </span>
            </t-if>
        </div> 
        <div class="row pt-2 mr-2">
            <div class="col-sm pt-2 mr-2">
                <div class="text-primary font-weight-bold">
                    ${echo L::project_formdescription.":"}
                </div>
                <div class="multiline">${echo $this->project->Descrizione;}</div>
            </div>
        </div>
        <div class="row pt-2 mr-2">
            <div class="row ml-1 inline justify-content-end d-inline-flex" style="width:100%">
            <t-if test="@{authorized}">
                <t-link controller="task" action="add" overwrite params="${return ['id'=>$this->project->id]}" class="btn btn-primary ml-2 inline">
                <i class="fas fa-plus mr-1"></i>${echo L::task_add;}
                </t-link>
            </t-if>
            </div>
        </div>
        <div class="row mt-2 mr-2">
            <t-tasklist tasks="@{tasks}" />
        </div>
        </div>
        <div class="col-sm-4 material-container">
            <div class="row ml-1">
                <div class="col-sm">
                    <div class="text-primary font-weight-bold row">
                        ${echo L::project_users.":"}
                    </div>
                    <div class="row">
                        <input class="form-control" type="text" onkeyup="searchuser(this)" placeholder="<?php echo L::project_searchuser; ?>"/> 
                    </div>
                    <div class="user-container row">
                    <t-if test="${Count($this->users)}">
                        <t-each collection="@{users}" item="user">
                            <div class="material-container-static m-2 inline user" id="${return $this->user->Nome.' ('.$this->user->NomeUtente.')'}" style="width:90%">
                                ${echo $this->user->Nome.' ('.$this->user->NomeUtente.')'}
                                <span class="badge badge-secondary">${echo $this->user->Authority;}</span>
                                <span class="badge badge-secondary">${echo $this->user->GroupName;}</span>
                            </div>
                        </t-each>
                    </t-if>
                    <t-if test="${!Count($this->users)}">
                        <div class="text-center alert alert-secondary" role="alert" style="width:100%">
                        <?php echo L::project_nousers; ?>
                        </div>
                    </t-if>
                    </div>
                </div>
            </div>
            <div class="row pt-2 ml-1">
                <div class="col-sm">
                    <div class="text-primary font-weight-bold row">
                        ${echo L::project_groups.":"}
                    </div>
                    <div class="user-container row pt-2">
                        <t-if test="${Count($this->groups)}">
                            <t-each collection="@{groups}" item="group">
                                <span class="badge badge-secondary mr-1">${echo $this->group->Nome}</span>
                            </t-each>
                        </t-if>
                        <t-if test="${!Count($this->groups)}">
                            <div class="text-center alert alert-secondary" role="alert" style="width:100%">
                            <?php echo L::project_nogroups ?>
                            </div>
                        </t-if>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>