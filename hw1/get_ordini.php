<?php 
    require_once 'check_auth.php';
    if (!$id = checkAuth()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));

    $query =   "SELECT *
                FROM ORDINI
                WHERE IdCliente=$id";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $output = array();
    while($row = mysqli_fetch_assoc($res))
        $output[]=$row;
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>