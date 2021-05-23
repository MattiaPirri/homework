<?php 
    /**********************************************************
     * ESEGUE LE OPERAZIONI PREVISTE NEL PROGETTO DI DATABASE *
     **********************************************************/
    require_once 'check_auth.php';
    //Verifico che qualcuno sia loggato
    if (!$id = checkAuthRiservata()) exit;
    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
     //Verifico che siano stati passati i parametri necessari alla query
    if(!isset($_GET['op'])) {
        echo "Errore";
        exit;
    }
    if($_GET['op']=='1') {
        $query = "CALL P1(@Ris)";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if ($res) {
            $query = "SELECT @Ris as perdita";
            $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
            echo(json_encode(mysqli_fetch_assoc($res)));
            mysqli_free_result($res);
        }
    }

    if($_GET['op']=='2') {
        if(!isset($_GET['s'])) exit;
        $s = mysqli_real_escape_string($conn, $_GET['s']);
        $query = "CALL P2(\"$s\")";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        
        $output = array();
        while($row = mysqli_fetch_assoc($res))
            $output[]=$row;
        echo json_encode($output);
        mysqli_free_result($res);
    }

    if($_GET['op']=='3') {
        if(!isset($_GET['cf']) || !isset($_GET['n']) || !isset($_GET['c']) || !isset($_GET['d']) || !isset($_GET['i']) || !isset($_GET['t']) || !isset($_GET['s'])) exit;
        $cf = mysqli_real_escape_string($conn, $_GET['cf']);
        $n = mysqli_real_escape_string($conn, $_GET['n']);
        $c = mysqli_real_escape_string($conn, $_GET['c']);
        $d = mysqli_real_escape_string($conn, $_GET['d']);
        $i = mysqli_real_escape_string($conn, $_GET['i']);
        $t = mysqli_real_escape_string($conn, $_GET['t']);
        $s = mysqli_real_escape_string($conn, $_GET['s']);
        //CALL P3("PRRMTT89S02C351G","Mattia", "Pirri","1991-11-02", "Via Indirizzo numero", "3825403794", 8000);
        $query = "CALL P3(\"$cf\",\"$n\",\"$c\",\"$d\",\"$i\",\"$t\",$s)";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        echo json_encode($res);
    }

    if($_GET['op']=='4') {
        $query = "CALL P4";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $output = array();
        while($row = mysqli_fetch_assoc($res))
            $output[]=$row;
        echo json_encode($output);
        mysqli_free_result($res);
    }
    
    mysqli_close($conn);
?>