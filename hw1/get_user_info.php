<?php 
    require_once 'check_auth.php';
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    //Ottiene il numero di preferiti e il numero di elementi presenti nel carrello (Attributi ridondanti aggiunti perchè richiesti in ogni pagina del sito) di un dato cliente
    $query = "SELECT NPreferiti, NCarrello FROM CLIENTI WHERE Id='$id'";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    if(mysqli_num_rows($res))
        echo json_encode(mysqli_fetch_assoc($res));
    mysqli_free_result($res);
    mysqli_close($conn);
?>