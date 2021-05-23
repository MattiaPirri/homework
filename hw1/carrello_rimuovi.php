<?php 
    require_once 'check_auth.php';
    //Verifico che qualcuno sia loggato
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
     //Verifico che siano stati passati i parametri necessari alla query
    if(!isset($_GET['EAN'])) {
        echo "Errore";
        exit;
    }
    //Eseguo l'escape dei parametri per evitare l'SQL injection
    $ean = mysqli_real_escape_string($conn, $_GET['EAN']);
    //Preparo la query
    $query = "DELETE FROM CARRELLI WHERE IdCliente='$id' AND EANProdotto='$ean'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo(json_encode($res));
    mysqli_close($conn);
?>