<?php 
    require_once 'dbconfig.php';
    require_once 'completa_prodotto.php';
    require_once 'check_auth.php';
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    //Seleziono 9 prodotti random
    if (!$id = checkAuth()) 
        $query = "SELECT EAN, Marca, P.Nome, Valutazione, Prezzo, P.Descrizione, P.Valutazioni, Disponibili, C.Nome as categoria
                FROM 
                        PRODOTTI AS P JOIN CATEGORIE AS C
                            ON C.Codice = P.CodiceCategoria 
                ORDER BY rand() 
                LIMIT 9;";
    //La query sottostante si differenzia per il campo isPreferito che vale true se è presente nei preferiti dell'utente loggato
    else $query =   "SELECT EAN, Marca, P.Nome, Valutazione, Prezzo, P.Descrizione, P.Valutazioni, Disponibili, C.Nome as categoria, 
                        IF(EANProdotto IS NULL, FALSE, TRUE) as isPreferito
                    FROM PRODOTTI AS P JOIN CATEGORIE AS C
                    ON C.Codice = P.CodiceCategoria 
                    LEFT JOIN (SELECT EANProdotto 
                                FROM PREFERITI 
                                WHERE IdCliente=$id) AS PRE
                            ON PRE.EANProdotto=EAN
                    ORDER BY rand() 
                    LIMIT 9;";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]= completa_prodotto($row);
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>