<?php 
    require_once 'dbconfig.php';

    if(!isset($_GET["e"])){
        echo "Errore";
        exit;
    }
    //Imposto header risposta per il json
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));

    $email = mysqli_real_escape_string($conn, $_GET["e"]);
    
    $query = "SELECT email FROM CLIENTI WHERE email='$email'";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    
    echo json_encode(array('exists' => mysqli_num_rows($res) > 0 ? true : false));
    mysqli_free_result($res);
    mysqli_close($conn);
?>