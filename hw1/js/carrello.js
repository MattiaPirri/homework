fetch("get_carrello.php").then(onResponse).then(onGetCarrelloJson);

function onResponse(promise) {
    return promise.json();
}
const divProdotti = document.querySelector('#prodotti');

function onGetCarrelloJson(json) {
    document.getElementById('loader').classList.add('hidden');
    const buttonCompletaOrdine = document.getElementById('completa');
    //cancello il contenuto perchè quando rimuovo un articolo dal carrello ricarico, lo ricarico tutto
    divProdotti.innerHTML = "";
    if (json.length == 0) {
        mostraCarrelloVuoto();
    } else {
        //Per formattare il prezzo, fornito come numero decimale, in valuta
        const formatter = new Intl.NumberFormat('it-IT', {
            style: 'currency',
            currency: 'EUR'
        })
        let prezzoTotale = 0,
            peso = 0;
        for (prodotto of json) {
            const divProdotto = document.createElement('div');
            divProdotto.classList.add('prodotto');
            divProdotto.dataset.ean = prodotto.EAN;
            divProdotti.appendChild(divProdotto);
            const img = document.createElement('img');
            img.src = prodotto.src;
            divProdotto.appendChild(img);
            const aContent = document.createElement('a');
            aContent.classList.add("content");
            aContent.href = "prodotto.php?EAN=" + prodotto.EAN;
            divProdotto.appendChild(aContent);
            const marca = document.createElement('strong');
            marca.textContent = prodotto.Marca;
            aContent.appendChild(marca);
            const nome = document.createElement('span');
            nome.textContent = prodotto.Nome;
            aContent.appendChild(nome);
            const prezzo = document.createElement('span');

            prezzo.textContent = formatter.format(prodotto.Prezzo);
            aContent.appendChild(prezzo);
            const select = document.createElement('select');
            select.classList.add('quantita');
            divProdotto.appendChild(select);

            for (let i = 0; i < prodotto.Disponibili; i++) {
                const option = document.createElement('option');
                option.value = i + 1;
                option.textContent = i + 1;
                select.appendChild(option);
                if (i + 1 == prodotto.quantità)
                    option.selected = true;
            }
            select.addEventListener('change', onSelectChange);

            const spanPeso = document.createElement('span');
            spanPeso.textContent = prodotto.Peso + "Kg";
            divProdotto.appendChild(spanPeso);

            const buttonCarrello = document.createElement('img');
            buttonCarrello.classList.add('btn-carrello');
            buttonCarrello.src = 'img/svuota-carrello.png';
            buttonCarrello.addEventListener('click', onClickRimuoviDalCarrello);
            divProdotto.appendChild(buttonCarrello);

            prezzoTotale += prodotto.Prezzo * prodotto.quantità;
            peso += prodotto.Peso * prodotto.quantità;
        }
        const totale = document.querySelector('#prezzo');
        totale.textContent = "Totale: " + formatter.format(prezzoTotale);
        const totalePeso = document.querySelector('#peso');
        //arrotondo il peso alla seconda cifra decimale
        totalePeso.textContent = "Totale peso: " + Math.round(peso * 100) / 100 + " Kg";
        buttonCompletaOrdine.classList.remove('hidden');
        buttonCompletaOrdine.addEventListener('click', procediOrdine);
    }
}

function procediOrdine() {
    fetch("completa_ordine.php").then(onResponse).then(onCompletaOrdine);
}

function onCompletaOrdine(json) {
    if (json == true) {
        //redirect to ordini
        window.location.href = "ordini.php";
    } else {
        //mostra messaggio
        alert(json);
    }
}

function onSelectChange(e) {
    fetch("carrello_aggiungi.php?EAN=" + e.currentTarget.parentNode.dataset.ean + "&q=" + e.currentTarget.value).then(onResponse).then(onAggiuntoAlCArrello);
}
//viene richiamato quando viene modificata la quantità
function onAggiuntoAlCArrello(json) {
    if (json) {
        //aggiorna la navbar
        fetch("get_user_info.php").then(onResponse).then(onNumeriJson);
        fetch("get_carrello.php").then(onResponse).then(onGetCarrelloJson);
    }
}

function onClickRimuoviDalCarrello(e) {
    fetch("carrello_rimuovi.php?EAN=" + e.currentTarget.parentNode.dataset.ean).then(onResponse).then(onCarrelloRimosso);
    e.currentTarget.parentNode.remove();
    if (divProdotti.childNodes.length == 0) mostraCarrelloVuoto();
}


//OVERRIDE
function onClickRimuoviDalCarrelloNavbar(e) {
    fetch("carrello_rimuovi.php?EAN=" + e.currentTarget.parentNode.dataset.ean).then(onResponse).then(onCarrelloRimosso);
    //cerco il prodotto con l'ean corretto
    const prodotto = divProdotti.querySelector(".prodotto[data-ean=\"" + e.currentTarget.parentNode.dataset.ean + "\"]");
    prodotto.remove();
    if (divProdotti.childNodes.length == 0) mostraCarrelloVuoto();
}

function mostraCarrelloVuoto() {
    divProdotti.appendChild(document.createTextNode("Il tuo carrello è vuoto!"));
    divProdotti.appendChild(document.createElement('br'));
    const imgCarrelloVuoto = document.createElement('img');
    imgCarrelloVuoto.src = "img/empty_cart.svg";
    imgCarrelloVuoto.id = "carrello-vuoto";
    divProdotti.appendChild(imgCarrelloVuoto);
    const totale = document.querySelector('#prezzo');
    totale.textContent = "";
    const totalePeso = document.querySelector('#peso');
    totalePeso.textContent = "";
    const buttonCompletaOrdine = document.getElementById('completa');
    buttonCompletaOrdine.classList.add("hidden");
}