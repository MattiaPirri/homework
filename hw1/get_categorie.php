<?php 
    require_once 'dbconfig.php';

    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));

    //Seleziono tutte le categorie, il join con prodotti serve a contare il numero di articoli
    $query = "SELECT Codice, CATEGORIE.Nome, count(*) AS n_articoli, CATEGORIE.Descrizione
                      FROM CATEGORIE JOIN PRODOTTI 
                            ON PRODOTTI.CodiceCategoria=CATEGORIE.Codice 
                      GROUP BY Codice";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]=$row;
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>