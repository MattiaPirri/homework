function creaProdotto(content) {
    const sezione = document.querySelector('#prodotti')
        //Creo la struttura voluta
        /*
        <div class="item">
            <a href="">
                <img src="https://percorso_immgaine.jpg">
                <span class="content">
                    <strong>Marca qui</strong>
                    <span> - </span>
                    <span class="nome">Nome prodotto qui</span>
                </span><br>
                <span class="stelle">★★★★☆</span>   
                <span> Numero recensioni qui</span><br>
                <span>Prezzo qui</span>
            </a>
            <span class="dettagli">Mostra maggiori dettagli</span>
            <span class="descrizione hidden">Descrizione qui</span>
            <div class="overlay">
                <img class="cuore" src="img/cuore-spezzato.png">
                <img src="img/carrello-bianco.png" class="carrello">
            </div>
        </div>
        */
    const item = document.createElement('div');
    item.classList.add('item');
    item.dataset.ean = content.EAN;
    sezione.appendChild(item);
    const a = document.createElement('a');
    a.href = 'prodotto.php?EAN=' + content.EAN;
    item.appendChild(a);
    const dettagli = document.createElement('span');
    dettagli.textContent = 'Mostra maggiori dettagli';
    dettagli.classList.add('dettagli');
    dettagli.addEventListener('click', onClickDettagli);
    item.appendChild(dettagli);
    const immagine = document.createElement('img');
    immagine.src = content.src;
    a.appendChild(immagine);
    const descrizione = document.createElement('span');
    descrizione.textContent = content.Descrizione;
    descrizione.classList.add('descrizione');
    descrizione.classList.add('hidden');
    item.appendChild(descrizione);
    const contenuto = document.createElement('span');
    contenuto.classList.add('content');
    a.appendChild(contenuto);
    const marca = document.createElement('strong');
    marca.textContent = content.Marca;
    contenuto.appendChild(marca);
    const nome = document.createElement('span');
    nome.classList.add('nome');
    nome.textContent = " " + content.Nome;
    contenuto.appendChild(nome);
    a.appendChild(document.createElement('br'));
    const stelle = document.createElement('span');
    stelle.classList.add('stelle');
    let textStelle = '';
    for (let i = 0; i < Math.floor(content.Valutazione); i++)
        textStelle += "<i class=\"fas fa-star\"></i>";
    if (content.Valutazione % 1)
        textStelle += "<i class=\"fas fa-star-half-alt\"></i>";
    for (let i = 0; i < 5 - Math.ceil(content.Valutazione); i++)
        textStelle += "<i class=\"far fa-star\"></i>";
    stelle.innerHTML = textStelle + "(" + Math.round(content.Valutazione * 10) / 10 + ")";
    stelle.addEventListener('click', (event) => apriModaleRecensioni(event, content.EAN));
    item.appendChild(stelle);
    const recensioni = document.createElement('span');
    recensioni.classList.add('n-recensioni');
    recensioni.textContent = ' ' + content.Valutazioni + ' recension' + (content.Valutazioni == 1 ? 'e' : 'i');
    recensioni.addEventListener('click', (event) => apriModaleRecensioni(event, content.EAN));
    item.appendChild(recensioni);
    //a.appendChild(document.createElement('br'));
    const prezzo = document.createElement('span');
    //Per formattare il prezzo, fornito come numero decimale, in valuta
    const formatter = new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    })
    prezzo.textContent = formatter.format(content.Prezzo);
    a.appendChild(prezzo);
    const overlay = document.createElement('div');
    overlay.classList.add('overlay');
    item.appendChild(overlay);

    const immagineCuore = document.createElement('img');
    immagineCuore.classList.add('cuore');
    immagineCuore.src = content.isPreferito == true ? 'img/cuore-spezzato.png' : 'img/cuore-bianco.png';
    //Se loggato aggiungo il listener(in base ai preferiti dell'utente) altrimenti aggiungo il listener per mostrare una modale per accedere
    immagineCuore.addEventListener('click', (isLogged ? (content.isPreferito == true ? onClickRimuoviPreferiti : onClickAggiungiPreferiti) : mostraModale));
    overlay.appendChild(immagineCuore);

    const immagineCarrello = document.createElement('img');
    immagineCarrello.src = 'img/carrello-bianco.png';
    immagineCarrello.classList.add('carrello');
    immagineCarrello.addEventListener('click', (isLogged ? (event) => mostraModaleQuantita(event, content) : mostraModale))
    overlay.appendChild(immagineCarrello);
}



//===============MODALE LOGIN=================
const modalView = document.querySelector('#modal-view');
modalView.addEventListener('click', onClickModale);

function onClickModale() {
    chiudiModale();
}

function onKey(event) {
    //tasto esc
    if (event.keyCode == 27)
        chiudiModale();
}

function chiudiModale() {
    document.body.classList.remove('no-scroll');
    modalView.classList.add('hidden');
    document.removeEventListener('keydown', onKey);
}

function mostraModale() {
    document.body.classList.add('no-scroll');
    modalView.style.top = window.pageYOffset + "px";
    modalView.classList.remove('hidden');
    document.addEventListener('keydown', onKey);
}

//============MODALE RECENSIONI==========
const modalViewRecensioni = document.querySelector('#modal-view-recensioni');
modalViewRecensioni.addEventListener('click', onClickModaleRecensioni);

function apriModaleRecensioni(e, EAN) {
    document.body.classList.add('no-scroll');
    modalViewRecensioni.style.top = window.pageYOffset + "px";
    modalViewRecensioni.classList.remove('hidden');
    document.addEventListener('keydown', onKeyRecensioni);
    fetch("get_recensioni.php?EAN=" + EAN).then(onResponse).then(onRecensioni);
}

function onRecensioni(json) {
    const divRecensioni = document.getElementById('div-recensioni');
    divRecensioni.innerHTML = "";
    if (json.length == 0) {
        divRecensioni.appendChild(document.createTextNode("Non ci sono recensioni per questo prodotto"));
    } else
        for (recensione of json) {
            const divRecensione = document.createElement('div');

            const stelle = document.createElement('span');
            stelle.classList.add('stelle');
            let textStelle = '';
            for (let i = 0; i < Math.floor(recensione.Stelle); i++)
                textStelle += "<i class=\"fas fa-star\"></i>";
            if (recensione.Stelle % 1)
                textStelle += "<i class=\"fas fa-star-half-alt\"></i>";
            for (let i = 0; i < 5 - Math.ceil(recensione.Stelle); i++)
                textStelle += "<i class=\"far fa-star\"></i>";
            stelle.innerHTML = textStelle + "(" + Math.round(recensione.Stelle * 10) / 10 + ")";
            divRecensione.appendChild(stelle);
            const nome = document.createElement('span');
            nome.textContent = " " + recensione.NomeCliente;
            divRecensione.appendChild(nome);
            if (recensione.isVerificato == true) {
                const verificato = document.createElement('span');
                verificato.classList.add("verificato");
                verificato.innerHTML = "<br><i class=\"fas fa-check\"></i> Acquisto verificato";
                divRecensione.appendChild(verificato);
            }
            divRecensione.appendChild(document.createElement('br'));
            const titoloRecensione = document.createElement('strong');
            titoloRecensione.classList.add('titolo-recensione');
            titoloRecensione.textContent = recensione.Titolo;
            divRecensione.appendChild(titoloRecensione);
            divRecensione.appendChild(document.createElement('br'));
            const descrizioneRecensione = document.createElement('span');
            descrizioneRecensione.classList.add('descrizione-recensione');
            descrizioneRecensione.textContent = recensione.Descrizione;
            divRecensione.appendChild(descrizioneRecensione);
            divRecensioni.appendChild(divRecensione);
        }
}


function onKeyRecensioni(event) {
    //esc
    if (event.keyCode == 27)
        chiudiModaleRecensioni();
}

function chiudiModaleRecensioni() {
    document.body.classList.remove('no-scroll');
    modalViewRecensioni.classList.add('hidden');
    document.removeEventListener('keydown', onKey);
}

function onClickModaleRecensioni() {
    chiudiModaleRecensioni();
}




//================MODALE QUANTITÀ================
const modalViewQuantita = document.querySelector('#modal-view-quantita');
modalViewQuantita.addEventListener('click', onClickModaleQuantita);

function onClickModaleQuantita() {
    chiudiModaleQuantita();
}

function onKeyQuantita(event) {
    if (event.keyCode == 27)
        chiudiModaleQuantita();
}

function chiudiModaleQuantita() {
    document.body.classList.remove('no-scroll');
    modalViewQuantita.classList.add('hidden');
    document.removeEventListener('keydown', onKey);
}

function mostraModaleQuantita(event, p) {
    document.body.classList.add('no-scroll');
    modalViewQuantita.style.top = window.pageYOffset + "px";
    const prodotto = modalViewQuantita.querySelector('#prodotto');
    prodotto.innerHTML = "";
    const content = document.createElement('span');
    prodotto.appendChild(content);
    const marcaStrong = document.createElement('strong');
    marcaStrong.textContent = p.Marca;
    content.appendChild(marcaStrong);
    content.appendChild(document.createTextNode(" " + p.Nome));
    const img = document.createElement('img');
    img.src = p.src;
    prodotto.appendChild(img);
    const select = document.getElementById('quantita');
    select.innerHTML = "";
    const trattino = document.createElement('option');
    trattino.textContent = "-";
    trattino.disabled = true;
    trattino.selected = true;
    select.appendChild(trattino);
    select.dataset.ean = p.EAN;
    if (p.Disponibili == 0) {
        modalViewQuantita.querySelector(".non-disponibile").classList.remove('hidden');
    } else {
        modalViewQuantita.querySelector(".non-disponibile").classList.add('hidden');
        for (let i = 0; i <= p.Disponibili; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            select.appendChild(option);
        }
    }
    modalViewQuantita.classList.remove('hidden');
    document.addEventListener('keydown', onKeyQuantita);
}

const selectQuantita = document.querySelector('#quantita');
selectQuantita.addEventListener('click', onSelectQuantita);
selectQuantita.addEventListener('change', onSelectChange);

function onSelectQuantita(event) {
    event.stopPropagation();
}


function onSelectChange(event) {
    event.preventDefault();
    if (selectQuantita.value == 0) {
        //fetch("carrello_aggiungi.php?EAN=" + selectQuantita.dataset.ean + "&q=" + selectQuantita.value).then(onResponse).then(onAggiuntoAlCArrello);
        const conferma = confirm("Confermi di voler rimuovere il prodotto dal carrello?");
        if (conferma)
            fetch("carrello_rimuovi.php?EAN=" + selectQuantita.dataset.ean).then(onResponse).then(onRimossoCarrello);

    } else
        fetch("carrello_aggiungi.php?EAN=" + selectQuantita.dataset.ean + "&q=" + selectQuantita.value).then(onResponse).then(onAggiuntoAlCArrello);
    chiudiModaleQuantita();
}

function onAggiuntoAlCArrello(json) {
    if (json)
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
}

function onRimossoCarrello(json) {
    if (json)
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
}

function onClickAggiungiPreferiti(e) {
    const immagine = e.currentTarget;
    fetch("preferiti_aggiungi.php?EAN=" + e.currentTarget.parentNode.parentNode.dataset.ean)
        .then(onResponse).then((json) => onAggiuntoPreferiti(json, immagine));

}


function onAggiuntoPreferiti(json, image) {
    if (json) {
        image.src = 'img/cuore-spezzato.png';
        image.removeEventListener('click', onClickAggiungiPreferiti);
        image.addEventListener('click', onClickRimuoviPreferiti);
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
    }
}

function onClickRimuoviPreferiti(e) {
    const immagine = e.currentTarget;
    fetch("preferiti_rimuovi.php?EAN=" + e.currentTarget.parentNode.parentNode.dataset.ean)
        .then(onResponse).then((json) => onRimossoPreferiti(json, immagine));
}
//image = false quando la richiamo senza parametro dalla pagina lista dei desideri, perché non devo cambiare l'immagine ma rimuovere l'intero item
function onRimossoPreferiti(json, image = false) {
    if (json) {
        if (image) {
            image.src = 'img/cuore-bianco.png';
            image.removeEventListener('click', onClickRimuoviPreferiti);
            image.addEventListener('click', onClickAggiungiPreferiti);
        }
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
    }
}
//"Override" della funzione nel main.js
function onClickRimuoviDaiPreferitiNavbar(e) {
    const ean = e.currentTarget.parentNode.dataset.ean;
    fetch("preferiti_rimuovi.php?EAN=" + ean).then(onResponse).then(onPreferitoRimosso);
    //cambio il listener nella sezione evidenza
    //#prodotti .item[data-ean="353153151412"]
    const item = document.querySelector("#prodotti .item[data-ean=\"" + ean + "\"]");
    if (item) {
        const img = item.querySelector(".cuore");
        img.src = 'img/cuore-bianco.png';
        img.removeEventListener('click', onClickRimuoviPreferiti);
        img.addEventListener('click', onClickAggiungiPreferiti);
    }
}


//===============MOSTRA E NASCONDI DESCRIZIONE =================
function onClickDettagliNascondi(e) {
    const target = e.currentTarget;
    target.textContent = 'Mostra maggiori dettagli';
    target.parentNode.querySelector('.descrizione').classList.add('hidden');
    target.removeEventListener('click', onClickDettagliNascondi);
    target.addEventListener('click', onClickDettagli);
}

function onClickDettagli(e) {
    const target = e.currentTarget;
    target.textContent = 'Nascondi dettagli';
    target.parentNode.querySelector('.descrizione').classList.remove('hidden');
    target.removeEventListener('click', onClickDettagli);
    target.addEventListener('click', onClickDettagliNascondi);
}