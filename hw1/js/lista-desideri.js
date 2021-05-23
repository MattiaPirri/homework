let isLogged;
fetch('is_logged.php').then(onResponse).then(onLoggedJson);

function onResponse(promise) {
    return promise.json();
}

function onLoggedJson(json) {
    isLogged = json;
    fetch('get_preferiti.php').then(onResponse).then(onPreferiti);
}

function onPreferiti(json) {
    document.getElementById('loader').classList.add('hidden');
    if (json.length)
        for (const content of json) {
            creaProdotto(content, isLogged);
        }
    else mostraMessaggioListaVuota();
}


//"Override" della funzione nel main.js e in item-prodotto.js
function onClickRimuoviDaiPreferitiNavbar(e) {
    const ean = e.currentTarget.parentNode.dataset.ean;
    fetch("preferiti_rimuovi.php?EAN=" + ean).then(onResponse).then(onPreferitoRimosso);
    //elimino il prodotto dalla sezione
    //#prodotti .item[data-ean="353153151412"]
    const item = document.querySelector("#prodotti .item[data-ean=\"" + ean + "\"]");
    item.remove();
    if (document.querySelector('#prodotti').childNodes.length == 0)
        mostraMessaggioListaVuota();
}
//"Override" della funzione in item-prodotto.js
function onClickRimuoviPreferiti(e) {
    fetch("preferiti_rimuovi.php?EAN=" + e.currentTarget.parentNode.parentNode.dataset.ean)
        .then(onResponse).then(onRimossoPreferiti);
    e.currentTarget.parentNode.parentNode.remove();
    if (document.querySelector('#prodotti').childNodes.length == 0)
        mostraMessaggioListaVuota();
}

function mostraMessaggioListaVuota() {
    const articolo = document.querySelector('article');
    articolo.appendChild(document.createTextNode("La tua lista dei desideri è vuota!"));
    articolo.appendChild(document.createElement('br'));
    const imgVuota = document.createElement('img');
    imgVuota.classList.add('wishlist-vuota');
    imgVuota.src = "img/wishlist-vuota.svg"
    articolo.appendChild(imgVuota);
}