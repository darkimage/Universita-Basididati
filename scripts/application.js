/**
 * GLOBAL VARIABLES
 */
var URL = 'http://localhost:8014/';
var API = URL+'/API/v1/';

/**
 *  prova a mostrare le tab in base alle anchor specificate
 *  nell url se sono presenti
 */
let url = document.location.href;
if(url.match(/#.*/)){
    const anchor = window.location.hash.split("#");
    console.log(anchor);
    anchor.forEach(element => {
        $(`a[href="${"#"+element}"]`).tab('show');    
    });
}

/**
 *  aggiunge l'anchor all url con la posssibilita di
 *  specificare il livello se non esiste lo aggiunge 
 */
function navigateAnchor(element,level=1){
    let url = document.location.href;
    if(url.match(/#.*/)){
        var replace = "(#.[^#]+){"+level+"}";
        var re = new RegExp(replace,"");
        if(url.match(re)){
            url = url.replace(re,$(element).attr("href"));
        }else{
            url = url + $(element).attr("href");
        }
    }else{
        url = url + $(element).attr("href");
    }
    window.history.pushState(null,null,url);
}

/**
 * Fix per i paragrafi che contengono linebreaks
 * class="multiline"
 */
$('.multiline').each((index,element)=>{
    text = $(element).text().replace(/$/gm,'<br>');
    $(element).html(text);
}); 