function searchUsers(elem,content,groupid){
    text = $(elem).val();
    showDropDown(content);    
    $.get( API+"users/getUsers", {search: text})
    .done(function(users) {
        if(users.error){
            console.log(users);
            return;
        }
        $('#'+content).empty();
        for (let i = 0; i < users.length; i++) {
            usertxt = users[i].Nome+' ('+users[i].NomeUtente+')';
            $('#'+content).append('<div onclick="setUser(\''+elem.id+'\','+groupid+','+users[i].id+',\''+usertxt+'\')">'+usertxt+'</div>');
        }
    });
}

function showDropDown(elem,input){
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

function setUser(elem,groupid,userid,text) {
    $('#'+elem).val(text)
    $('#AddUser').click(function(){addUser(groupid,userid)});
}

function addUser(groupid,userid) {
    $.post( API+"groups/addUserToGroup", {id: groupid,user:userid})
    .done(function(grouprole){
        if(grouprole.error){
            console.log(grouprole);
            return;
        }
        console.log(grouprole);
        $("#userscontent").append(
            `<div class="row material-container-static m-1">
            <div class="col">
                <a href="`+URL+`/user/show?id=`+grouprole.Userid.id+`">`+grouprole.Userid.Nome+' ('+grouprole.Userid.NomeUtente+')'+`</a>
            </div>
            <div class="col d-flex justify-content-end">
                <span class="badge badge-secondary">`+grouprole.Roleid.Authority+`</span>
            </div>
        </div>`
        );
    });
}