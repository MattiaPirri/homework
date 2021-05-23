<?php 
    require_once 'check_auth.php';
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    if(!isset($_GET['campo']) || !isset($_GET['valore'])) exit;
    $campo = $_GET['campo'];
    $valore = $_GET['valore'];
    $query =   "UPDATE CLIENTI SET $campo = '$valore'
                WHERE id=$id";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo json_encode($res);
    mysqli_close($conn);
?>