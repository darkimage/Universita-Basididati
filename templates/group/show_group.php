<t-flashmessage />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 material-container-static">
        <div class="row ml-1 inline justify-content-end d-inline-flex" style="width:100%">
        <t-if test="@{authorized}">
            <t-link controller="group" action="edit" overwrite params="${return ['id'=>$this->group->id]}" class="btn btn-warning ml-2">
                ${echo L::common_edit;}
            </t-link>
        </t-if>
        </div>
        <div class="header inline" style="width:100%">
            <div class="inline capitalize">${echo $this->group->Nome}</div>
        </div>
        <div class="row pt-2 mr-2">
            <div class="col-sm pt-2 mr-2">
                <div class="text-primary font-weight-bold">${echo L::group_members.":"}</div>
                <div class="row mt-2 mr-2 ml-2 user-container">
                    <div class="col-sm">
                    <t-each collection="@{users}" item="user">
                        <div class="row material-container-static m-1">
                            <div class="col">
                                <t-link controller="user" action="show" overwrite params="${return ['id'=>$this->user->id]}">${echo $this->user->Nome.' ('.$this->user->NomeUtente.')'}</t-link>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <span class="badge badge-secondary">${echo $this->user->Authority;}</span>
                            </div>
                        </div>
                        </div>
                    </t-each>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>