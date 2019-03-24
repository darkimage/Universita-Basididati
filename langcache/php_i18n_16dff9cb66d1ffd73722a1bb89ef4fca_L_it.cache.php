<?php class L {
const login_title = 'Accedi';
const login_username = 'Nome utente';
const login_password = 'Password';
const login_submit = 'Entra';
const login_remember = 'Ricordami';
const login_error = 'Nome utente o Password incorretti.';
const index_title = 'Task Manager';
const error_title = 'Errore';
const error_notauth = 'Non possiedi le autorizzazioni per visualizzare questo contenuto.';
const controlpanel_title = 'Panello di Controllo';
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
function L($string, $args=NULL) {
    $return = constant("L::".$string);
    return $args ? vsprintf($return,$args) : $return;
}