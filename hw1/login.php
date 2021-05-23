<!DOCTYPE html>
<?php 
    include 'check_auth.php';
    //se il cliente è già loggato lo reindirizzo alla home page
    if(checkAuth()){
        header('Location: index.php');
        exit;
    }
    //se entrambi i campi sono stati popolati
    if(!empty($_POST["email"]) && !empty($_POST['password'])){
        $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
        //faccio l'escape dei parametri forniti dall'utente per evitare SQL injection
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        //seleziono il cliente corrispondente alla date email (chiave di cliente)
        $query = "SELECT id, nome, password FROM CLIENTI WHERE email = '$email'";
        $res = mysqli_query($conn, $query) or die (mysqli_error($conn));
        //se esiste un utente con tale email
        if(mysqli_num_rows($res)>0){
            $ris = mysqli_fetch_assoc($res);
            //se le password corrispondono
            if(password_verify($_POST['password'], $ris['password'])){
                //se l'utente decide di rimanere collegato
                if(isset($_POST['keep'])){
                    $scadenza = time()+60*60*24*7;//Una settimana
                    //imposto un cookie con all'interno un array contenente: id, nome, scadenza del cookie stesso e un hash che è l'hash risultante dalla concatenazione dell'id del cliente con la data di scadenza del cookie, nella funzione checkAuth se è settato il cookie in questione provo a ricostruire quest'hash e nel caso in cui corrisponde con quello memorizzato nel cookie stesso è molto probabile che il cookie sia utentico e non manipolato o costruito ad hoc. Per aggirare questo sistema il malintenzionato dovrebbe conoscere il procedimento per generare l'hash 
                    $cookie = (object) array( "id" => $ris['id'],"nome"=> $ris['nome'], "scadenza" => $scadenza, "hash" => md5($ris['id'].$scadenza));
                    setcookie('_id_cliente', json_encode( $cookie), $scadenza); 
                }
                //setto le variabili di sessione
                $_SESSION["_id_cliente"] = $ris['id'];
                $_SESSION["_nome_cliente"] = $ris['nome'];
                //ho implementato un meccanismo di redirect che permette di tornare alla pagina dalla quale arriva l'utente
                if(isset($_GET['redirect'])){
                    $redirect = $_GET['redirect'];
                    header("Location: $redirect");
                }
                //se il parametro redirect non è presente lo reindirizzo alla home page
                else header("Location: index.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        //se non esiste un utente con l'email fornita o la password non coincide con quella memorizzata nel db mostro un errore
        $message = "Email e/o password errati.";
        //se uno o tutti e due i campi sono vuoti, controllo che almeno uno dei due sia settato così da non mostrare l'errore al primo arrivo sulla pagina
    }else if (isset($_POST["email"]) || isset($_POST["password"])) {
        $message = "Inserisci username e password!";
    }
?>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pirri Shop - Accedi</title>
    <link rel="stylesheet" href="style/login-signup.css">
</head>

<body>
    <div class="container">
        <div class="title">
            <a href="index.php">
                <div class="logo">
                    PIRRI
                    <div>shop</div>
                </div>
            </a>

        </div>
        <h1>Accedi</h1>
        <form action="" method="POST">
            <input type="text" placeholder="Email" name="email" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>> <!--In caso di errore reimpio nuovamente i campi-->
            <input type="password" placeholder="Password" name="password" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
            <div class="bottom">
                <?php //stampo i vari messaggi di errore
                if(isset($message))
                    echo "<span class=\"error\">$message</span>"
                ?>

                <label><input type="checkbox" name="keep">Rimani connesso</label>
            </div>
            
            <input type="submit" value="Accedi" id="submit">
        </form>
        <span id="login">Non hai un account? <a href="signup.php">Registrati</a></span>
    </div>
</body>

</html>