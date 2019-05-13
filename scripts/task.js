function searchUsers(elem,content,id, max){
    console.log($(elem).val());
    if($(elem).val() == '') return;
    showDropDown(content);

    $.post( API+"users/getUsersOfProject", {id: id}, "json")
    .done(function(users) {
        $.post( API+"groups/getGroupsOfProject", {id: id}, "json")
        .done(function(groups) {
            $('#'+content).empty();
            if(groups.error){
                console.log(groups);
                return;
            }
            if(users.error){
                console.log(users);
                return;
            }
            regex = new RegExp(encodeURI($(elem).val()),'g');
            for (let i = 0; i < users.length; i++) {
                if(i == max) {break;}
                if(users[i].NomeUtente.match(regex)){
                    $('#'+content).append('<div onclick="return setAssignee(\''+elem.id+'\',\''+content+'\',\'Assignee\','+users[i].id+',\''+users[i].NomeUtente+' ('+users[i].Nome+')\',\'user\')">'+users[i].NomeUtente+' ('+users[i].Nome+')</div>');
                }
            }
            for (let i = 0; i < groups.length; i++) {
                if(i == max) {break;}
                if(groups[i].Nome.match(regex)){
                    $('#'+content).append('<div onclick="return setAssignee(\''+elem.id+'\',\''+content+'\',\'Assignee\','+groups[i].id+',\''+groups[i].Nome+'\',\'group\')">'+groups[i].Nome+'</div>');
                }
            }
        });
    });
}

function searchUserShare(elem,content,taskid) {
    if($(elem).val() == '') return;
    showDropDown(content);
    $.get( API+"users/getUsers")
    .done(function(users) {
        if(users.error){
            console.log(users);
            return;
        }
        $('#'+content).empty();
        regex = new RegExp(encodeURI($(elem).val()),'g');
        for (let i = 0; i < users.length; i++) {
            if(users[i].NomeUtente.match(regex)){
                $('#'+content).append('<div onclick="return shareWith(\''+elem.id+'\','+users[i].id+','+taskid+',this)">'+users[i].NomeUtente+' ('+users[i].Nome+')</div>');
            }
        }
    });
}

function shareWith(elem,userid,taskid,div){
    $('#'+elem).val($(div).text());
    $('#sharebtn').off("click");
    $('#sharebtn').click(function(){
        shareTask(taskid,userid);
    });
}

function unShareTask(sharetaskid){
    $.post( API+"tasks/unshare",{id: sharetaskid})
    .done(function(res) {
        if(res.error != 0){
            console.log(res);
            return;
        }
        $('#sharebtn').off("click");
        $('#share').removeClass("d-none");
        $('#sharebtnremove').addClass('d-none');
        $('#user-share').remove();
    });
}

function shareTask(taskid,userid){
    $.post( API+"tasks/share",{id: taskid,user: userid}, "json")
    .done(function(share) {
        if(share.error){
            console.log(share);
            return;
        }
        console.log(share);
        $('#share').addClass("d-none");
        $('#sharebtnremove').removeClass('d-none');
        $('#sharebtnremove').off('click');
        $('#sharebtnremove').click(function(){
            unShareTask(share.id);
        });
        $('#user-share-container').append(`
        <div id="user-share" class="row mt-2 ml-1">
        <div class="material-container-static inline" style="width:100%">
            <span class="badge badge-pill badge-secondary ml-2">Gruppo</span>
            <a href="`+URL+'user/show?id='+share.User.id+`">
               `+share.User.Nome+' ('+share.User.NomeUtente+`)
            </a>
        </div>
        </div>`);
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

function setAssignee(input,dropdown,hidden,id,text,type){
    $.post( API+"users/createAssignee", {id: id,type: type}, "json")
    .done(function(data) {
        if(data.error){
            console.log(data);
            return;
        }
        hideDropDown(dropdown);
        $('#'+input).val(text);
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
        for (let i = 0; i < data.length; i++) {
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

function initTaskList() {
    tasklist = $("#TaskList").val();
    project = $("#Project").val();
    task = $("#id").val();
    $.post( API+"tasks/getList", {id: tasklist}, "json")
    .done(function(data) {
        if(data.error){
            console.log(data);
            return;
        }
        $('#_tasks').keyup(function() {searchTasks('#_tasks','tasksdropdown',tasklist,project,task,5)});
        console.log(data);
        for (let i = 0; i < data.length; i++) {
            $("#taskslist").prepend(
                $('<li id="tasks'+data[i].Task.id+'" class="list-group-item"></li>').append(
                $('<div class="container-fluid"></div>').append(
                    $('<div class="row"></div>')
                    .append('<div class="col">'+data[i].Task.Nome+'</div>')
                    .append(
                        $('<div class="col justify-content-end d-flex"></div>')
                        .append(
                            $('<button type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>')
                            .click(function(){
                                removeTaskFromList(tasklist,data[i].Task,'tasks'+data[i].Task.id)
                            })
                        )
                    )
                ))
            );
        }
    });
}
initTaskList();



function removeTaskFromList(tasklist,task,elem){
    $.post( API+"tasks/removeTaskFromList", {id: tasklist,task: task.id}, "json")
    .done(function(data) {
        if(data.error != 0){
            console.log();
            return;
        }
        $('#'+elem).remove();
    });
}