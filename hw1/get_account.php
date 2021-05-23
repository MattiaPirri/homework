<?php 
    require_once 'check_auth.php';
    //Controllo che qualcuno sia loggato
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));

    $query =   "SELECT Nome, Cognome, Indirizzo, Email, Telefono
                FROM CLIENTI
                WHERE Id=$id";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo json_encode(mysqli_fetch_assoc($res));
    mysqli_free_result($res);
    mysqli_close($conn);
?>