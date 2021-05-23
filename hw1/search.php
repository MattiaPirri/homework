<?php 
    require_once 'dbconfig.php';
    if(!isset($_GET["q"])){
        echo "Errore";
        exit;
    }
    //Imposto header risposta per il json
    header('Content.type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));

    $q = mysqli_real_escape_string($conn, $_GET["q"]);
    
    $query = "  SELECT * 
                FROM PRODOTTI 
                WHERE concat(Marca, ' ' ,Nome) 
                LIKE '%".$q."%'";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]=$row;
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>
