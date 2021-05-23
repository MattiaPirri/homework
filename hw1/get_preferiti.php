<?php 
    require_once 'check_auth.php';
    require_once 'completa_prodotto.php';
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    //Seleziono i preferiti di un dato utente, il join con categorie mi serve per la funzione completa prodotto, che fa le richieste API per ottenere le immagini di libri e musica
    $query =   "SELECT PRO.*, C.Nome as categoria, TRUE AS isPreferito
                FROM PREFERITI AS PRE JOIN PRODOTTI AS PRO 
                    ON PRE.EANProdotto=PRO.EAN 
                     JOIN CATEGORIE AS C
                    ON PRO.CodiceCategoria=C.Codice
                WHERE IdCliente=$id;";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]=completa_prodotto($row);
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>