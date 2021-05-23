<?php
    require_once 'dbconfig.php'; //così non devo includerlo in ogni file che usa questa funzione
    session_start();
    function checkAuth() {
        if(isset($_COOKIE["_id_cliente"])){
            //Nel coockie ho 4 campi: id, nome, scadenza e hash
            $cookie = json_decode($_COOKIE['_id_cliente'], true);
            //Se l'hash della concatenazione di id e scadenza coincide con l'hash memorizzato nel cookie (Quindi presumibilmenete non è stato manomesso o costruito da terzi)
            if(md5($cookie['id'].$cookie['scadenza'])==$cookie['hash']){
                //copio nella sessione id e nome dal cookie
                $_SESSION["_id_cliente"] = $cookie['id'];
                $_SESSION["_nome_cliente"] = $cookie['nome'];
                //ritorno l'id del cliente
                return $_SESSION["_id_cliente"];
            }
        }
        //Nel caso in cui non esiste il cookie verifico se esiste la sessione e la ritorno, altrimenti ritorno 0
        return isset($_SESSION['_id_cliente']) ? $_SESSION['_id_cliente'] : 0;
    }

    function checkAuthRiservata() {
        //Gestitsco questo login solo con la sessione per motivi di sicurezza, se esiste la sessione e la ritorno, altrimenti ritorno 0
        return isset($_SESSION['_id_impiegato']) ? $_SESSION['_id_impiegato'] : 0;
    }
?>