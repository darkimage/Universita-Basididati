<div class="material-container-static">
<h1><!--Title--></h1>
<div class="container-fluid pt-2">
<div class="row">
<div class="col-sm">
    <div class="user-field pt-1">
        <div class="font-weight-bold"><?php echo L::user_username.':' ?></div>
        <div class="item inline" style="width:100%">
            <span class="pl-2">${echo $this->user->NomeUtente;}</span>
        </div>
    </div>
    <div class="user-field pt-1">
        <div class="font-weight-bold"><?php echo L::user_name.':' ?></div>
        <div class="item inline" style="width:100%">
            <span class="pl-2">${echo $this->user->Nome;}</span>
        </div>
    </div>
    <div class="user-field pt-1">
        <div class="font-weight-bold"><?php echo L::user_surname.':' ?></div>
        <div class="item inline" style="width:100%">
            <span class="pl-2">${echo $this->user->Cognome;}</span>
        </div>
    </div>
    <div class="user-field pt-1">
        <div class="font-weight-bold"><?php echo L::user_birthday.':' ?></div>
        <div class="item inline" style="width:100%">
            <span class="pl-2">${echo date("d/m/Y",strtotime($this->user->DataNascita));}</span>
        </div>
    </div>
    <div class="user-field pt-3">
        <div class="text-primary font-weight-bold">
            <?php echo L::group_plural.':' ?>
        </div>
        <t-if test="${Count($this->groups)}">
            <t-each collection="@{groups}" item="group">
                <span class="badge badge-secondary mr-1">${echo $this->group->Nome}</span>
            </t-each>
        </t-if>
        <t-if test="${!Count($this->groups)}">
            <div class="alert alert-secondary inline" style="width:100%" role="alert">
                ${ echo L::user_nogroups }
            </div>
        </t-if>
    </div>
    <div class="row mt-2 mr-2">
        <t-tasklist tasks="@{tasks}" />
    </div>
</div>
<div class="col-sm mt-2">
    <h2><?php echo L::project_plural ?></h2>
    <t-if test="${Count($this->projects)}">
    <t-each collection="@{projects}" item="project">
    <div class="card mt-2">
        <div class="card-header">
            <div class="inline">
            <t-link controller="project" action="show" class="capitalize" overwrite params="${return ['id'=>$this->project->id];}">
                ${echo $this->project->Nome;}
            </t-link>
            </div>
            <t-if test="@{project->Completato}">
                <span class="badge badge-pill badge-success ml-2">
                    ${echo L::project_completed.": ".date("d/m/Y",strtotime($this->project->DataCompletamento));}
                </span>
            </t-if>
        </div>
        <div class="card-body">
            <span class="badge badge-pill badge-primary ml-2">
                ${echo L::project_startdate.": ".date("d/m/Y",strtotime($this->project->DataInizio));}
            </span>
            <span class="badge badge-pill badge-primary ml-2">
                ${echo L::project_enddate.": ".date("d/m/Y",strtotime($this->project->DataScadenza));}
            </span>
        </div>
    </div>
    </t-each>
    </t-if>
    <t-if test="${!Count($this->projects)}">
        <div class="alert alert-secondary inline" style="width:100%" role="alert">
            ${echo L::user_noprojects }
        </div>
    </t-if>
</div>
</div>
</div>
</div>