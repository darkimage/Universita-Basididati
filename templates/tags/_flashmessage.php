<?php
if(!isset($_SESSION)) 
    session_start();
if(isset($_SESSION['flash'])){
    echo '<div class="alert alert-danger">';
    echo $_SESSION['flash'];
    unset($_SESSION['flash']);
    echo '</div>';
}
?>