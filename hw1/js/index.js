//SEZIONE CATEGORIE
const categorie = document.querySelector('#cat');
//richiedo tutte le categorie
fetch("get_categorie.php").then(onResponse).then(onCategorieJson);

function onResponse(promise) {
    return promise.json();
}

function onCategorieJson(json) {
    //appena arrivano le categorie le costruisco e le mostro
    document.getElementById("loader-categorie").classList.add('hidden');
    for (categoria of json) {
        /*
                <!--
                <div class="item">
                    <a href="abbligliamento.html">
                        <img src="img/categories/1.png">
                        <div>
                            <strong>Abbigliamento</strong>
                            <span>7 articoli</span>
                        </div>
                    </a>
                </div>
                -->    
        */
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
        const span = document.createElement('span');
        span.textContent = categoria.n_articoli + " articol" + (categoria.n_articoli == 1 ? "o" : "i");
        div.appendChild(span);
        categorie.appendChild(divCategoria);
    }
}

//SEZIONE ARTICOLI IN EVIDENZA
fetch('is_logged.php').then(onResponse).then(onLoggedJson);
const sezioneEvidenza = document.querySelector('#prodotti');
let isLogged

function onLoggedJson(json) {
    isLogged = json;
    //richiedo i prodotti in evidenza (9 prodotti random)
    fetch("get_prodotti_evidenza.php").then(onResponse).then(onEvidenzaJson);
}


function onEvidenzaJson(json) {
    document.getElementById("loader-prodotti").classList.add('hidden');
    for (const content of json) {
        creaProdotto(content, isLogged);
    }
}


//==========RICERCA TRA I PRODOTTI IN EVIDENZA==========
//Immagine nessun risultato ricerca tra i prodotti in evidenza
const immagine = document.createElement('img');
immagine.src = 'img/non-trovato.svg';
sezioneEvidenza.appendChild(immagine);
immagine.classList.add('hidden');
immagine.id = 'non-trovato';


//RICERCA EVIDENZA
const inputEvidenza = document.getElementById('input-evidenza');
inputEvidenza.addEventListener('keyup', onKeyup);

function onKeyup(e) {
    let numeroRisultati = 0;
    //Seleziono tutti i prodotti
    const items = document.querySelectorAll("#prodotti .item");
    for (let i = 0; i < items.length; i++) {
        //Content contiene marca e modello
        const s = items[i].querySelector('.content').textContent;
        //Se corrisponde
        if (s.toLowerCase().indexOf(e.currentTarget.value.toLowerCase()) !== -1) {
            //lo mostro
            items[i].classList.remove('hidden');
            //mi serve a rimuovere l'immagine che comunica che non ci sono risultati
            numeroRisultati++;
        } else items[i].classList.add('hidden'); //Se non corrisponde con la query di ricerca lo nascondo
    }
    //mostro e nascondo l'immagine che comunica che non ci sono risultati
    const immagine = document.querySelector('#non-trovato');
    if (!numeroRisultati)
        immagine.classList.remove('hidden');
    else
        immagine.classList.add('hidden');
}