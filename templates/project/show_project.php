<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
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
        </div> 
        <div class="row pt-2">
            <div class="col-sm pt-4 mr-2">
                <div class="text-primary font-weight-bold">
                    ${echo L::project_formdescription.":"}
                </div>
                ${echo $this->project->Descrizione;}
            </div>
        </div>
        </div>
        <div class="col-sm-4 pt-4 material-container">
            <div class="text-primary font-weight-bold">
                ${echo L::project_users.":"}
            </div>
            <div class="user-container">
            <t-each collection="@{users}" item="user">
                <div class="material-container-static m-2 inline" style="width:90%">
                    ${echo $this->user['user']->Nome}
                    <span class="badge badge-secondary">${echo $this->user['role'];}</span>
                </div>
            </t-each>
            </div>
        </div>
    </div>
</div>