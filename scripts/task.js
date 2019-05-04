function searchUsers(elem,content,id, max){
    console.log($(elem).val());
    if($(elem).val() == '') return;
    showDropDown(content);
    $.post( API+"users/getUsersOfProject", {id: id}, "json")
    .done(function(data) {
        if(data.error){
            console.log(data);
            return;
        }
        $('#'+content).empty();
        regex = new RegExp(encodeURI($(elem).val()),'g');
        for(var i in data) {
            if(i == max) {break;}
            if(data[i].NomeUtente.match(regex)){
                $('#'+content).append('<div onclick="return setAssignee(\''+elem.id+'\',\''+content+'\',\'Assignee\','+data[i].id+',\''+data[i].NomeUtente+' ('+data[i].Nome+')\''+')">'+data[i].NomeUtente+' ('+data[i].Nome+')</div>');
            }
        }
    });
}

function showDropDown(elem,input){
    $('.user-error-add').addClass('d-none');
    $('.user-error-remove').addClass('d-none');
    if($(input).val() != ''){
        $('#'+elem).addClass('d-block');
        $('#'+elem).removeClass('d-none');
    }
}

function hideDropDown(elem){
    window.setTimeout(function() { 
        $('#'+elem).removeClass('d-block');
        $('#'+elem).addClass('d-none');
    }, 200);
}

function setAssignee(input,dropdown,hidden,id,text){
    hideDropDown(dropdown);
    $('#'+input).val(text);
    $.post( API+"users/createAssignee", {id: id,type: 'user'}, "json")
    .done(function(data) {
        if(data.error){
            return;
        }
        $('#'+hidden).val(data.id);
    });
}

function addTaskList(id,taskid,project) {
    $.post( API+"tasks/addTaskList", {id: taskid}, "json")
    .done(function(data) {
        if(data.error){
            console.log(data);
            return;
        }
        $('#'+id).removeClass('d-none');
        $('#'+id+'add').addClass('d-none');
        $('#'+id+'remove').removeClass('d-none');
        $('#'+id+'remove').attr('onclick','');
        $('#'+id+'remove').click(function() {removeTaskList(id,data.id)});
        $('#_'+id).keyup(function() {searchTasks('#_'+id,id+'dropdown',data.id,project,taskid,5)});
        $('#TaskList').val(data.id);
    });
}

function removeTaskList(id,taskid) {
    $.post( API+"tasks/removeTaskList", {id: taskid}, "json")
    .done(function(data) {
        if(data.error != 0){
            console.log(data);
            return;
        }
        $('#'+id).addClass('d-none');
        $('#'+id+'add').removeClass('d-none');
        $('#'+id+'remove').addClass('d-none');
        $('#TaskList').val('');
    });
}


function searchTasks(elem,content,tasklist,project,task,max){
    console.log($(elem).val());
    if($(elem).val() == '') return;
    showDropDown(content);
    $.post( API+"tasks/getTasksOfProject", {id: project}, "json")
    .done(function(data) {
        if(data.error){
            console.log(data);
            return;
        }
        $('#'+content).empty();
        regex = new RegExp(encodeURI($(elem).val()),'g');
        for(var i in data) {
            if(i == max) {break;}
            if(task == data[i].id) {continue;}
            if(data[i].Nome.match(regex)){
                $('#'+content).append(
                    $('<div></div>')
                    .click(function(){
                        addTaskToList(tasklist,data[i]);
                    })
                    .text(data[i].Nome)
                );
            }
        }
    });
}

function addTaskToList(tasklist,task) {
    $.post( API+"tasks/addTaskToList", {id: tasklist,task: task.id}, "json")
    .done(function(data) {
        if(data.err){
            console.log(data);
            return;
        }
        $("#taskslist").prepend(
            $('<li id="tasks'+task.id+'" class="list-group-item"></li>').append(
            $('<div class="container-fluid"></div>').append(
                $('<div class="row"></div>')
                .append('<div class="col">'+task.Nome+'</div>')
                .append(
                    $('<div class="col justify-content-end d-flex"></div>')
                    .append(
                        $('<button type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>')
                        .click(function(){
                            removeTaskFromList(tasklist,task,'tasks'+task.id)
                        })
                    )
                )
            ))
        );
    });
}

function removeTaskFromList(tasklist,task,elem){
    $.post( API+"tasks/removeTaskFromList", {id: tasklist,task: task.id}, "json")
    .done(function(data) {
        if(data.error){
            console.log();
            return;
        }
        $('#'+elem).remove();
    });
}