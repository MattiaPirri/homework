<?php 
    require_once 'dbconfig.php';

    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));

    if(!isset($_GET['EAN'])) exit;
    $ean = mysqli_real_escape_string($conn,$_GET['EAN']);
    /*
    $query =   "SELECT *
                FROM RECENSIONI
                WHERE EANProdotto=$ean";

    */

    $query = "  SELECT R.Stelle, R.Titolo, R.Descrizione, CONCAT(C.Nome, \" \", SUBSTRING(C.Cognome, 1, 1), \".\") AS NomeCliente, IF (VERIFICATI.Id IS NULL, FALSE, TRUE) AS isVerificato
                FROM RECENSIONI R LEFT JOIN(
                SELECT RE.Id
                FROM RECENSIONI AS RE
                WHERE IdCliente IN (SELECT IdCliente 
                                    FROM  COMPOSIZIONI JOIN ORDINI ON Id=IdOrdine
                                    WHERE EANProdotto = RE.EANProdotto)) AS VERIFICATI ON R.Id=VERIFICATI.Id
                JOIN CLIENTI AS C ON C.Id=IdCliente
                WHERE EANProdotto =$ean";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]=$row;
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>