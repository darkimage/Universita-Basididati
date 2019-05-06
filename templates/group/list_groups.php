<h1><!--Title--></h1>
<div class="container-fluid">
<t-form controller="group" method="GET" hidden="${return false;}">
    <div class="row justify-content-end material-container-static">
    <div class="col-sm form-inline justify-content-end">
        <label for="max" class="pr-2 pl-2"><?php echo L::group_max ?>:</label>
            <select class="form-control" id="max" name="max" 
            onchange="${return 'sendForm(\''.$this->formid.'\')'}">
                <option <?php ($this->max == 5) ? print('selected') : ''; ?>>5</option>
                <option <?php ($this->max == 10) ? print('selected') : ''; ?>>10</option>
                <option <?php ($this->max == 50) ? print('selected') : ''; ?>>50</option>
                <option <?php ($this->max == 100) ? print('selected') : ''; ?>>100</option>
            </select>
    </div>
    </div>
</t-form>
</div>
<div class="d-flex justify-content-end">
<t-link controller="group" action="add" class="btn btn-primary" overwrite params="${return []}"><?php echo L::group_add ?></t-link>
</div>
<t-each collection="@{groups}" item="group">
<div class="mt-2 material-container">
    <div class="item inline" style="font-size: large; width:100%;">
        <t-link controller="group" action="show" class="capitalize" overwrite params="${return ['id'=>$this->group->id];}">
            ${echo $this->group->Nome;}
        </t-link>
    </div>
    <div class="container-fluid">
        <div class="row mt-1">
        <div class="col-sm pt-1">
            <div class="row">
                <div class="text-secondary font-weight-bold">${echo L::user_plural.":"}</div>
                <span class="ml-1">${echo $this->group->users;}</span>
            </div>
        </div>
        <div class="col-sm pt-1">
            <div class="row">
                <div class="text-secondary font-weight-bold">${echo L::project_plural.":"}</div>
                <span class="ml-1">${echo $this->group->projects;}</span>
            </div>
        </div>
        </div>
    </div>
</div>
</t-each>
<t-pagination 
    count="@{groupsCount}" 
    list="@{groups}" 
    params="@{params}"
    controller="group"
    action="index"/>