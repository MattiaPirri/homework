fetch("get_ordini.php").then(onResponse).then(onOrdiniJson);

function onResponse(promise) {
    return promise.json();
}

function onOrdiniJson(json) {
    //quando ricevo gli ordini li costruisco e li mostro
    document.getElementById('loader').classList.add('hidden');
    const orders = document.getElementById("orders");
    //cancello i vecchi ordini nel caso un ordine venga annullato (vengono ricaricati tutti gli ordini)
    orders.innerHTML = "";
    if (json.length == 0) {
        mostraOrdiniVuoto();
    } else
        for (ordine of json) {
            const order = document.createElement('div');
            order.classList.add("item");
            //questo dataset viene letto per ottenere tutti gli articoli dell'ordine quando si clicca su uno di essi
            order.dataset.id = ordine.Id;
            order.addEventListener('click', getOrdine);
            orders.appendChild(order);
            const idOrdine = document.createElement('span');
            idOrdine.textContent = ordine.Id;
            order.appendChild(idOrdine);
            const stato = document.createElement('span');
            stato.textContent = ordine.Stato + " ";
            if (ordine.Stato == "Confermato") {

                const annulla = document.createElement('a');
                annulla.addEventListener('click', annullaOrdine);
                annulla.textContent = "(Annulla)";
                annulla.dataset.idOrdine = ordine.Id;
                stato.appendChild(annulla);

            }
            order.appendChild(stato);
            const dataOrdine = document.createElement('span');
            dataOrdine.textContent = ordine.Data;
            order.appendChild(dataOrdine);
        }
}

function getOrdine(e) {
    const ordine = e.currentTarget;
    ordine.removeEventListener('click', getOrdine);
    ordine.addEventListener('click', chiudiOrdine);
    fetch("get_ordine.php?id=" + ordine.dataset.id).then(onResponse).then((json) => onOrdineJson(json, ordine));
}

function onOrdineJson(json, order) {
    //costruisco una tabella contenente tutti gli articoli e il totale dell'ordine
    const divDettagliOrdine = document.createElement('div');
    const tabella = document.createElement('table');
    const intestazioneMarca = document.createElement('th');
    intestazioneMarca.textContent = "Marca";
    tabella.appendChild(intestazioneMarca);
    const intestazioneNome = document.createElement('th');
    intestazioneNome.textContent = "Nome";
    tabella.appendChild(intestazioneNome);
    const intestazioneQuantità = document.createElement('th');
    intestazioneQuantità.textContent = "Quantità";
    tabella.appendChild(intestazioneQuantità);
    const intestazionePrezzo = document.createElement('th');
    intestazionePrezzo.textContent = "Prezzo";
    tabella.appendChild(intestazionePrezzo);
    const intestazioneTotale = document.createElement('th');
    intestazioneTotale.textContent = "Totale";
    tabella.appendChild(intestazioneTotale);
    divDettagliOrdine.appendChild(tabella);
    order.parentNode.insertBefore(divDettagliOrdine, order.nextSibling);
    const formatter = new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    })
    let totale = 0;
    const tBody = document.createElement('tbody');
    for (prodotto of json) {
        const riga = document.createElement('tr');
        riga.addEventListener('click', (event) => apriProdotto(event, prodotto.EAN));
        tBody.appendChild(riga);
        const marca = document.createElement('td');
        marca.textContent = prodotto.Marca;
        riga.appendChild(marca);
        const nome = document.createElement('td');
        nome.textContent = prodotto.Nome;
        riga.appendChild(nome);
        const quantità = document.createElement('td');
        quantità.textContent = prodotto.Quantità;
        riga.appendChild(quantità);
        const prezzo = document.createElement('td');
        prezzo.textContent = formatter.format(prodotto.Prezzo);
        riga.appendChild(prezzo);
        const prezzoTotale = document.createElement('td');
        prezzoTotale.textContent = formatter.format(prodotto.Prezzo * prodotto.Quantità);
        riga.appendChild(prezzoTotale);
        totale += prodotto.Prezzo * prodotto.Quantità;

    }
    tabella.appendChild(tBody);
    tabella.addEventListener('click', rimuoviTabella);

    const totaleTabella = document.createElement('tr');
    const cellaTotale = document.createElement('td');
    cellaTotale.textContent = "TOTALE";
    totaleTabella.appendChild(cellaTotale);

    for (let i = 0; i < 3; i++) {
        const cellaVuota = document.createElement('td');
        cellaVuota.textContent = "";
        totaleTabella.appendChild(cellaVuota);
    }
    const cellaTotalePrezzo = document.createElement('td');
    cellaTotalePrezzo.textContent = formatter.format(totale);
    totaleTabella.appendChild(cellaTotalePrezzo);
    const rigaTotale = document.createElement('tfoot');
    tabella.appendChild(rigaTotale);
    rigaTotale.appendChild(totaleTabella);
}

function annullaOrdine(e) {
    //quando clicco su annulla in un ordine confermato
    //fermo la propagazione dell'evento, altrimenti mi apre l'ordine
    e.stopPropagation();
    fetch("annulla_ordine.php?id=" + e.currentTarget.dataset.idOrdine).then(onResponse).then(onAnnullatoJson);
}

function onAnnullatoJson(json) {
    //se annullato correttamente, riaggiorno tutti gli ordini
    if (json == true)
        fetch("get_ordini.php").then(onResponse).then(onOrdiniJson);
}

function apriProdotto(event, ean) {
    //link per andare alla pagina del prodotto
    event.stopPropagation();
    document.location = "prodotto.php?EAN=" + ean;
}

function chiudiOrdine(e) {
    //quando clicco su un ordine aperto, cancello la tabella con i dettagli
    e.currentTarget.nextSibling.remove();
    e.currentTarget.removeEventListener('click', chiudiOrdine);
    e.currentTarget.addEventListener('click', getOrdine);
}

function rimuoviTabella(e) {
    //quando clicco sulla tabella la rimuovo e cambio il listener all'ordine
    e.currentTarget.parentNode.previousSibling.removeEventListener('click', chiudiOrdine);
    e.currentTarget.parentNode.previousSibling.addEventListener('click', getOrdine);
    e.currentTarget.remove();

}

function mostraOrdiniVuoto() {
    const orders = document.getElementById("orders");
    orders.appendChild(document.createTextNode("Non hai ancora effettuato nessun ordine!"));
    orders.appendChild(document.createElement('br'));
    const imgOrdiniVuoto = document.createElement('img');
    imgOrdiniVuoto.src = "img/ordini_vuoto.svg";
    imgOrdiniVuoto.id = "ordini-vuoto";
    orders.appendChild(imgOrdiniVuoto);
}