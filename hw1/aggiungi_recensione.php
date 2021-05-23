<?php 
    require_once 'check_auth.php';
    //Verifico che qualcuno sia loggato
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
     //Verifico che siano stati passati i parametri necessari alla query
    if(!isset($_GET['EAN']) || !isset($_GET['t']) || !isset($_GET['v']) || !isset($_GET['r']))exit;
    //Eseguo l'escape dei parametri per evitare l'SQL injection
    $ean = mysqli_real_escape_string($conn, $_GET['EAN']);
    $titolo = mysqli_real_escape_string($conn, $_GET['t']);
    $valutazione = mysqli_real_escape_string($conn, $_GET['v']);
    $recensione = mysqli_real_escape_string($conn, $_GET['r']);
    //Inserisco la recensione, se già presente (chiave EANProdotto e IdCliente) la aggiorno
    $query = "  INSERT INTO RECENSIONI (IdCliente, Stelle, Titolo, Descrizione, EANProdotto, CodiceRecensione) 
                SELECT  '$id', '$valutazione', '$titolo', '$recensione', '$ean', max(CodiceRecensione)+1 
                FROM RECENSIONI WHERE EANProdotto = '$ean'
                ON DUPLICATE KEY UPDATE Stelle=VALUES(Stelle),Titolo=VALUES(Titolo), Descrizione=VALUES(Descrizione)";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo(json_encode($res));
    mysqli_close($conn);
?>