<t-if test="${Count($this->projects)}">
<t-each collection="@{projects}" item="project">
<div class="card mt-2 mr-1">
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
    <div class="row" style="width:100%">
    <div class="alert alert-secondary inline ml-3" style="width:100%" role="alert">
        ${echo L::user_noprojects }
    </div>
    </div>
</t-if>