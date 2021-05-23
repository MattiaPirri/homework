<?php
    require_once 'check_auth.php';
    //Se il cliente è gia autenticato lo reindirizzo alla home page
    if (checkAuth()) {
        header("Location: index.php");
        exit;
    } 
    //Se tutti i campi richiesti non sono vuoti
    if (!empty($_POST["nome"]) && !empty($_POST["cognome"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && 
        !empty($_POST["conferma"]) && !empty($_POST["indirizzo"]) && !empty($_POST["telefono"])){

            $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']) or die(mysqli_error($conn));
            //inizializzo un array che conterrà i potenziali errori
            $errors = array();
            //valida l'email secondo il formato definito in RFC 822
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                //imposto il messaggio che mostrerò all'utente
                $errors[] = "Email non valida";
            } else {
                //eseguo l'escape per evitare SQL injection
                $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
                //creo la query per verificare che l'email fornita non sia già stata utilizzata, in ogni caso se riesco ad arrivare qui, questo cotrollo è già stato fatto dal js
                $res = mysqli_query($conn, "SELECT email FROM CLIENTI WHERE email = '$email'");
                if (mysqli_num_rows($res) > 0) {
                    $errors[] = "Email già in uso";
                }
            }
            //Verifico il formato della password (Una minuscola, una maiuscola, un numero e un carattere speciale (!@#$%^&*), lunghezza tra 8 e 15 caratteri)
            if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,15}$/', $_POST['password'])) {
                $errors[] = "La password deve essere formata da 8-15 caratteri, deve contenere almeno un carattere minuscolo, uno maiuscolo, un numero e un carattere speciale (!@#$%^&*)";
            } else {
                //eseguo l'escape per evitare SQL injection
                $password = mysqli_real_escape_string($conn, $_POST['password']);
            }
            //verifico se la password e la password per la conferma coincidono
            if (strcmp($_POST["password"], $_POST["conferma"]) != 0) {
                $errors[] = "Le password non coincidono";
            }
            //verifico che il numero di telefono sia composto da 10 e massimo 10 numeri, sarebbe opportuno fare una validazione più accurata //FIXME
            if(!preg_match('/^\d{10}$/', $_POST['telefono'])) {
                $errors[] = "Numero di telefono non valido";
            } else {
                //eseguo l'escape per evitare SQL injection
                $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
            }
            //nel caso in cui la lunghezza del vettore contenente i messaggi di errore = 0, e che quindi non sono sono stati rilevati errori
            if(count($errors) == 0){
                /*Su questi campi non faccio controlli perchè mi interessa solo che siano pieni e
                e se entro in nell'if precendente vuol dire che sono pieni
                */
                $nome = mysqli_real_escape_string($conn, $_POST['nome']);
                $cognome = mysqli_real_escape_string($conn, $_POST['cognome']);
                $indirizzo = mysqli_real_escape_string($conn, $_POST['indirizzo']);
                //creo l'hash della password
                $password = password_hash($password, PASSWORD_BCRYPT);
                $query = "INSERT INTO CLIENTI(nome, cognome, email, password, indirizzo, telefono) VALUES('$nome', '$cognome', '$email', '$password', '$indirizzo', '$telefono')";
                //nel caso in cui l'inserimento va a buon fine
                if (mysqli_query($conn, $query)) {
                    //setto la sessione
                    $_SESSION["_nome_cliente"] = $_POST["nome"];
                    $_SESSION["_id_cliente"] = mysqli_insert_id($conn);
                    mysqli_close($conn);
                    //reindizizzo l'utente alla home page
                    header("Location: index.php");
                    exit;
                } else {
                    //nel caso in cui c'è un errore con la query
                    $errors[] = "Errore di connessione al Database";
                }
            }
            mysqli_close($conn); 
    }else if (isset($_POST["nome"])){  //controllo che uno qualsiasi sia settato così da non mostrare l'errore quando arrivo la prima volta sulla pagina
        $errors[] = "Riempire tutti i campi";
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pirri Shop - Registrazione</title>
    <link rel="stylesheet" href="style/login-signup.css">
    <script src="js/signup.js" defer></script>
</head>
    <div class="container">
        <div class="title">
            <a href="index.php">
                <div class="logo">
                    PIRRI
                    <div>shop</div>
                </div>
            </a>
        </div>
        <h1>Registrazione</h1>
        <form action="" method="POST" name="signup">
            <?php
                //Se ci sono errori li stampo
                if(isset($errors))
                    foreach($errors as $error )
                        echo "<span class=\"error\">$error</span>";
            ?>
            <input type="text" placeholder="Nome" name="nome" <?php if(isset($_POST["nome"])){echo "value=".$_POST["nome"];} ?>><!--Riempio i campi con i dati forniti in caso di errore-->
            <span class="error hidden" id="nome">Inserisci il nome</span> <!-- i campi di classe error vengono mostrati durante la validazione js-->
            <input type="text"placeholder="Cognome" name="cognome" <?php if(isset($_POST["cognome"])){echo "value=".$_POST["cognome"];} ?>>
            <span class="error hidden" id="cognome">Inserisci il cognome</span>
            <input type="text" placeholder="Email" name="email" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>>
            <span class="error hidden" id="email-1">Email non valida</span>
            <span class="error hidden" id="email-2">Email già in uso</span>
            <input type="password" placeholder="Password" name="password" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
            <span class="error hidden" id="password">La password deve essere formata da 8-15 caratteri, deve contenere almeno un carattere minuscolo, uno maiuscolo, un numero e un carattere speciale (!@#$%^&*)</span>
            <input type="password" placeholder="Conferma password" name="conferma" <?php if(isset($_POST["conferma"])){echo "value=".$_POST["conferma"];} ?>>
            <span class="error hidden" id="conferma">Le password non coincidono</span>
            <input type="text" placeholder="Indirizzo" name="indirizzo" <?php if(isset($_POST["indirizzo"])){echo "value=".$_POST["indirizzo"];} ?>>
            <span class="error hidden" id="indirizzo">Inserisci l'indirizzo</span>
            <input type="text" placeholder="Telefono" name="telefono" <?php if(isset($_POST["telefono"])){echo "value=".$_POST["telefono"];} ?>>
            <span class="error hidden" id="telefono">Numero di telefono non valido</span>
            <input type="submit" value="Registrati" id="submit">
        </form>
        <span id="login">Hai un account? <a href="login.php">Accedi</a></span>
    </div>
</body>

</html>