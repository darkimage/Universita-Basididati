<div class="col-sm mr-2 ">
    <div class="text-primary font-weight-bold">
        ${echo L::task_plural.":"}
    </div>
    <t-if test="@{tasks}">
    <div class="col-sm user-container p-2 mt-2 ml-3">
    <t-each collection="@{tasks}" item="task">
    <div class="row pt-2 mr-2 ml-2">
        <div class="col-sm pt-2 mr-2 material-container-static">
            <div class="row item ml-1 inline" style="width:100%">
                <t-link controller="task" action="show" overwrite params="${return ['id'=>$this->task->id]}">${echo $this->task->Nome}</t-link>
            </div>
            <div class="row ml-1 mt-2">
            <span class="badge badge-pill badge-primary ml-2">
                ${echo L::project_enddate.": ".date("d/m/Y",strtotime($this->task->DataScadenza));}
            </span>
            <t-if test="@{task->Completata}">
                <span class="badge badge-pill badge-success ml-2">
                    ${echo L::task_completed.": ".date("d/m/Y",strtotime($this->task->DataCompletamento));}
                </span>
            </t-if>
            </div>
        </div>
    </div>
    </t-each>
    </div>
    </t-if>
    <t-if test="${!$this->tasks}">
        <div class="text-center alert alert-secondary" role="alert" style="width:100%">
            <?php echo L::task_notasks ?>
        </div>  
    </t-if>
</div>