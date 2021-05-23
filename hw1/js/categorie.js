//prendo il parametro dall'url
const urlParams = new URLSearchParams(window.location.search);
const parametroCategoria = urlParams.get('c');

let isLogged;
//se è settato il parametro richiedo la categoria
if (parametroCategoria) {
    document.getElementById('loader-categorie').classList.add('hidden');
    fetch('is_logged.php').then(onResponse).then(onLoggedJson);
} else {
    //altrimentri richiedo tutte le categorie
    fetch("get_categorie.php").then(onResponse).then(onCategorieJson);
}

function onResponse(promise) {
    return promise.json();
}

function onCategorieJson(json) {
    //creo e appendo tutte le categorie
    document.getElementById('loader-categorie').classList.add('hidden');
    document.getElementById('loader-categoria').classList.add('hidden');
    document.getElementById('loader-more-categoria').classList.add('hidden');
    const titleCategories = document.getElementById("title-categories");
    titleCategories.classList.remove('hidden');
    const sectionCategorie = document.getElementById("categories");
    for (const categoria of json) {
        const divCategoria = document.createElement('div');
        divCategoria.classList.add('item');
        const a = document.createElement('a');
        a.href = "categorie.php?c=" + categoria.Codice;
        divCategoria.appendChild(a);
        const img = document.createElement('img');
        img.src = "img/categories/" + categoria.Codice + ".jpeg";
        a.appendChild(img);
        const div = document.createElement('div');
        a.appendChild(div);
        const strong = document.createElement('strong');
        strong.textContent = categoria.Nome;
        div.appendChild(strong);
        div.appendChild(document.createElement("br"));
        const descrizione = document.createElement('span');
        descrizione.textContent = categoria.Descrizione;
        descrizione.classList.add('descrizione');
        div.appendChild(descrizione);
        div.appendChild(document.createElement("br"));
        const span = document.createElement('span');
        span.textContent = categoria.n_articoli + " articol" + (categoria.n_articoli == 1 ? "o" : "i");
        div.appendChild(span);
        sectionCategorie.appendChild(divCategoria);
    }
}

function onLoggedJson(json) {
    isLogged = json;
    fetch("get_categoria.php?c=" + parametroCategoria).then(onResponse).then(onCategoriaJson);
}
let pages;
let page = 1;
const loadMore = document.getElementById("load-more");

function onCategoriaJson(json) {
    if (json.pages) pages = json.pages;
    document.getElementById('loader-more-categoria').classList.add('hidden');
    if (page < pages) {
        loadMore.classList.remove('hidden');
    } else {
        loadMore.classList.add('hidden');
    }
    //se è la prima pagina creo l'intestazione
    if (page == 1) {
        document.getElementById('loader-categoria').classList.add('hidden');
        const categoria = json.results[0];
        //Cambio il titolo alla pagina in base alla categoria
        document.title = "Pirri Shop - " + categoria.categoria;
        //Intestazione pagina categoria
        const titoloCategoria = document.getElementById('title-categoria');
        const img = document.createElement('img');
        img.src = "img/categories/" + categoria.CodiceCategoria + ".jpeg";
        titoloCategoria.appendChild(img);
        const div = document.createElement('div');
        titoloCategoria.appendChild(div);
        const strong = document.createElement('strong');
        strong.textContent = categoria.categoria;
        div.appendChild(strong);
        div.appendChild(document.createElement("br"));
        const descrizione = document.createElement('span');
        descrizione.textContent = categoria.descrizioneCategoria;
        descrizione.classList.add('descrizione');
        div.appendChild(descrizione);
        div.appendChild(document.createElement("br"));
        const span = document.createElement('span');
        span.textContent = json.results.length + " articol" + (json.results.length == 1 ? "o" : "i");
        div.appendChild(span);
    }
    const sectionCategoria = document.getElementById('section-categoria');
    //aggiungo i risultati alla pagina
    for (const content of json.results) {
        creaProdotto(content, isLogged);
    }
}


//aggiungo un listener dell'evento scroll per verificare quando l'utente raggiunge la fine della pagina e caricare i risultati mancanti
window.addEventListener('scroll', caricaAltriRisultati, false);

function caricaAltriRisultati() {
    //se sono arrivato alla fine della pagina
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight)
        if (page < pages) { //e ci sono ancora pagine di prodotti da caricare
            loadMore.classList.add('hidden');
            document.getElementById('loader-more-categoria').classList.remove('hidden');
            //li richiedo
            fetch("get_categoria.php?c=" + parametroCategoria + "&page=" + page++).then(onResponse).then(onCategoriaJson);
        }
}