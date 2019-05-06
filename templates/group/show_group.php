<t-flashmessage />
<div class="container-fluid">
<div class="row">
    <div class="col-sm-8 material-container-static">
    <div class="row ml-1 inline justify-content-end d-inline-flex" style="width:100%">
    <t-if test="@{authorized}">
        <t-link controller="group" action="edit" overwrite params="${return ['id'=>$this->group->id]}" class="btn btn-warning ml-2">
            ${echo L::common_edit;}
        </t-link>
        <t-link controller="group" action="remove" overwrite params="${return ['id'=>$this->group->id]}" class="btn btn-danger ml-2">
            <i class="fas fa-trash-alt pt-1"></i>
        </t-link>
    </t-if>
    </div>
    <div class="header inline" style="width:100%">
        <div class="inline capitalize">${echo $this->group->Nome}</div>
    </div>
    <div class="row pt-2 mr-2">
        <div class="col-sm pt-2 mr-2">
            <div class="text-primary font-weight-bold">${echo L::group_members.":"}</div>
            <t-if test="@{authorized}">
            <div class="row">
                <div class="col d-inline-flex mt-1 mr-2 ml-2 justify-content-end">
                <div class="cust-dropdown" style="width: 100%">
                    <input type="text" id="inputUser" class="form-control" placeholder="${return L::user_add}" 
                        onkeyup="${return 'searchUsers(this,\'groupdropdown\',\''.$this->group->id.'\')'}" 
                        onfocus="showDropDown('groupdropdown',this)"
                        onfocusout="hideDropDown('groupdropdown')">
                    <div id="groupdropdown" class="dropdown-content d-none">
                    </div>
                </div>
                <button id="AddUser" type="button" class="btn btn-primary ml-2">${echo L::user_add}</button>
                </div>
            </div>
            <div class="row mt-2 mr-2 ml-2 user-container">
                <div class="col-sm" id="userscontent">
                <t-each collection="@{users}" item="user">
                    <div class="row material-container-static m-1">
                        <div class="col">
                            <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->user->id]}">${echo $this->user->Nome.' ('.$this->user->NomeUtente.')'}</t-link>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <span class="badge badge-secondary">${echo $this->user->Authority;}</span>
                        </div>
                    </div>
                </t-each>
                </div>
                <t-if test="${Count($this->users)">
                    <div class="text-center alert alert-secondary" role="alert" style="width:100%">
                        ${echo L::project_nousers}
                    </div>
                </t-if>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 material-container">
        <div class="row ml-1">
            <div class="col-sm">
                <div class="text-primary font-weight-bold">
                    ${echo L::project_plural.":"}
                </div>
                <div class="row">
                <div class="mt-2 project-container" style="width:100%">
                    <t-listprojects projects="@{projects}" />
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>