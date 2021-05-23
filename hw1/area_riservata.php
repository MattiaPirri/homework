<?php 
    require_once 'check_auth.php';
    if (!$userid = checkAuthRiservata()) {
        header("Location: login_area_riservata.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pirri Shop - Area riservata</title>
    <script src="js/area-riservata.js" defer></script>
</head>
<body>
    <!-- Questa pagina è appositamente con una grafica semplice per rendere le operazioni degli operatori più veloci e al contempo non sovraccaricare il server
         potendo così offire un migliore servizio ai clienti finali
    -->
    Benvenuto <?php echo $_SESSION['_nome_impiegato']." (id=".$_SESSION['_id_impiegato'].")"?> <a href="logout.php">Logout</a> <br><br><br>
    <!--  Ho implementato le 4 query del progetto database anche se poco significative-->
    <table>
        <tr>
            <td>QUERY 1 Calcolare il ricavo perso a causa di tutti gli ordini annullati</td>
            <td><button id="q1">Esegui</button></td>
        </tr>
        <tr>
            <td>QUERY 2  Mostrare i magazzini per cui lavorano o hanno lavorato almeno 3 impiegati con sede in una città che inizia per una data stringa</td>
            <td><button id="q2">Esegui</button></td>
        </tr>
        <tr>
            <td>QUERY 3 Inserire un dipendente e assegnarlo alla sede di Catania se è nato dopo il 1990 altrimenti assegnarlo alla sede con più dipendenti</td>
            <td><button id="q3">Esegui</button></td>
        </tr>
        <tr>
            <td>QUERY 4 Mostrare tutti i prodotti contenuti in ordini effettuati nel 2019 da omonimi</td>
            <td><button id="q4">Esegui</button></td>
        </tr>
    </table>
</body>
</html>


