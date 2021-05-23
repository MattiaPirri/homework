//Importare questo file in tutte le pagine che contengono la navbar
const searchBar = document.querySelector(".termini-ricerca");
//effettuo la ricerca ad ogni lettera inserita
searchBar.addEventListener('keyup', search);
//Nascondo i risultati quando si clicca su qualche altro elemento
searchBar.addEventListener('blur', searchBlur);
//quando l'utente clicca sulla barra ne cancello il vecchio contenuto
searchBar.addEventListener('click', onClickBar);
//Mostro i risultati quando si clicca su qualche altro elemento
searchBar.addEventListener('focus', searchFocus);
const searchResults = document.querySelector(".search-results");

//L'evento blur mi dava problemi quando cliccavo su un risultato, difatto nascondendoli e non permettendomi di cliccarlo. L'evento 'mousedown' viene scatenato prima di 'blur' 
//quando clicco su un risultato fermo la propagazione dell'evento così posso andare alla pagina del prodotto
searchResults.addEventListener('mousedown', onMouseDown);

//se si clicca sull'icona della lente si viene reindirizzati alla pagina dedicata a tutti i risultati
const searchButton = document.querySelector(".search-button");
searchButton.addEventListener('click', searchAll);

//Mi serve a cancellare quando torno indietro da un'altra pagina
function onClickBar() {
    searchBar.value = "";
}
//Così funziona
function onMouseDown(event) {
    event.preventDefault();
}
//se non ci sono risultati non permetto di andare alla pagina dei risultati
let noResults = false;

function searchAll() {
    if (searchBar.value && !noResults)
        window.location.href = "search_results.php?q=" + searchBar.value;
}

function search(event) {
    //se viene premuto invio reindirizzo alla pagina con i risultati
    if (event.keyCode === 13)
        if (!noResults)
            searchAll();
        //se viene premuto esc svuoto i risulati
    if (event.keyCode === 27) {
        svuotaRisultati();
        //per qualsiasi altro tasto premuto
    } else if (searchBar.value)
        fetch("search.php?q=" + searchBar.value).then(onResponse).then(onJsonSearch);
    else svuotaRisultati();
}

function onResponse(promise) {
    return promise.json();
}

function onJsonSearch(json) {
    //quando ottengo i risultati cancello quelli vecchi
    svuotaRisultati();
    //se ci sono risultati
    if (json.length) {
        noResults = false;
        for (const element of json) //li aggiungo al menu a tendina
            aggiungiRisultato(element);
    } else mostraMessaggio(); //altrimenti mostro che non ci sono risultati
}

function mostraMessaggio() {
    noResults = true;
    const divItem = document.createElement('div');
    divItem.classList.add('item');
    const content = document.createElement('div');
    content.classList.add('content');
    content.classList.add('void');
    content.textContent = "Nessun risultato";
    divItem.appendChild(content);
    searchResults.appendChild(divItem);
}

function svuotaRisultati() {
    //cancella il contenuto del menu a tendina
    searchResults.innerHTML = "";
}

function aggiungiRisultato(e) {
    /*
         Nella seguente forma
            <a class="item">
                <div>
                    Marca Nome
                </div>
            </a>
    */
    const item = document.createElement('a');
    item.classList.add('item');
    item.href = "prodotto.php?EAN=" + e.EAN;
    const inner = document.createElement('div');
    let testo = inner.textContent = e.Marca + " " + e.Nome;
    //Voglio mostrare in grassetto tutte le occorrenze dei termini di ricerca nei risultati
    let index, inizio = 0;
    //Finquando trovo la stringa nel risultato a partire dall'indice precedente
    const termini = searchBar.value.toLowerCase();
    while ((index = testo.toLowerCase().indexOf(termini, inizio)) > -1) {
        //il prossimo ciclo inizio a cercare dall'inizio precedente + la lunghezza della stringa che sto cercando
        inizio = index + termini.length;
        const aperturaSpan = "<span class='highlight'>";
        const chiusuraSpan = "</span>";
        //aggiungo uno span per evidenziare l'occorrenza trovata
        testo = testo.substring(0, index) + aperturaSpan + testo.substring(index, index + termini.length) + chiusuraSpan + testo.substring(index + termini.length);
        //aggiungo all'indice per la prossima ricerca la lunghezza dello span che ho inserito
        inizio += aperturaSpan.length + chiusuraSpan.length;
    }
    inner.innerHTML = testo;
    item.appendChild(inner);
    searchResults.appendChild(item);
}

function searchBlur() {
    //quando esco dal campo di input nascondo i risultati
    searchResults.classList.add('hidden');
}

function searchFocus() {
    //quando entro nel campo di input mostro i risultati
    searchResults.classList.remove('hidden');
}