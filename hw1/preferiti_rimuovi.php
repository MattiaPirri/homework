<?php 
    require_once 'check_auth.php';
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    if(!isset($_GET['EAN'])) exit;
    $ean = mysqli_real_escape_string($conn, $_GET['EAN']);
    $query = "DELETE FROM PREFERITI WHERE IdCliente='$id' AND EANProdotto='$ean'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo(json_encode($res));
    mysqli_close($conn);
?>