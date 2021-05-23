<?php 
    require_once 'check_auth.php';
    require_once 'completa_prodotto.php';
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    //Seleziono i prodotti che sono presenti nel carrello dell'utente
    $query =   "SELECT EAN, Marca, P.Nome, CA.Nome as categoria, C.quantità, Disponibili, Prezzo, Peso
                FROM CARRELLI AS C JOIN PRODOTTI AS P ON C.EANProdotto=P.EAN 
                    JOIN CATEGORIE AS CA
                    ON P.CodiceCategoria=CA.Codice
                WHERE IdCliente=$id;";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]=completa_prodotto($row);
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>