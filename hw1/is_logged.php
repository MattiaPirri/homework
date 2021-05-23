<?php 
    require_once 'check_auth.php';
    header('Content-type: application/json');
    //Ritorna 0 se non loggato (sia cookie che sessione), l'id del cliente se loggato
    echo(json_encode(checkAuth()));
?>