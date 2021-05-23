<?php 
    require_once 'dbconfig.php';
    require_once 'completa_prodotto.php';
    require_once 'check_auth.php';
    //Imposto header risposta per il json
    header('Content.type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    
    //Se nessuno è loggato ritorno i 9 prodotti più venduti
    if (!$id = checkAuth()) 
    $query =   "SELECT P.*, sum(Quantità) as QuantitàTotale, CA.Nome AS categoria
                FROM PRODOTTI P JOIN COMPOSIZIONI C ON C.EANProdotto=P.EAN JOIN CATEGORIE AS CA ON CodiceCategoria=CA.Codice
                GROUP BY EAN 
                ORDER BY QuantitàTotale DESC
                LIMIT 9;";
    //Altrimenti ritorno la stessa cosa, ma con un ulteriore attributo che mi serve a mostrare il tasto per aggiungere o rimuovere dai preferiti
    else $query =  "SELECT P.*, sum(Quantità) as QuantitàTotale, CA.Nome AS categoria, IF(PRE.EANProdotto IS NULL, FALSE, TRUE) as isPreferito
                    FROM PRODOTTI P JOIN COMPOSIZIONI C ON C.EANProdotto=P.EAN JOIN CATEGORIE AS CA ON CodiceCategoria=CA.Codice
                    LEFT JOIN (SELECT EANProdotto 
                                FROM PREFERITI 
                                WHERE IdCliente=$id) AS PRE
                            ON PRE.EANProdotto=EAN
                    GROUP BY EAN 
                    ORDER BY QuantitàTotale DESC
                    LIMIT 9;";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]=completa_prodotto($row);
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>
