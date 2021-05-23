let isLogged;
//effettuo una richiesta per sapere se vi è un utente loggato
fetch('is_logged.php').then(onResponse).then(onLoggedJson);

function onResponse(promise) {
    return promise.json();
}

function onLoggedJson(json) {
    //memorizzo se l'utente e loggato o meno e effettuo la fetch dei bestselelr
    isLogged = json;
    fetch('get_bestseller.php').then(onResponse).then(onBestseller);
}

function onBestseller(json) {
    //appena arrivano i risultati nascondo il loader
    document.getElementById('loader').classList.add('hidden');
    for (const content of json) {
        //funzione in item-prodotto.js che crea il prodotto e lo appende nella sezione con id prodotti
        //isLoggede serve per mostrare la modale per permettere di loggarsi o meno
        creaProdotto(content, isLogged);
    }
}