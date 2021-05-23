/***************************************************************************************
 * QUESTO FILE CONTIENE TUTTO IL COMPORTAMENTO COMUNE ALLA MAGGIOR PARTE DELLE PAGINE. *
 *           IN PARTICOLARE IL COMPORTAMENTO DELLA NAVBAR E DEL MENÙ MOBILE            *
 ***************************************************************************************/
//CATEGORIE NAVBAR
//faccio il fetch delle categorie per il menù a tendina
fetch("get_categorie.php").then(onResponse).then(onCategorieJson);

function onResponse(promise) {
    return promise.json();
}

function onCategorieJson(json) {
    const navbarCategorie = document.querySelector('#categoria');
    for (categoria of json) {
        //Navbar categorie 
        //<a href=categorie.php?c=1">Nome categoria</a>";
        const divCategoriaNavbar = document.createElement('a');
        divCategoriaNavbar.href = "categorie.php?c=" + categoria.Codice;
        divCategoriaNavbar.textContent = categoria.Nome;
        navbarCategorie.appendChild(divCategoriaNavbar);
    }
}

//============Numeri navbar==================
fetch('is_logged.php').then(onResponse).then(onLoggedJson);

function onLoggedJson(json) {
    var logged = json;
    if (logged)
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
}

function onNumeriJson(json) {
    var numeroPreferiti = json.NPreferiti;;
    const nPreferiti = document.querySelector('#desideri .numero');
    nPreferiti.textContent = json.NPreferiti;
    if (json.NPreferiti) //se il cliente ha preferiti li richiedo
        fetch("get_preferiti.php").then(onResponse).then(onPreferitiJson);
    var numeroCarrello = json.NCarrello;
    const nCarrello = document.querySelector('#carrello .numero');
    nCarrello.textContent = json.NCarrello;
    if (json.NCarrello) // se il cliente ha articoli nel carrello li richiedo
        fetch("get_carrello.php").then(onResponse).then(onCarrelloJson);
}

function onPreferitiJson(json) {
    //Preferiti navbar
    /*
    <div class="item">
        <img class="immagine" src="link immagine">
        <div class="content">
            <strong>Marca</strong>
            <em class="nome">Nome</em>
        </div>
        <img class="btn-cuore" src="img/cuore-spezzato-rosso.png">
    </div>
    */
    const navbar = document.querySelector('#preferiti-navbar');
    navbar.innerHTML = "";
    /*
    <div class="empty">
        <img src="img/wishlist-vuota.svg"> Non ci sono preferiti nella lista!
    </div>
    */
    //Nel caso non ci sono elementi mostro l'immagine che lo comunica
    if (json.length == 0) {
        const empty = document.createElement('div');
        empty.classList.add('empty');
        navbar.appendChild(empty);
        const emptyImg = document.createElement('img');
        emptyImg.src = "img/wishlist-vuota.svg";
        empty.appendChild(emptyImg);
        navbar.appendChild(document.createTextNode("Non ci sono preferiti nella lista!"));
    }
    for (p of json) {
        const itemPreferitoNavbar = document.createElement('div');
        itemPreferitoNavbar.classList.add('item');
        itemPreferitoNavbar.dataset.ean = p.EAN;
        navbar.appendChild(itemPreferitoNavbar);
        const imgPreferitoNavbar = document.createElement('img');
        imgPreferitoNavbar.classList.add('immagine');
        imgPreferitoNavbar.src = p.src;
        itemPreferitoNavbar.appendChild(imgPreferitoNavbar);
        const contenutoPreferitoNavbar = document.createElement('a');
        contenutoPreferitoNavbar.href = "prodotto.php?EAN=" + p.EAN;
        contenutoPreferitoNavbar.classList.add('content');
        const marcaPreferito = document.createElement('strong');
        marcaPreferito.textContent = p.Marca;
        contenutoPreferitoNavbar.appendChild(marcaPreferito);
        const nomePreferito = document.createElement('em');
        nomePreferito.classList.add('nome');
        nomePreferito.textContent = p.Nome;
        contenutoPreferitoNavbar.appendChild(nomePreferito);
        itemPreferitoNavbar.appendChild(contenutoPreferitoNavbar);
        const buttonCuore = document.createElement('img');
        buttonCuore.classList.add('btn-cuore');
        buttonCuore.src = 'img/cuore-spezzato-rosso.png';
        buttonCuore.addEventListener('click', onClickRimuoviDaiPreferitiNavbar);
        itemPreferitoNavbar.appendChild(buttonCuore);
    }
}

function onCarrelloJson(json) {
    //Carrello navbar
    /*
    <div class="item">
        <img class="immagine" src="link immagine">
        <div class="content">
            <strong>Marca</strong>
            <em class="nome">Nome</em>
        </div>
        <img class="btn-carrello" src="img/svuota-carrello.png">
    </div>
    */
    const navbar = document.querySelector('#carrello-navbar');
    navbar.innerHTML = "";
    /*
    <div class="empty">
        <img src="img/empty_cart.svg"> Carrello vuoto!
    </div>
    */
    //Nel caso non ci sono elementi mostro l'immagine che lo comunica
    if (json.length == 0) {
        const empty = document.createElement('div');
        empty.classList.add('empty');
        navbar.appendChild(empty);
        const emptyImg = document.createElement('img');
        emptyImg.src = "img/empty_cart.svg";
        empty.appendChild(emptyImg);
        navbar.appendChild(document.createTextNode("Carrello vuoto!"));
    }
    for (p of json) {
        const itemCarrelloNavbar = document.createElement('div');
        itemCarrelloNavbar.classList.add('item');
        itemCarrelloNavbar.dataset.ean = p.EAN;
        navbar.appendChild(itemCarrelloNavbar);
        const imgCarrelloNavbar = document.createElement('img');
        imgCarrelloNavbar.classList.add('immagine');
        imgCarrelloNavbar.src = p.src;
        itemCarrelloNavbar.appendChild(imgCarrelloNavbar);
        const contenutoCarrelloNavbar = document.createElement('a');
        contenutoCarrelloNavbar.href = "prodotto.php?EAN=" + p.EAN;
        contenutoCarrelloNavbar.classList.add('content');
        const marcaCarrello = document.createElement('strong');
        marcaCarrello.textContent = p.Marca;
        contenutoCarrelloNavbar.appendChild(marcaCarrello);
        const nomeCarrello = document.createElement('em');
        nomeCarrello.classList.add('nome');
        nomeCarrello.textContent = p.Nome;
        contenutoCarrelloNavbar.appendChild(nomeCarrello);
        contenutoCarrelloNavbar.appendChild(document.createTextNode("Quantità: " + p.quantità));
        itemCarrelloNavbar.appendChild(contenutoCarrelloNavbar);
        const buttonCarrello = document.createElement('img');
        buttonCarrello.classList.add('btn-carrello');
        buttonCarrello.src = 'img/svuota-carrello.png';
        buttonCarrello.addEventListener('click', onClickRimuoviDalCarrelloNavbar);
        itemCarrelloNavbar.appendChild(buttonCarrello);
    }
}
//rimuovo l'elemento cliccato dai preferiti
function onClickRimuoviDaiPreferitiNavbar(e) {
    fetch("preferiti_rimuovi.php?EAN=" + e.currentTarget.parentNode.dataset.ean).then(onResponse).then(onPreferitoRimosso);
}
//quando rimuovo un elemento dai preferiti ricarico tutta la navbar
function onPreferitoRimosso(json) {
    if (json) {
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
    }
}
//rimuovo l'elemento cliccato dal carrello
function onClickRimuoviDalCarrelloNavbar(e) {
    fetch("carrello_rimuovi.php?EAN=" + e.currentTarget.parentNode.dataset.ean).then(onResponse).then(onCarrelloRimosso);

}
//quando rimuovo un elemento dal carrello ricarico tutta la navbar
function onCarrelloRimosso(json) {
    if (json) {
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
    }
}

//==============MOBILE=============
const hamburger = document.getElementById("hamburger-wrapper");
hamburger.addEventListener('click', apriMenuMobile);
let menuAperto = false;
const menuMobile = document.getElementById("menu-mobile");
const barraRicerca = document.querySelector(".search");
//classe che nasconde la barra di ricerca sotto una certa dimensione definita con una media query
barraRicerca.classList.add('nascosta');

function apriMenuMobile() {
    if (!menuAperto) {
        //l'hamburger diventa una x
        hamburger.classList.add('open');
        menuAperto = true;
        //mostro il menù mobile
        menuMobile.classList.remove('hidden');
        //blocco la possibilità di scorrere la pagina
        document.body.classList.add('no-scroll');
        //mostro la barra (quando il dispositivo ha larghezza minore di 375px)
        barraRicerca.classList.remove('nascosta');
    } else {
        //la x diventa un hamburger di nuovo
        hamburger.classList.remove('open');
        menuAperto = false;
        //nascondo il menù mobile
        menuMobile.classList.add('hidden');
        //riabilito lo scroll
        document.body.classList.remove('no-scroll');
        //nascondo la barra (quando il dispositivo ha larghezza minore di 375px)
        barraRicerca.classList.add('nascosta');
    }

}