<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pirri shop - Informazioni</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9ff6dd1595.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/informazioni.css">
    <script src="js/main.js" defer></script>
    <script src="js/search.js" defer></script>
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
    </header>
    <article>
    <h1 id="contatti">Contatti</h1>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus reprehenderit asperiores sequi aliquid soluta voluptate et dolores, ratione sed perferendis voluptatem culpa illum! Saepe magnam quia asperiores rem, reiciendis quidem?
    Harum obcaecati ipsa voluptatibus debitis eaque corrupti inventore consectetur odio architecto laudantium nobis aliquam libero, laboriosam magnam exercitationem quo iure mollitia est. Maxime, voluptates omnis corrupti illo harum neque laboriosam.
    Sequi voluptatibus magni porro esse, ipsum ipsam aperiam repellendus iusto nobis earum repudiandae aliquam et voluptatem similique atque officia. Nobis neque corrupti vel expedita. Nam illo architecto cumque quis provident.
    Sunt, ab corrupti, atque officia cumque obcaecati nam aspernatur, veniam maxime odio esse impedit temporibus eius quasi necessitatibus sequi labore ad dolor veritatis consequatur quam debitis laborum dolorum explicabo. Nisi!
    Vitae veritatis amet voluptas molestias nemo ipsum error pariatur officiis. Illum doloremque adipisci inventore itaque, repudiandae quas ratione illo natus voluptate, aperiam possimus iusto velit dolore. Aut minus fuga facere!
    Voluptatum reiciendis eius maiores laborum, rem iste iusto ratione saepe nemo ipsam veritatis odit labore, praesentium quidem eveniet eum minima odio repudiandae nobis dolore ad quasi sint nulla ab. A.
    Repellendus hic nobis laborum voluptatibus vero? Reiciendis dolor quaerat eum nostrum quasi maxime in accusamus maiores, laboriosam porro, obcaecati perspiciatis quia magnam, quas veniam ipsam placeat architecto velit fugiat qui.
    Autem rem vitae qui illum expedita aut voluptates reiciendis suscipit non sunt similique eligendi ab aliquam quaerat nam tempore quam eius molestiae magnam voluptate incidunt, reprehenderit vero? Laudantium, quidem quo.
    Recusandae, culpa, provident impedit cum quae corrupti reprehenderit ut excepturi animi, facilis magnam saepe. Sapiente placeat accusantium saepe fugit officia repellat laudantium quia expedita autem! Voluptas ea labore assumenda doloribus.
    Ab sequi ratione voluptatibus odit excepturi ipsam autem perspiciatis, nihil est nemo laborum nobis quisquam consequuntur cumque unde dicta facilis ducimus non recusandae, ea at libero culpa iste. Porro, hic.
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente ea doloribus omnis fugiat vitae fugit quasi quis, eaque officiis esse pariatur nesciunt, nobis neque dolor suscipit. Omnis provident natus quisquam?
    Eius officia ex deleniti nihil at mollitia placeat repellat? Aspernatur natus cupiditate similique tempora incidunt deleniti ad sint fugit consequuntur suscipit ducimus, vitae reiciendis aliquam commodi atque ipsa! Aperiam, sunt.
    Dolores, nobis! Ipsa, vitae consectetur. Quae recusandae ad dolores animi aut amet iusto obcaecati est mollitia et, itaque dolor minus corrupti harum porro! Nam aliquam ad quasi voluptatibus, alias ea!
    Neque tempore dolor sit officia repellat veniam aperiam laboriosam distinctio. Velit temporibus eum, exercitationem nam iste repellendus at. Iure provident eligendi saepe? Amet, mollitia rerum. Nostrum illo ullam nemo adipisci?
    Temporibus magni eius dolores itaque fugit accusantium libero. Pariatur mollitia autem dolorum illo eaque consectetur possimus? Labore accusamus soluta, asperiores distinctio sint earum, nulla dolores aspernatur error inventore est ratione.
    Modi fugiat aut nulla fugit ea velit earum? Vero similique exercitationem dolorem ratione quidem unde! Commodi inventore libero ex iusto possimus esse neque adipisci cum? Natus tenetur deserunt molestias corrupti.
    Libero nemo quaerat quis nobis, eligendi modi fuga voluptatum, sint dignissimos ipsam debitis consectetur dolores unde ipsum amet optio sit nulla earum neque sed maiores? Quos odit autem adipisci unde.
    Similique ab quaerat atque sapiente nemo ipsa, sint corrupti et aperiam repudiandae recusandae blanditiis officiis dolorem autem corporis, tenetur voluptatibus cum perferendis eligendi eveniet doloribus. Atque recusandae nemo sequi dignissimos.
    Cupiditate nulla provident ducimus nemo facilis minima dicta, quas dolorum! Magnam officiis iste enim dignissimos. Ducimus voluptas tempore quod enim explicabo? Ex quam dolorum tempore laborum. Dolorem maxime mollitia odit.
    Ex, culpa animi? Itaque ea perspiciatis modi. Perferendis placeat aliquid doloremque veniam error hic eius, enim non distinctio architecto laboriosam nostrum necessitatibus fuga id omnis. Et repellat nam impedit sapiente.
    Labore facere architecto tempora voluptas, sed dolorum inventore eius amet officia dignissimos necessitatibus eum atque nulla a culpa sunt numquam nisi delectus ipsam at quidem sit excepturi esse! Doloribus, maxime!
    Laborum incidunt inventore officiis consectetur vel, consequatur in dolores minima voluptatum accusantium saepe nulla, recusandae ipsum dignissimos sunt fugit quibusdam obcaecati rem blanditiis, eius nihil quaerat deleniti. Delectus, voluptatibus sequi?
    Dicta deleniti labore voluptatum necessitatibus maxime repudiandae vel totam minus? Et hic minus minima cumque ratione deleniti quam, quisquam mollitia, voluptatum velit iusto atque veniam esse accusantium nulla maxime? Repudiandae.
    Atque ab asperiores dignissimos ducimus vitae officiis unde quaerat itaque omnis labore veniam, incidunt repellendus iure tenetur magni illum deserunt magnam facere tempore rerum tempora nihil! Eligendi nemo natus placeat.
    Id porro pariatur amet reprehenderit totam vel adipisci, laboriosam nobis officia necessitatibus tempora, accusamus provident cupiditate quas asperiores deleniti inventore, assumenda fugiat numquam ducimus magni temporibus tempore dolor? Repudiandae, ipsum.
    Eum expedita nemo ratione vero eaque assumenda quam fuga quisquam, harum, suscipit quis itaque officia sed commodi inventore quasi nihil fugit! Voluptate deleniti quos velit earum corporis, consequuntur quidem ex?
    Impedit doloremque accusantium minima nemo laboriosam modi earum explicabo voluptates. Dicta sapiente voluptatum incidunt cumque, voluptatibus non ducimus! Maiores autem deserunt obcaecati facilis quibusdam deleniti dolorum nam, nostrum unde eaque.
    Laborum sit iste, laudantium vitae adipisci vero eaque vel? Voluptatem consectetur placeat ad temporibus quia cum eius quam iste accusamus obcaecati inventore cupiditate debitis, optio quas amet eveniet aperiam pariatur!
    Minus, perspiciatis praesentium explicabo labore atque fuga. Magni reprehenderit odit necessitatibus molestiae modi, architecto consectetur cupiditate accusantium iure ipsam, dignissimos impedit iste soluta cumque dolor at ducimus assumenda deserunt. Eligendi.
    Est, eligendi ad. Animi possimus nobis minus id, enim exercitationem vero nesciunt illo asperiores nemo placeat ex quo fuga eius eligendi nihil, sunt a dignissimos pariatur ipsum? Unde, quos velit.
    <h1 id="spedizioni-pagamenti">Spedizioni e pagamenti</h1>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus reprehenderit asperiores sequi aliquid soluta voluptate et dolores, ratione sed perferendis voluptatem culpa illum! Saepe magnam quia asperiores rem, reiciendis quidem?
    Harum obcaecati ipsa voluptatibus debitis eaque corrupti inventore consectetur odio architecto laudantium nobis aliquam libero, laboriosam magnam exercitationem quo iure mollitia est. Maxime, voluptates omnis corrupti illo harum neque laboriosam.
    Sequi voluptatibus magni porro esse, ipsum ipsam aperiam repellendus iusto nobis earum repudiandae aliquam et voluptatem similique atque officia. Nobis neque corrupti vel expedita. Nam illo architecto cumque quis provident.
    Sunt, ab corrupti, atque officia cumque obcaecati nam aspernatur, veniam maxime odio esse impedit temporibus eius quasi necessitatibus sequi labore ad dolor veritatis consequatur quam debitis laborum dolorum explicabo. Nisi!
    Vitae veritatis amet voluptas molestias nemo ipsum error pariatur officiis. Illum doloremque adipisci inventore itaque, repudiandae quas ratione illo natus voluptate, aperiam possimus iusto velit dolore. Aut minus fuga facere!
    Voluptatum reiciendis eius maiores laborum, rem iste iusto ratione saepe nemo ipsam veritatis odit labore, praesentium quidem eveniet eum minima odio repudiandae nobis dolore ad quasi sint nulla ab. A.
    Repellendus hic nobis laborum voluptatibus vero? Reiciendis dolor quaerat eum nostrum quasi maxime in accusamus maiores, laboriosam porro, obcaecati perspiciatis quia magnam, quas veniam ipsam placeat architecto velit fugiat qui.
    Autem rem vitae qui illum expedita aut voluptates reiciendis suscipit non sunt similique eligendi ab aliquam quaerat nam tempore quam eius molestiae magnam voluptate incidunt, reprehenderit vero? Laudantium, quidem quo.
    Recusandae, culpa, provident impedit cum quae corrupti reprehenderit ut excepturi animi, facilis magnam saepe. Sapiente placeat accusantium saepe fugit officia repellat laudantium quia expedita autem! Voluptas ea labore assumenda doloribus.
    Ab sequi ratione voluptatibus odit excepturi ipsam autem perspiciatis, nihil est nemo laborum nobis quisquam consequuntur cumque unde dicta facilis ducimus non recusandae, ea at libero culpa iste. Porro, hic.
    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Distinctio, rerum et. Impedit inventore dolores quasi ad voluptate commodi eos voluptatum nulla repudiandae, nisi soluta ipsa autem beatae, sapiente, consequuntur voluptas.
    Quisquam autem tempora laudantium possimus blanditiis eos, error reprehenderit doloremque veritatis voluptates. Autem quidem maxime aperiam, deserunt sed quibusdam repellendus sunt natus veritatis ex! In mollitia praesentium tempora temporibus sunt!
    Saepe repellat maxime incidunt fuga sapiente mollitia quaerat est. Modi atque sed quos iusto qui tempora, architecto, est ab placeat, aut vel facere voluptatum. Magni perspiciatis accusantium iure omnis mollitia?
    Deleniti dicta alias ducimus nemo magni quia officiis? Necessitatibus, a explicabo. Aut veritatis asperiores quis, rem voluptate soluta tenetur eveniet distinctio ullam qui pariatur maxime consectetur, minima corrupti totam natus?
    Eius sit quod voluptate odio dolore perferendis numquam, qui exercitationem explicabo eos beatae esse rerum saepe quo optio enim soluta. Magni rerum nobis officiis! Eveniet repudiandae voluptatem voluptatum cumque at.
    Hic porro incidunt voluptatum maiores quibusdam! Consequuntur magni aspernatur ea neque consequatur minima. Inventore molestiae, blanditiis laborum error natus libero autem nobis sapiente repellat temporibus repudiandae, maxime officia expedita cupiditate!
    Et placeat non at, excepturi minima eos, eaque nemo sapiente iure exercitationem, velit repellendus aut accusantium magnam! Tempore vitae hic consectetur minima quo, quaerat incidunt dolorum recusandae, eligendi mollitia explicabo.
    Dolor error laborum quas, blanditiis inventore cum quam fuga perferendis eos! Voluptas expedita accusamus unde maiores vel assumenda cum hic a! Cumque officia labore nesciunt! Architecto reprehenderit sunt dolor deleniti.
    Quas voluptates quasi optio hic porro assumenda molestiae alias, atque laborum consequuntur necessitatibus, aspernatur sapiente ipsa repellendus exercitationem, odio quo cumque harum commodi in perferendis nulla aliquam error voluptatum. Nam.
    Velit tenetur ducimus blanditiis libero. Quo commodi nulla magnam molestias magni eveniet nisi consequuntur quidem, tempore perferendis consectetur repellat velit quae sit accusantium expedita, rem deleniti atque voluptate quaerat at.
    Laborum rem recusandae illo architecto aspernatur sunt officiis, magnam voluptates dolores impedit praesentium? Officia earum recusandae, velit eum iste incidunt, repudiandae nobis nihil et, tempora enim laudantium dignissimos ducimus id?
    Sed nihil fuga voluptate sint laudantium dolorum, perspiciatis ducimus officiis! Tenetur doloremque sunt molestiae consequatur fugit aspernatur, sapiente eaque obcaecati provident possimus quos? Quis, esse ducimus. Libero quae ratione nihil?
    Molestias error accusamus molestiae qui accusantium animi, commodi possimus asperiores distinctio voluptate dolore? Voluptate unde alias fugit beatae. Ipsa architecto amet quasi nobis inventore rem, minima debitis nihil recusandae delectus.
    Ea, illo odio! Maxime consequuntur nobis qui quas voluptatem placeat velit cupiditate laudantium rerum? Neque hic voluptatibus tempore voluptatem enim nisi molestias unde blanditiis vel eum. Numquam deleniti voluptatibus veniam!
    Quos odit, laudantium explicabo quas doloribus expedita eum velit temporibus autem sapiente blanditiis, earum, consequatur quod corrupti ratione. Perspiciatis earum maiores ut accusamus reprehenderit quos esse qui fugiat! Ea, beatae!
    Aspernatur animi quae, corrupti quaerat dolorum possimus nihil sequi saepe sed dolore eligendi aut cumque, temporibus architecto et? Vero eos exercitationem quo nemo odio dignissimos! Beatae cupiditate accusamus aperiam et.
    Odio et, a consectetur quam ullam expedita velit atque aliquid voluptatibus pariatur alias architecto quasi magnam enim quod soluta adipisci totam exercitationem officia quae eaque ducimus molestiae dolor. Dignissimos, porro.
    Accusamus provident neque iste enim odio tenetur nam laudantium possimus autem culpa. Minus, optio iste sit itaque, veniam obcaecati ut inventore dignissimos unde quis sed esse magni! Laudantium, aperiam natus?
    Esse officia sint sequi voluptate in assumenda officiis odio, aut iure! Optio vitae iusto provident fugit, natus reiciendis quos unde ut culpa eaque maiores id repudiandae ad vero ex corporis.
    Vero delectus dignissimos laborum quia tempora repudiandae quam est corporis quisquam esse eveniet neque, excepturi porro numquam sunt repellat et quae voluptatem. Repellat illo provident exercitationem beatae. Error, qui incidunt?
    <h1 id="termini-condizioni">Termini e condizioni</h1>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus reprehenderit asperiores sequi aliquid soluta voluptate et dolores, ratione sed perferendis voluptatem culpa illum! Saepe magnam quia asperiores rem, reiciendis quidem?
    Harum obcaecati ipsa voluptatibus debitis eaque corrupti inventore consectetur odio architecto laudantium nobis aliquam libero, laboriosam magnam exercitationem quo iure mollitia est. Maxime, voluptates omnis corrupti illo harum neque laboriosam.
    Sequi voluptatibus magni porro esse, ipsum ipsam aperiam repellendus iusto nobis earum repudiandae aliquam et voluptatem similique atque officia. Nobis neque corrupti vel expedita. Nam illo architecto cumque quis provident.
    Sunt, ab corrupti, atque officia cumque obcaecati nam aspernatur, veniam maxime odio esse impedit temporibus eius quasi necessitatibus sequi labore ad dolor veritatis consequatur quam debitis laborum dolorum explicabo. Nisi!
    Vitae veritatis amet voluptas molestias nemo ipsum error pariatur officiis. Illum doloremque adipisci inventore itaque, repudiandae quas ratione illo natus voluptate, aperiam possimus iusto velit dolore. Aut minus fuga facere!
    Voluptatum reiciendis eius maiores laborum, rem iste iusto ratione saepe nemo ipsam veritatis odit labore, praesentium quidem eveniet eum minima odio repudiandae nobis dolore ad quasi sint nulla ab. A.
    Repellendus hic nobis laborum voluptatibus vero? Reiciendis dolor quaerat eum nostrum quasi maxime in accusamus maiores, laboriosam porro, obcaecati perspiciatis quia magnam, quas veniam ipsam placeat architecto velit fugiat qui.
    Autem rem vitae qui illum expedita aut voluptates reiciendis suscipit non sunt similique eligendi ab aliquam quaerat nam tempore quam eius molestiae magnam voluptate incidunt, reprehenderit vero? Laudantium, quidem quo.
    Recusandae, culpa, provident impedit cum quae corrupti reprehenderit ut excepturi animi, facilis magnam saepe. Sapiente placeat accusantium saepe fugit officia repellat laudantium quia expedita autem! Voluptas ea labore assumenda doloribus.
    Ab sequi ratione voluptatibus odit excepturi ipsam autem perspiciatis, nihil est nemo laborum nobis quisquam consequuntur cumque unde dicta facilis ducimus non recusandae, ea at libero culpa iste. Porro, hic.
    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Tenetur rerum amet quasi maiores hic, magni, eligendi minima deserunt aliquid officiis illum modi dolorum ratione? Enim laborum odio dolores unde eligendi!
    Harum incidunt voluptatum exercitationem iure praesentium. Accusantium iste impedit ratione quidem minus numquam. Similique perferendis veniam dolor. Ipsum cum aperiam blanditiis ipsa sapiente reiciendis iure aut, totam odio laboriosam accusamus?
    Voluptatibus id repellendus ab consequatur qui quisquam velit optio odio, unde iusto soluta iure blanditiis molestiae, dolorem ipsam asperiores, earum officia suscipit aspernatur tenetur. Perferendis laboriosam ratione consequuntur quos dolores?
    Neque voluptatem sequi aperiam illum explicabo amet minus obcaecati eaque, numquam odio praesentium tempora reprehenderit quod? Iure quam quisquam, repellendus cupiditate saepe vero, delectus quos nihil ad nam accusamus earum.
    Rerum provident asperiores nemo iste autem obcaecati quasi temporibus voluptatibus ipsam cupiditate delectus, atque, expedita nam officiis minus maxime aspernatur nisi, quas recusandae. Eius fuga nesciunt ullam ipsum, voluptate sed.
    Deleniti iste est aliquam odio iure! Nemo ducimus reprehenderit numquam, sapiente ipsa aliquam voluptate veritatis sunt vero dolore maiores molestias itaque beatae ipsam ipsum. Labore eaque obcaecati rerum fugiat reiciendis.
    Ipsam ex consequuntur earum. Beatae nihil debitis nesciunt nisi porro vitae reprehenderit unde fuga ad fugit. Itaque provident eligendi accusantium odit dignissimos tenetur inventore, officia quibusdam, labore libero illo doloremque.
    Itaque, quod! Nisi repellat dicta deleniti debitis quas expedita, dolore earum exercitationem! Minima numquam deserunt temporibus vitae perspiciatis totam illo, at expedita perferendis nisi maiores consectetur eaque harum aliquam rem?
    Deserunt sunt beatae repellat nostrum neque consequuntur, cumque ullam, odio doloremque dolore recusandae molestias aut obcaecati illo accusantium unde porro? Magnam deserunt tempora voluptatum quam consequatur vel commodi possimus earum?
    Exercitationem sunt possimus aliquid laboriosam sit minima consequatur, magni et quos accusamus earum ipsam, deserunt iste rem mollitia est voluptates ullam. Similique alias illum quibusdam repudiandae molestiae. Error, eaque voluptatibus.
    Soluta placeat totam laborum corrupti ullam ex eius inventore minus illo molestias animi, optio aspernatur officiis exercitationem ea beatae. Dolore quos voluptates consectetur molestiae, unde non inventore libero sint nostrum.
    Molestias quos vitae libero sequi architecto magni commodi veniam dolor rerum delectus consequuntur aspernatur mollitia quod at, cumque incidunt quidem? Assumenda dolore repellendus aut quam placeat soluta a enim cupiditate.
    Cupiditate aut aperiam alias illum. Deleniti quam voluptatibus eligendi architecto error in voluptas accusantium omnis ratione fugiat enim atque obcaecati exercitationem consequatur eius sed esse debitis consequuntur, quasi accusamus laboriosam?
    Sapiente architecto provident ipsam distinctio assumenda expedita, maiores, autem asperiores odio beatae nostrum blanditiis, sint totam in quos esse pariatur! Et ducimus labore soluta temporibus illum sapiente quisquam voluptas asperiores?
    Hic fugit vitae voluptatum error laborum minus nesciunt optio. Facere voluptate quibusdam hic neque impedit provident nisi, molestiae rem voluptatem nobis quaerat, odio velit doloremque, nesciunt beatae laudantium? Similique, corporis.
    Fugit ea aliquid perspiciatis iusto magni nemo voluptas distinctio ut accusamus ad laboriosam consectetur, sunt, debitis laudantium repellat adipisci impedit ab commodi porro optio fugiat veritatis quos molestiae. Molestiae, ullam?
    Soluta eveniet, corrupti delectus nisi at sit, alias animi illum cumque ratione iusto fugit nobis provident tempora eius quas obcaecati consectetur? Praesentium maxime, repudiandae dolorum exercitationem facere magni quibusdam dolores.
    Magni non, tempora quis, magnam reprehenderit consectetur repudiandae quod beatae ipsa, possimus harum sunt et hic. Aspernatur facilis odit sint deserunt! Exercitationem at incidunt aliquid earum ut debitis odio reprehenderit.
    Cupiditate, repudiandae dolore quidem saepe totam id neque possimus ratione vel aperiam eveniet amet impedit beatae quis non veritatis facere dignissimos exercitationem ad distinctio voluptates molestiae debitis odio veniam. Eaque!
    Quibusdam, quod. Error quam asperiores, nobis quidem numquam natus, vero sint laborum enim reprehenderit maiores eligendi. Quibusdam ratione ipsum nisi accusantium maiores in laboriosam neque quos explicabo harum, vero hic!
    <h1 id="lavoro">Offerte di lavoro</h1>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus reprehenderit asperiores sequi aliquid soluta voluptate et dolores, ratione sed perferendis voluptatem culpa illum! Saepe magnam quia asperiores rem, reiciendis quidem?
    Harum obcaecati ipsa voluptatibus debitis eaque corrupti inventore consectetur odio architecto laudantium nobis aliquam libero, laboriosam magnam exercitationem quo iure mollitia est. Maxime, voluptates omnis corrupti illo harum neque laboriosam.
    Sequi voluptatibus magni porro esse, ipsum ipsam aperiam repellendus iusto nobis earum repudiandae aliquam et voluptatem similique atque officia. Nobis neque corrupti vel expedita. Nam illo architecto cumque quis provident.
    Sunt, ab corrupti, atque officia cumque obcaecati nam aspernatur, veniam maxime odio esse impedit temporibus eius quasi necessitatibus sequi labore ad dolor veritatis consequatur quam debitis laborum dolorum explicabo. Nisi!
    Vitae veritatis amet voluptas molestias nemo ipsum error pariatur officiis. Illum doloremque adipisci inventore itaque, repudiandae quas ratione illo natus voluptate, aperiam possimus iusto velit dolore. Aut minus fuga facere!
    Voluptatum reiciendis eius maiores laborum, rem iste iusto ratione saepe nemo ipsam veritatis odit labore, praesentium quidem eveniet eum minima odio repudiandae nobis dolore ad quasi sint nulla ab. A.
    Repellendus hic nobis laborum voluptatibus vero? Reiciendis dolor quaerat eum nostrum quasi maxime in accusamus maiores, laboriosam porro, obcaecati perspiciatis quia magnam, quas veniam ipsam placeat architecto velit fugiat qui.
    Autem rem vitae qui illum expedita aut voluptates reiciendis suscipit non sunt similique eligendi ab aliquam quaerat nam tempore quam eius molestiae magnam voluptate incidunt, reprehenderit vero? Laudantium, quidem quo.
    Recusandae, culpa, provident impedit cum quae corrupti reprehenderit ut excepturi animi, facilis magnam saepe. Sapiente placeat accusantium saepe fugit officia repellat laudantium quia expedita autem! Voluptas ea labore assumenda doloribus.
    Ab sequi ratione voluptatibus odit excepturi ipsam autem perspiciatis, nihil est nemo laborum nobis quisquam consequuntur cumque unde dicta facilis ducimus non recusandae, ea at libero culpa iste. Porro, hic.
    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ratione obcaecati numquam corrupti maxime laboriosam ullam sed magnam quia sint pariatur. Earum, blanditiis! Eum laudantium fugiat praesentium! Consequuntur odio natus illo.
    Modi, eius. Repellendus ducimus rem eius quas nesciunt nulla aperiam obcaecati, eum labore iusto asperiores at dolorem ipsum repudiandae debitis incidunt. Dignissimos cupiditate eum nobis aspernatur assumenda ipsam impedit iure.
    Omnis tempora, culpa autem similique illum labore sequi quae rem eius fugiat eveniet cum! Veritatis consequatur vel, eius iusto, quibusdam laborum qui est, aliquam atque praesentium harum. Itaque, ab numquam!
    Ad similique at ipsum praesentium vero officiis veritatis animi, natus amet ducimus quas numquam nisi eum doloribus, tempore illo! Ab dignissimos unde commodi, aliquid libero expedita ex natus eaque dolore.
    Impedit, quia reiciendis? Officiis dolor laborum atque, ad nam voluptates debitis ullam iusto deserunt inventore in hic nisi nesciunt iure! Ipsam ad nesciunt pariatur recusandae aut, laboriosam magni quos quibusdam.
    Voluptatibus voluptatum provident corporis possimus odit libero totam incidunt. Laboriosam fuga eveniet praesentium aut nulla aperiam. Recusandae illum, nisi ducimus ab eos dolorem rem quasi enim quia assumenda mollitia incidunt.
    Consequatur soluta numquam adipisci veritatis nulla voluptas, optio, amet asperiores delectus omnis quod commodi nihil perspiciatis molestias. Autem, aliquid odio nisi odit asperiores inventore ullam, corrupti molestias eligendi cumque a!
    Voluptatibus, dolorem corporis vel quos assumenda soluta facere maxime magni laborum consequatur asperiores consectetur quam porro natus libero id necessitatibus ab eaque in amet incidunt mollitia neque quas vero. Laborum?
    Nam laboriosam aspernatur numquam ipsam, iste omnis reiciendis ad laborum molestiae suscipit nihil et neque eos nobis veniam voluptatibus. Nemo dolorum accusamus eos nam animi? Sequi quidem commodi voluptatibus temporibus.
    Illo deserunt expedita earum cupiditate maiores, velit quia nostrum tempore nam officiis ipsam vitae ullam unde nisi doloribus atque officia dignissimos culpa odio odit molestias illum rem. Id, minima voluptatem!
    Asperiores odio labore eos provident pariatur. Minus facere obcaecati aliquid repellendus odit? Suscipit, doloribus possimus! Mollitia minus, odit ut vitae itaque dolore sint necessitatibus libero. Obcaecati necessitatibus consequuntur deleniti ab?
    Esse officia temporibus delectus perspiciatis eaque placeat ullam illo quisquam animi sunt accusamus in corrupti dignissimos, facere cupiditate quam accusantium magnam amet fugit vitae magni! Saepe repellendus voluptatum nulla ex.
    Tenetur quibusdam excepturi quo eaque, omnis veniam distinctio deserunt alias numquam sequi. Ex consectetur possimus, doloremque at error cumque porro eos adipisci et deserunt quae earum eveniet odit sint doloribus!
    Labore possimus esse odit harum placeat non ab doloribus accusamus vero veniam. Saepe illum deleniti est reiciendis laboriosam. Dolore ea, tempora nostrum dicta deserunt saepe. Praesentium distinctio eveniet est nihil.
    Odit, tempore facere repudiandae quisquam laboriosam enim modi quas error cum quam, ullam nisi dolorem fugiat! Ratione vitae voluptate nobis, ab ipsa esse tenetur aut doloremque, modi ipsam quasi magnam.
    Repellendus modi magnam id velit provident autem dolore eaque quasi natus distinctio dolorem perspiciatis rem ipsum alias aliquid, praesentium cumque magni optio omnis eos assumenda similique fugiat. Quo, fugiat soluta.
    Quasi beatae, est, iusto pariatur suscipit laborum distinctio eos nesciunt sequi veritatis, esse ab aut rem. Nihil esse maxime facere aspernatur. Atque nesciunt in unde quam harum exercitationem optio dolores.
    Facilis quos iure quis nisi ex impedit accusamus possimus dolorum qui, deserunt molestias vitae eos facere sed magni sint? Animi repudiandae molestias officiis molestiae et optio! Corporis vero deserunt officia!
    Atque, incidunt aliquid. Ducimus placeat earum sit nobis quos soluta perspiciatis, nulla, modi consequuntur ex nesciunt sed ipsum minima libero sunt provident sint odit similique delectus, quo ab. Vero, cupiditate!
    Fugit sint repellat consequuntur hic adipisci. Possimus, adipisci porro sapiente quas nisi quidem obcaecati veniam praesentium officiis quam ratione, amet nesciunt numquam harum ea molestiae iste excepturi nihil ex. Quaerat!








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

    