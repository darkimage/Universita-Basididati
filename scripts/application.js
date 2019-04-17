//PROVA A MOSTRARE LE TAB IN BASE ALLE ANCHOR SPECIFICATE 
//NELL URL SE SONO PRESENTI

if(url.match(/#.*/)){
    const anchor = window.location.hash.split("#");
    console.log(anchor);
    anchor.forEach(element => {
        $(`a[href="${"#"+element}"]`).tab('show');    
    });
}

//AGGIUNGE L'ANCHOR ALL URL CON LA POSSSIBILITA DI
// SPECIFICARE IL LIVELLO
function navigateAnchor(element,level=1){
    let anchors = [];
    let url = document.location.href;
    if(url.match(/#.*/)){
        var replace = "(#.[^#]+){"+level+"}";
        var re = new RegExp(replace,"");
        url = url.replace(re,$(element).attr("href"));
    }else{
        url = url + $(element).attr("href");
    }
    window.history.pushState(null,null,url);
}