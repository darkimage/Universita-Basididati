<h1><!--Title--></h1>
<div class="d-flex justify-content-end">
<t-link controller="project" action="add" class="btn btn-primary" overwrite params="${return []}"><?php echo L::project_add ?></t-link>
</div>
<t-each collection="@{projects}" item="project">
    <div class="mt-2 material-container">
        <div class="item inline" style="font-size: large; width:100%;">
        <t-link controller="project" action="show" class="capitalize" overwrite params="${return ['id'=>$this->project->id];}">
            ${echo $this->project->Nome;}
        </t-link>
        <t-if test="@{project->Completato}">
            <span class="badge badge-pill badge-success">
                ${echo L::project_completed;}
            </span>
        </t-if>
        </div>
        <div class="container-fluid">
        <div class="row">
        <div class="col-sm pt-1">
            <span class="truncate">${echo $this->project->Descrizione;}</span>
        </div>
        <div class="col-sm pt-3">
            <div class="row">
            <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->project->Creatore->id];}" class="badge badge-primary">
                ${echo L::user_creator.": ".$this->project->Creatore->Nome;}
            </t-link>
            </div>
            <div class="row">
                <span class="badge badge-pill badge-primary mt-2">
                    ${echo L::project_startdate.": ".date("d/m/Y",strtotime($this->project->DataInizio));}
                </span>
            </div>
            <div class="row">
                <span class="badge badge-pill badge-primary mt-2">
                    ${echo L::project_enddate.": ".date("d/m/Y",strtotime($this->project->DataScadenza));}
                </span>
            </div>
        </div>
        </div>
        </div>
    </div>
</t-each>
<t-pagination 
    count="@{projectsCount}" 
    list="@{projects}" 
    params="@{params}"
    controller="project"
    action="index"/>