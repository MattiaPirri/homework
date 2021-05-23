<?php
    define ("ITEMS_PER_PAGE", 1); //TODO ho messo 1 solo per provare a vedere se funzionasse l'infinity scroll
    require_once 'dbconfig.php';
    require_once 'completa_prodotto.php';
    require_once 'check_auth.php';
    if(!isset($_GET['c'])) {
        echo "Errore";
        exit;
    }

    header('Content-type: application/json');
    //Mi connetto al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));

    $c = mysqli_real_escape_string($conn, $_GET['c']);
    
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
        $query =   "SELECT COUNT(*) AS pages
                    FROM CATEGORIE C JOIN PRODOTTI AS P ON C.Codice=P.CodiceCategoria
                    WHERE C.Codice='$c';";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));          
        $output ['pages'] = ceil(mysqli_fetch_assoc($res)['pages']/$n);
    }
    $offset = $p*$n;
    //seleziono tutti i prodotto della data categoria
    if (!$id = checkAuth()) 
        $query =   "SELECT C.nome as categoria,C.Descrizione AS descrizioneCategoria, P.Nome, P.*
                    FROM CATEGORIE C JOIN PRODOTTI AS P ON C.Codice=P.CodiceCategoria
                    WHERE C.Codice='$c'
                    LIMIT $offset, $n;";
    else{
        $query =   "SELECT C.nome as categoria,C.Descrizione AS descrizioneCategoria, P.Nome, P.*, IF(EANProdotto IS NULL, FALSE, TRUE) as isPreferito
                    FROM CATEGORIE C JOIN PRODOTTI AS P ON C.Codice=P.CodiceCategoria LEFT JOIN (
                        SELECT EANProdotto
                        FROM PREFERITI
                        WHERE IdCliente=$id
                    ) AS PRE ON PRE.EANProdotto=EAN
                    WHERE C.Codice='$c'
                    LIMIT $offset, $n;";
    }

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $ris = array();
    while($row = mysqli_fetch_assoc($res))
        $ris[]=completa_prodotto($row);
    $output ['results']= $ris;
    echo json_encode($output);
    mysqli_free_result($res);
    mysqli_close($conn);
?>