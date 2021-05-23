<?php 
    require_once 'check_auth.php';
    //Verifico che qualcuno sia loggato
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    //Verifico che siano stati passati i parametri necessari alla query
    if(!isset($_GET['EAN']) && !isset($_GET['EAN'])) {
        echo "Errore";
        exit;
    }
    //Eseguo l'escape dei parametri per evitare l'SQL injection
    $ean = mysqli_real_escape_string($conn, $_GET['EAN']);
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    //Preparo la query
    //Nel caso in cui l'utente abbia già inserito nel carrello tale prodotto, ne modifico la quantità
    $query = "INSERT INTO CARRELLI (IdCliente, EANProdotto, Quantità) 
              VALUE ('$id', '$ean', '$q') 
              ON DUPLICATE KEY
              UPDATE Quantità=VALUES(Quantità)";
    //Eseguo la query e stampo il messaggio di errore che mi viene ritornato in caso di errore
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    //Stampo il risultato della query (true o false)  Il false non viene stampato perchè il programma si bloccherebbe al die
    echo(json_encode($res));
    mysqli_close($conn);
?>