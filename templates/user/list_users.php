<h1><!--Title--></h1>
<div class="container-fluid">
    <t-form controller="user" method="GET" hidden="${return false;}">
    <div class="row justify-content-end material-container-static">
    <div class="col-sm form-inline justify-content-end">
        <label for="columns" class="pr-2"><?php echo L::user_columns ?>:</label>
            <select class="form-control" id="columns" name="columns"
            onchange="${return 'sendForm(\''.$this->formid.'\')'}">
                <option <?php ($this->columns == 1) ? print('selected') : ''; ?>>1</option>
                <option <?php ($this->columns == 2) ? print('selected') : ''; ?>>2</option>
                <option <?php ($this->columns == 4) ? print('selected') : ''; ?>>4</option>
                <option <?php ($this->columns == 8) ? print('selected') : ''; ?>>8</option>
                <option <?php ($this->columns == 12) ? print('selected') : ''; ?>>12</option>
            </select>

        <label for="max" class="pr-2 pl-2"><?php echo L::user_max ?>:</label>
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
    <t-listUsers users="@{users}" colsize="@{columns}"/>
    <t-pagination 
        count="@{usersCount}" 
        list="@{users}" 
        params="@{params}"
        controller="user"
        action="index"/>
</div>
