<?php 
    require_once 'check_auth.php';

    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    /*PRODCEURA PER CREARE UN ORDINE A PARTIRE DA UN CARRELLO, L'HANDLER CATTURA I MESSAGGI DI CARRELLO VUOTO E QUELLO LANCIATO DALLA BUSINESS RULE  (vedi file: .sql riga 835) //FIXME*/
    $query =   "CALL carrelloToOrdine($id);";
    $res = mysqli_query($conn, $query);
    //ritorno true se tutto va a buon fine, il segnale di errore altrimenti
    echo json_encode($res ? true : mysqli_error($conn) );
    mysqli_close($conn);
?>