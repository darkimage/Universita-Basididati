/**
 * GLOBAL VARIABLES
 */
var URL = 'http://localhost:8014/';
var API = URL+'API/v1/';

/**
 * Fix per i paragrafi che contengono linebreaks
 * class="multiline"
 */
$('.multiline').each((index,element)=>{
    text = $(element).text().replace(/$/gm,'<br>');
    $(element).html(text);
}); 



/**
 * Usata per inviare i forms 
 * (per esempio con il callback onchange degli inpur select e altri)
 */
function sendForm(formid){
    $('#'+formid).submit();
}