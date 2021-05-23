<?php 
    define ("ITEMS_PER_PAGE", 6);
    require_once 'dbconfig.php';
    require_once 'completa_prodotto.php';
    require_once 'check_auth.php';
    //Se il parametro non è settato esco
    if(!isset($_GET["q"])){
        echo "Errore";
        exit;
    }
    //Imposto header risposta per il json
    header('Content.type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
    //faccio l'escape del parametro per evitare SQL injection
    $q = mysqli_real_escape_string($conn, $_GET["q"]);
    //se non mi viene fornita la pagina desiderata di default seleziono la prima 
    if(!isset($_GET['page'])) 
            $p=0;
        else 
            $p = $_GET['page'];
    //nel caso in cui non mi viene specificato quanti elementi per pagina mostrare uso un valore di default definito nella costante ITEMS_PER_PAGE
    if(!isset($_GET['n'])) 
        $n=ITEMS_PER_PAGE;
    else
        $n = $_GET['n'];
    $output = array();
    //nel caso in cui sto ritornando i risultati della prima pagina ritorno anche il numero di pagine, così raggiunto tale numero il client non effettua più richieste
    if ($p==0){
        $query = "  SELECT COUNT(*) AS pages
                    FROM PRODOTTI 
                    WHERE concat(Marca, ' ' ,Nome) 
                    LIKE '%".$q."%'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));          
        //echo json_encode(mysqli_fetch_assoc($res));
        $output ['pages'] = ceil(mysqli_fetch_assoc($res)['pages']/$n);
    }
    $offset = $p*$n;
    //Seleziono i prodotti che contengono la query di ricerca all'interno della concatenazione tra marca e nome, mostro $n risultati alla volta
    if (!$id = checkAuth()){
        $query = "  SELECT P.* , C.Nome AS categoria
        FROM PRODOTTI AS P JOIN CATEGORIE AS C ON P.CodiceCategoria = C.Codice
        WHERE concat(Marca,' ',P.Nome) 
        LIKE '%".$q."%'
        LIMIT $offset, $n";
    }
    //se loggato, medesima query con campo isPreferito per mostrare il corretto tasto per aggiungere/rimuovere dai preferiti
    else $query = " SELECT P.* , C.Nome AS categoria, IF(EANProdotto IS NULL, FALSE, TRUE) as isPreferito
                    FROM PRODOTTI AS P JOIN CATEGORIE AS C ON P.CodiceCategoria = C.Codice
                    LEFT JOIN (SELECT EANProdotto 
                                FROM PREFERITI 
                                WHERE IdCliente=$id) AS PRE
                            ON PRE.EANProdotto=EAN
                    WHERE concat(Marca,' ',P.Nome) 
                    LIKE '%".$q."%'
                    LIMIT $offset, $n";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $ris = array();
    while($row = mysqli_fetch_assoc($res))
        $ris[]=completa_prodotto($row);
    $output ['results']= $ris;
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>
