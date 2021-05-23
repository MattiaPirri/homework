<?php
    session_start();
    //Cancello la sessione
    session_destroy();
    //Cancello il cookie
    setcookie("_id_cliente", "");
    header('Location: index.php');
?>