<?php class L {
const login_title = 'Accedi';
const login_username = 'Nome utente';
const login_password = 'Password';
const login_submit = 'Entra';
const login_remember = 'Ricordami';
const index_title = 'Task Manager';
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
function L($string, $args=NULL) {
    $return = constant("L::".$string);
    return $args ? vsprintf($return,$args) : $return;
}