<t-flashmessage />
<div class="material-container-static">
<t-if test="@{authorized}">
    <div class="d-flex justify-content-end">
    <div>
    <t-link controller="user" action="edit" overwrite params="${return ['id'=>$this->user->id]}">
    <button type="button" class="btn btn-warning">${echo L::common_edit}</button>
    </t-link>
    <t-link controller="user" action="remove" overwrite params="${return ['id'=>$this->user->id]}" class="btn btn-danger ml-2">
        <i class="fas fa-trash-alt py-1"></i>
    </t-link>
    </div>
    </div>
</t-if>
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
    <div class="row mt-2">
    <div class="col-sm">
        <div class="text-primary font-weight-bold">
            ${echo L::task_plural.":"}
        </div>
        <div class="task-container">
            <t-tasklist tasks="@{tasks}"/>
        </div>
    </div>
    </div>
</div>
<div class="col-sm mt-2">
    <h2><?php echo L::project_plural ?></h2>
    <div class="project-container">
        <t-listprojects projects="@{projects}" />
    </div>
</div>
</div>
</div>
</div>