<!DOCTYPE html>
<?php 
    include 'check_auth.php';
    //nel caso in un un'impiegato è già loggato lo reindirizzo all'area riservata
    if(checkAuthRiservata()){
        header('Location: area_riservata.php');
        exit;
    }
    //se entrambi i campi sono riempiti
    if(!empty($_POST["cf"]) && !empty($_POST['password'])){
        $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
        //faccio l'escape per evitare SQL injection
        $cf = mysqli_real_escape_string($conn, $_POST["cf"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        $query = "SELECT id, nome, password FROM impiegati WHERE cf= '$cf'";
        $res = mysqli_query($conn, $query) or die (mysqli_error($conn));
        //nel caso in cui è presente un impiegato con il codice fiscale fornito come parametro alla pagina
        if(mysqli_num_rows($res)>0){
            $ris = mysqli_fetch_assoc($res);
            //controllo se gli hash delle password corrispondono
            if(md5($_POST['password'])== $ris['password']){
                //setto la sessione, non ho voluto dare la possibilità di utilizzare i cookie 
                $_SESSION["_id_impiegato"] = $ris['id'];
                $_SESSION["_nome_impiegato"] = $ris['nome'];
                //ho previsto un redirect anche se al momento non ne faccio uso
                if(isset($_GET['redirect'])){
                    $redirect = $_GET['redirect'];
                    header("Location: $redirect");
                }
                //reindirizzo all'area riservata
                else header("Location: area_riservata.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        //se non ci sono impiegati con quel codice fiscale o le password non corrispondono
        $message = "CF e/o password errati.";
        //se uno o tutti e due i campi sono vuoti, controllo che almeno uno dei due sia settato così da non mostrare l'errore al primo arrivo sulla pagina
    }else if (isset($_POST["cf"]) || isset($_POST["password"])) {
        $message = "Inserisci CF e password!";
    }
?>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pirri Shop - Accedi area riservata</title>
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
        <h3>all'area riservata</h3>
        <form action="" method="POST">
            <input type="text" placeholder="CF" name="cf" <?php if(isset($_POST["cf"])){echo "value=".$_POST["cf"];} ?>> <!--Nel caso di errori riempio nuovamente i campi-->
            <input type="password" placeholder="Password" name="password" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
            <div class="bottom">
                <?php //stampo il messaggio di errore nel caso in cui non vengono riempiti i campi o nel caso in cui le credenziali non sono valide
                if(isset($message))
                    echo "<span class=\"error\">$message</span>"
                ?>
            </div>
            <input type="submit" value="Accedi" id="submit">
        </form>
    </div>
</body>

</html>