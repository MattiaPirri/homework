//prendo l'ean dall'url
const urlParams = new URLSearchParams(window.location.search);
const parametroEAN = urlParams.get('EAN');
fetch('is_logged.php').then(onResponse).then(onLoggedJson);

function onLoggedJson(json) {
    isLogged = json;
    fetch("get_prodotto.php?EAN=" + parametroEAN).then(onResponse).then(onProdottoJson);
}


function onResponse(promise) {
    return promise.json();
}
//appena mi arrivano le informazioni del prodotto
function onProdottoJson(prodotto) {
    document.getElementById('loader').classList.add('hidden');
    //cambio il titolo della pagina dinamicamente
    document.title = "Pirri Shop - " + prodotto.Marca + " " + prodotto.Nome;
    const linkCategoria = document.getElementById("link-categoria");
    linkCategoria.textContent = prodotto.categoria;
    linkCategoria.href = "categorie.php?c=" + prodotto.CodiceCategoria;
    const wrapperProdotto = document.getElementById('wrapper-prodotto');
    wrapperProdotto.innerHTML = "";
    const img = document.createElement('img');
    img.src = prodotto.src;
    wrapperProdotto.appendChild(img);
    const divProdotto = document.createElement('div');
    divProdotto.id = "inner-prodotto";
    wrapperProdotto.appendChild(divProdotto);
    const marca = document.createElement("strong");
    marca.textContent = prodotto.Marca;
    divProdotto.appendChild(marca);
    const nome = document.createElement("span");
    nome.textContent = prodotto.Nome;
    divProdotto.appendChild(nome);
    const prezzo = document.createElement('span');
    //Per formattare il prezzo, fornito come numero decimale, in valuta
    const formatter = new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    })
    prezzo.textContent = formatter.format(prodotto.Prezzo) + " IVA inclusa";
    divProdotto.appendChild(prezzo);
    const stelle = document.createElement('span');
    stelle.classList.add('stelle');
    let textStelle = '';
    //mostro le stelle piene
    for (let i = 0; i < Math.floor(prodotto.Valutazione); i++)
        textStelle += "<i class=\"fas fa-star\"></i>";
    //mostro la mezza stella
    if (prodotto.Valutazione % 1)
        textStelle += "<i class=\"fas fa-star-half-alt\"></i>";
    //mostro le stelle vuote
    for (let i = 0; i < 5 - Math.ceil(prodotto.Valutazione); i++)
        textStelle += "<i class=\"far fa-star\"></i>";
    stelle.innerHTML = textStelle + "(" + Math.round(prodotto.Valutazione * 10) / 10 + ")"; //arrotondo alla prima cifra decimale
    stelle.addEventListener('click', (event) => apriModaleRecensioni(event, prodotto.EAN));
    divProdotto.appendChild(stelle);
    const recensioni = document.createElement('span');
    recensioni.classList.add('n-recensioni');
    recensioni.textContent = ' ' + prodotto.Valutazioni + ' recension' + (prodotto.Valutazioni == 1 ? 'e' : 'i');
    recensioni.addEventListener('click', (event) => apriModaleRecensioni(event, prodotto.EAN));
    divProdotto.appendChild(recensioni);
    const lasciaRecensione = document.createElement('span');
    lasciaRecensione.textContent = 'Scrivi una recensione';
    //se loggato associo una funzione che mostra una modale per lasciare una recensione, altrimenti una modale che ricorda di loggarsi
    lasciaRecensione.addEventListener('click', isLogged ? mostraModaleAggiungiRecensione : mostraModale);
    lasciaRecensione.id = "leave-review";
    divProdotto.appendChild(lasciaRecensione);
    const descrizione = document.createElement('span');
    descrizione.classList.add('descrizione');
    descrizione.textContent = prodotto.Descrizione;
    divProdotto.appendChild(descrizione);
    const divButtons = document.createElement('div');
    divButtons.classList.add('buttons');
    const immagineCuore = document.createElement('img');
    immagineCuore.classList.add('cuore');
    //se l'articolo è presente nella lista dei desideri mostro l'immagine per rimuoverlo, altrimenti per aggiungerlo
    immagineCuore.src = prodotto.isPreferito == true ? 'img/cuore-spezzato.png' : 'img/cuore-bianco.png';
    //Se loggato aggiungo il listener(in base ai preferiti dell'utente) altrimenti aggiungo il listener per mostrare una modale per accedere
    immagineCuore.addEventListener('click', (isLogged ? (prodotto.isPreferito == true ? onClickRimuoviPreferiti : onClickAggiungiPreferiti) : mostraModale));
    divButtons.appendChild(immagineCuore);

    const immagineCarrello = document.createElement('img');
    immagineCarrello.src = 'img/carrello-bianco.png';
    immagineCarrello.classList.add('carrello');
    //se loggato mostro la modale per aggiungere al carrello, quella per loggarsi altrimenti
    immagineCarrello.addEventListener('click', (isLogged ? (event) => mostraModaleQuantita(event, prodotto) : mostraModale))
    divButtons.appendChild(immagineCarrello);
    divProdotto.appendChild(divButtons);


}

//"Override" della funzione nel main.js e in item-prodotto.js
function onClickRimuoviDaiPreferitiNavbar(e) {
    fetch("preferiti_rimuovi.php?EAN=" + parametroEAN).then(onResponse).then(onPreferitoRimosso);

    const img = document.querySelector(".cuore");
    img.src = 'img/cuore-bianco.png';
    img.removeEventListener('click', onClickRimuoviPreferiti);
    img.addEventListener('click', onClickAggiungiPreferiti);
}


//OVERRIDE
function onClickAggiungiPreferiti(e) {
    const immagine = e.currentTarget;
    //aggiungo ai preferiti e cambio immagine e listener (in onAggiuntoPreferiti)
    fetch("preferiti_aggiungi.php?EAN=" + parametroEAN)
        .then(onResponse).then((json) => onAggiuntoPreferiti(json, immagine));

}

function onClickRimuoviPreferiti(e) {
    const immagine = e.currentTarget;
    //rimuovo dai preferiti e cambio immagine e listener (in onRimossoPreferiti)
    fetch("preferiti_rimuovi.php?EAN=" + parametroEAN)
        .then(onResponse).then((json) => onRimossoPreferiti(json, immagine));
}

//============MODALE AGGIUNGI RECENSIONE ===============
const modalViewAggiungiRecensione = document.querySelector('#modal-view-leave-review');
modalViewAggiungiRecensione.addEventListener('click', onClickModaleAggiungiRecensione);

function onClickModaleAggiungiRecensione() {
    //se clicca sulla modale la chiudo
    chiudiModaleAggiungiRecensione();
}

function onKeyAggiungiRecensione(event) {
    //se viene pressato esc
    if (event.keyCode == 27)
        chiudiModaleAggiungiRecensione();
}

function chiudiModaleAggiungiRecensione() {
    //ripristino lo scroll
    document.body.classList.remove('no-scroll');
    //nascondo la modale
    modalViewAggiungiRecensione.classList.add('hidden');
    //rimuovo il listener per il tasto esc
    document.removeEventListener('keydown', onKeyAggiungiRecensione);
}

function mostraModaleAggiungiRecensione() {
    //blocco la possibilità di scrollare la pagina
    document.body.classList.add('no-scroll');
    //sposto la modale all'altezza corretta della pagina
    modalViewAggiungiRecensione.style.top = window.pageYOffset + "px";
    //mostro la modale
    modalViewAggiungiRecensione.classList.remove('hidden');
    //aggiungo il listener per il tasto esc
    document.addEventListener('keydown', onKeyAggiungiRecensione);
    //tutti gli elementi che voglio che si possano cliccare senza che la modale venga
    const inputs = document.getElementById('clickable').childNodes;
    for (input of inputs)
        input.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    //onClick sul button invia viene aggiunta la recensione se non esiste già una recensione per quel prodotto da parte di quel cliente, aggiornata altrimenti
    document.getElementById('invia').addEventListener('click', aggiungiRecensione);
}



function aggiungiRecensione(event) {
    //seleziono il valore associato alla stellina cliccata (radio)
    const valutazione = modalViewAggiungiRecensione.querySelector("input[type='radio']:checked").value;
    const titolo = modalViewAggiungiRecensione.querySelector("input[type='text']").value;
    const recensione = modalViewAggiungiRecensione.querySelector("textarea").value;
    fetch("aggiungi_recensione.php?EAN=" + parametroEAN + "&v=" + valutazione + "&t=" + titolo + "&r=" + recensione).then(onResponse).then(onRecensioneAggiunta);
}

function onRecensioneAggiunta(json) {
    //quando la recensione viene aggiunta correttamente, ricarico il prodotto per mostrare la valutazione aggiornata
    if (json == true)
        fetch("get_prodotto.php?EAN=" + parametroEAN).then(onResponse).then(onProdottoJson);
}