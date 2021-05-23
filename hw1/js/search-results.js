//prendo il parametro della query dal'url (GET)
const urlParams = new URLSearchParams(window.location.search);
const query = urlParams.get('q');
let isLogged;
fetch('is_logged.php').then(onResponse).then(onLoggedJson);

function onResponse(promise) {
    return promise.json();
}

function onLoggedJson(json) {
    isLogged = json;
    fetch('get_search_results.php?q=' + query).then(onResponse).then(onSearchResults);
}
let pages;
let page = 1;
const loadMore = document.getElementById("load-more");
const loaderSpinner = document.getElementById('loader');

function onSearchResults(json) {
    loaderSpinner.classList.add('hidden');
    //la prima fetch dei risulatati mi ritorna il numero di pagine e lo memorizzo in pages
    if (json.pages) pages = json.pages;
    //se ci sono ulteriori pagine da caricare, mostro la scritta per notificarlo all'utente
    if (page < pages) {
        loadMore.classList.remove('hidden');
    } else {
        loadMore.classList.add('hidden');
    }
    //mostro i risultati
    for (const content of json.results) {
        creaProdotto(content, isLogged);
    }

}
//aggiungo un listener dell'evento scroll per verificare quando l'utente raggiunge la fine della pagina e caricare i risultati mancanti
window.addEventListener('scroll', caricaAltriRisultati, false);

function caricaAltriRisultati() {
    //se la fine della pagina è raggiunta
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight)
        if (page < pages) { //se ci sono ancora pagine da caricare
            //nascondo la scritta che dice di scorrere per caricare altri risultati
            loadMore.classList.add('hidden');
            //mostro il loader
            loaderSpinner.classList.remove('hidden');
            //richiedo un'altra pagina di risultati
            fetch('get_search_results.php?q=' + query + "&page=" + page++).then(onResponse).then(onSearchResults);
        }
}