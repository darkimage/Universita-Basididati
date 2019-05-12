/**
 * Permette di filtrare gli utenti dei progetti
 * @param {input type="text" element} text 
 */
function searchuser(text) {
    if($(text).val() == ''){
        $('.user').each(( index, element )=>{
            $(element).removeClass('d-none');
        });
    }
    $('.user').each(( index, element )=>{
        username = element.id.replace(/\(|\)/g,'').split(' ');
        found = false;
        username.forEach(name => {
            regex = new RegExp($(text).val(),'g');
            if(name.match(regex)){found = true;}
        });
        if(!found){
            $(element).addClass('d-none');
        }else{
            $(element).removeClass('d-none') ;
        }
    });
}

function showDropDown(elem,input){
    $('.group-error-add').addClass('d-none');
    $('.group-error-remove').addClass('d-none');
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

function addGroup(elem,dropdown,groupid,projectid){
    console.log(elem);
    hideDropDown(dropdown);
    $.post( API+"projects/addProjectGroup", {project: projectid,group: groupid},"json")
    .done(function(data) {
        if(data.error){
            $('.group-error-add').removeClass('d-none');
            console.log(data);
            return;
        }
        $('.group-container').append(
        `<span class="badge badge-secondary inline">
        <button type="button" class="close" aria-label="Close" 
        onclick="removeGroup(\'#group`+groupid+`\',`+groupid+`,`+projectid+`)">
            <i class="fas fa-window-close fa-lg"></i>
        </button>
        <p>`+$(elem).text()+`</p>
        </span>`);
    });
    return true;
}

function removeGroup(elem,groupid,projectid){
    $.post( API+"projects/removeProjectGroup", {project: projectid,group: groupid},"json")
    .done(function(data) {
        if(data.error){
            $('.group-error-remove').removeClass('d-none');
            console.log(data);
            return;
        }
        $(elem).remove();
    });
}

function searchGroups(elem,content,id){
    console.log($(elem).val());
    if($(elem).val() == '') return;
    showDropDown(content);
    $.post( API+"projects/getGroups")
    .done(function(data) {
        if(data.error){
            console.log(data);
            return;
        }
        $('#'+content).empty();
        regex = new RegExp(encodeURI($(elem).val()),'g');
        for (let i = 0; i < data.length; i++) {
            if(data[i].Nome.match(regex)){
                $('#'+content).append('<div onclick="return addGroup(this,\'groupdropdown\','+data[i].id+','+id+')">'+data[i].Nome+'</div>');
            }
        }
    });
}
