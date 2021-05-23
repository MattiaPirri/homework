<?php 
    require_once 'check_auth.php';
    require_once 'completa_prodotto.php';
    
    if(!isset($_GET['EAN'])) exit;

    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    if(!isset($_GET['EAN'])) exit;
    $ean = mysqli_real_escape_string($conn, $_GET['EAN']);
    //Seleziono il prodotto data la sua chiave come parametro, il join con categoria mi serve per la funzione complata_prodotto
    if (!$id = checkAuth()) 
        $query =   "SELECT P.*, C.Nome as categoria
                    FROM PRODOTTI AS P JOIN CATEGORIE AS C ON P.CodiceCategoria=C.Codice
                    WHERE P.EAN=$ean";
    else 
    //se c'è un utente loggato seleziono un ulteriore campo isPreferito che assume il valore true nal caso in cui il prodotto si trova nella lista dei preferiti del cliente
        $query =   "SELECT P.*, C.Nome as categoria, IF(PRE.EANProdotto IS NULL, FALSE, TRUE) as isPreferito
                    FROM PRODOTTI AS P JOIN CATEGORIE AS C ON P.CodiceCategoria=C.Codice
                    LEFT JOIN (SELECT EANProdotto 
                                FROM PREFERITI 
                                WHERE IdCliente=$id) AS PRE
                            ON PRE.EANProdotto=P.EAN
                    WHERE P.EAN=$ean";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo json_encode(completa_prodotto(mysqli_fetch_assoc($res)));
    mysqli_free_result($res);
    mysqli_close($conn);
?>