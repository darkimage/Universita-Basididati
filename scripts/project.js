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
            console.log(element);
        }else{
            $(element).removeClass('d-none') ;
        }
    });
    console.log($(text).val());
}


/**
 * 
 */
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

function addGroup(elem,dropdown){
    console.log(elem);
    $.post( "http://localhost:8014/API/v1/testAPI/testpost", {prova: 'test'},"json").done(function(data) {
        console.log(JSON.parse(data).action);
      });
    hideDropDown(dropdown);
    return true;
}

function searchGroups(elem,content){
    console.log($(elem).val());
    showDropDown(content);
    $('#'+content).append('<div onclick="return addGroup(this,\'groupdropdown\')">PROVA</div>');
}
