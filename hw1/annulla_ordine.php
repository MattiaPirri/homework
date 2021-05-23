<?php 
    require_once 'check_auth.php';
    //Verifico che qualcuno sia loggato
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
     //Verifico che siano stati passati i parametri necessari alla query
    if(!isset($_GET['id'])) {
        echo "Errore";
        exit;
    }
    //Eseguo l'escape dei parametri per evitare l'SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    //Preparo la query
    $query = "UPDATE ORDINI SET Stato='Annullato' WHERE id=$id";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo(json_encode($res));
    mysqli_close($conn);
?>