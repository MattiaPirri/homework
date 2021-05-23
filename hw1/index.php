<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pirri shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9ff6dd1595.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/index.css">
    <script src="js/main.js" defer></script>
    <script src="js/search.js" defer></script>
    <script src="js/item-prodotto.js" defer></script>
    <script src="js/index.js" defer></script>
</head>

<body>
    <header>
        <nav>
            <a href="index.php">
                <div class="logo">
                    PIRRI
                    <div>shop</div>
                </div>
            </a>
            <div class="search">
                <input type="text" class="termini-ricerca" placeholder="Cerca...">
                <button type="submit" class="search-button">
                    <img src="img/search-white.svg">
                </button>
                <div class="search-results">
                </div>
            </div>         
            <div id="menu">
                <div id="menu-contenuto">                  
                    <a href="categorie.php">
                        <div id="categorie">Categorie▾
                            <div id="categoria">
                            </div>
                        </div>
                    </a>
                    <a href="bestseller.php">Bestseller</a>
                    <span>|</span>
                    <div id="destra">
                        <div class="dropdown">
                            <div id="hover-utente">
                                <div id="utente-navbar">
                                    <?php
                                        if (isset($_SESSION["_nome_cliente"])) {
                                            echo "<div class=\"logged\">";
                                            echo $_SESSION["_nome_cliente"];
                                            echo "<a href= \"account.php\">Dettagli account</a>";
                                            echo "<a href= \"ordini.php\">Ordini</a>";
                                            echo "<a href= \"logout.php\">Logout</a>";
                                            echo "</div>";
                                        } else {
                                            echo "<a href= \"login.php\">Accedi</a>"; 
                                        }
                                    ?>
                                </div>
                            </div>
                            <a id="utente" href="account.php">
                                <div class="img"></div>
                                <span>Utente</span>
                            </a>
                        </div>
                        <div class="dropdown">
                            <div id="hover-desideri">
                                <div id="preferiti-navbar">
                                <?php
                                        if (isset($_SESSION["_nome_cliente"])) { 
                                            echo" <div class=\"empty\">
                                                <img src=\"img/wishlist-vuota.svg\"> Non ci sono preferiti nella lista!
                                            </div>";
                                        } else {
                                            echo "<div class=\"not-logged\"><a href=\"login.php\">Accedi</a> per vedere la tua lista dei desideri</div>"; 
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                            <a id="desideri" href="lista_desideri.php">
                                <div class="img"></div>
                                <span>Lista desideri</span>
                                <div class="numero
                                    <?php
                                        if (!isset($_SESSION["_nome_cliente"])) { 
                                            echo "hidden";
                                        }
                                    ?>
                                ">0</div>
                            </a>
                        </div>
                        <div class="dropdown">
                            <div id="hover-carrello">
                                <div id="carrello-navbar">
                                    <?php
                                        if (isset($_SESSION["_nome_cliente"])) { 
                                            echo" <div class=\"empty\">
                                                <img src=\"img/empty_cart.svg\"> Carrello vuoto!
                                            </div>";
                                        } else {
                                            echo "<div class=\"not-logged\"><a href=\"login.php\">Accedi</a> per vedere il tuo carrello</div>"; 
                                        }
                                    ?>
                                </div>
                            </div>
                            <a id="carrello" href="carrello.php">
                                <div class="img"></div>
                                <span>Carrello</span>
                                <div class="numero
                                    <?php
                                        if (!isset($_SESSION["_nome_cliente"])) { 
                                            echo "hidden";
                                        }
                                    ?>
                                ">0</div>       
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="hamburger-wrapper">
                <div id="hamburger-btn"></div>
            </div>
            <div id="menu-mobile" class="hidden">
                <a href="index.php">
                    <div class="logo">
                        PIRRI
                        <div>shop</div>
                    </div>
                </a>
                <a id="menu-mobile-categorie" href="categorie.php">Categorie</a>
                <a id="menu-mobile-bestseller" href="bestseller.php">Bestseller</a>
                <a id="menu-mobile-utente" href="account.php">Utente</a>
                <?php   if(isset($_SESSION['_id_cliente'])) 
                            echo "<a id=\"menu-mobile-logout\" href=\"logout.php\">Logout</a>";
                        else
                            echo "<a id=\"menu-mobile-login\" href=\"login.php\">Login</a>";
                ?> 
                <a id="menu-mobile-lista-desideri" href="lista_desideri.php">Lista desideri</a>
                <a id="menu-mobile-carrello" href="carrello.php">Carrello</a>
            </div>
        </nav>
        <h1>Benvenuto <?php
                        if (isset($_SESSION["_nome_cliente"])) {
                            echo $_SESSION["_nome_cliente"];
                        } else {
                            echo "nel nostro shop";
                        }
                        ?>
        </h1>
    </header>
    <article>
        <h2>Acquista per categoria</h2>
        <section id="cat">
            <div id="loader-categorie"></div>
        </section>
        <h2>Prodotti in evidenza</h2>
        <input type="text" placeholder="Cerca tra gli articoli" id="input-evidenza">
        <section id="prodotti">
            <div id="loader-prodotti"></div>
        </section>
        <section id="modal-view" class="hidden">
            <div class="modal-content">
                <h1>Non sei autenticato</h1>
                <a href="login.php">Accedi</a> per utilizzare questa funzione
            </div>
        </section>
        <section id="modal-view-quantita" class="hidden">
            <div class="modal-content">
                <div id="prodotto">
                </div>
                <h1>Seleziona la quantità</h1>
                <select id="quantita">
                </select><br>
                <span class="non-disponibile hidden">Prodotto non disponibile</span><br>
                <span class="alert">Attenzione se già l'articolo è presente nel carrello, la quantità viene aggiornata (Non si somma).</span>
            </div>
        </section>
        <section id="modal-view-recensioni" class="hidden">
            <div class="modal-content">
                <h1>Recensioni</h1>
                <div id="div-recensioni"></div>
            </div>
        </section>
    </article>
    <footer>
        <div id="footer">
            <div class="item">
                <a href="index.php">
                    <div class="logo">
                        PIRRI
                        <div>shop</div>
                    </div>
                </a>
                <address>Via Strada 5, Acireale, 95024, CT</address>
            </div>
            <div class="item">
                <strong><a href="informazioni.php">Informazioni</a></strong><br>
                <a href="informazioni.php#contatti">Contatti</a><br>
                <a href="informazioni.php#spedizioni-pagamenti">Spedizioni e pagamenti</a><br>
                <a href="informazioni.php#termini-condizioni">Termini e condizioni</a><br>
                <a href="informazioni.php#lavoro">Offerte di lavoro</a><br>
                <a href="area_riservata.php">Area riservata</a>
            </div>
            <div class="item">
                <strong>Account</strong><br>
                <?php 
                
                    if(isset($_SESSION['_id_cliente']))
                        echo "<a href=\"logout.php\">Logout</a><br>";
                    else 
                        echo "<a href=\"login.php\">Log in</a><br>";
                      
                ?>
                <a href="account.php">Il mio account</a><br>
                <a href="ordini.php">Ordini</a><br>
                <a href="lista_desideri.php">Lista desideri</a><br>
                <a href="carrello.php">Carrello</a><br>
            </div>
        </div>
        <p>Mattia Pirri o46002095</p>
    </footer>
</body>

</html>