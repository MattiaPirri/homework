const input = document.querySelector('input');
input.addEventListener('keyup', onKeyup);

function onKeyup(e) {
    let numeroRisultati = 0;
    const items = document.querySelectorAll("#prodotti .item");
    for (let i = 0; i < items.length; i++) {
        const s = items[i].querySelector('.content').textContent.replace(' - ', ' ');
        if (s.toLowerCase().indexOf(e.currentTarget.value.replace(' - ', ' ').toLowerCase()) !== -1) {
            items[i].classList.remove('hidden');
            numeroRisultati++;
        } else items[i].classList.add('hidden');
    }
    const immagine = document.querySelector('#non-trovato');
    if (!numeroRisultati)
        immagine.classList.remove('hidden');
    else
        immagine.classList.add('hidden');
}

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

//Creo una stringa per poter cercare l'elemento nella sezione principale e cambiarne l'immagine del tasto
//considerando la coppia marca+nome chiave
function creaStringa(item) {
    const contenuto = item.querySelector('.content');
    const marca = contenuto.querySelector('strong').textContent;
    const nome = contenuto.querySelector('.nome').textContent;
    return (marca + nome);
}

function cerca(selector, stringa) {
    const items = document.querySelectorAll(selector);
    //Effettuo la ricerca
    for (const item of items) {
        if (creaStringa(item) == stringa) {
            //Ritorno l'elemento
            return item;
        }
    }
}

function nascondi() {
    titolo_preferiti.classList.add('hidden');
    sezione_preferiti.classList.add('hidden');
    const empty = document.querySelector('#preferiti-navbar .empty');
    empty.classList.remove('hidden');
}

//Seleziono la sezione e il relativo titolo
const preferiti = document.querySelectorAll('.preferiti');
const titolo_preferiti = preferiti[0];
const sezione_preferiti = preferiti[1];

//Listener associato agli elementi dei preferiti
function onClickRimuoviDaiPreferiti(e) {
    const target = e.currentTarget;
    //Rimuovo l'elemento cliccato
    const item = target.parentNode.parentNode;
    item.remove();
    //Decremento il contatore nella navbar
    num_preferiti.textContent = parseInt(num_preferiti.textContent) - 1;
    //Creo una stringa per poter cercare l'elemento nella sezione principale e cambiarne l'immagine del tasto
    //considerando la coppia marca+nome chiave
    const stringa = creaStringa(item);
    //cerco e cambio l'immagine e il listener nella sezione principale 
    const cuore = cerca('#prodotti .item', stringa).querySelector('.cuore');
    cuore.src = 'img/cuore-bianco.png';
    cuore.addEventListener('click', onClickAggiungiPreferiti);
    cuore.removeEventListener('click', onClickRimuoviPreferiti);
    //cerco e rimuovo dalla navbar
    cerca('#preferiti-navbar .item', stringa).remove();
    //Nel caso in cui i preferiti sono zero, nascondo la sezione preferiti e faccio comparire il div 
    //nella navbar che mi informa che non ci sono preferiti
    if (parseInt(num_preferiti.textContent) === 0) {
        nascondi();
    }
}

//Listener associato agli elementi normalemente visibili
function onClickRimuoviPreferiti(e) {
    const target = e.currentTarget;
    //Decremento il contatore nella navbar
    num_preferiti.textContent = parseInt(num_preferiti.textContent) - 1;
    //Creo una stringa per poter cercare l'elemento nella sezione principale e cambiarne l'immagine del tasto
    //considerando la coppia marca+nome chiave
    const stringa = creaStringa(target.parentNode.parentNode);
    //Cerco l'elemento da rimuovere dalla sezione preferiti e lo rimuovo
    cerca('.preferiti .item', stringa).remove();
    //cambio l'immagine e il listener
    target.src = "img/cuore-bianco.png";
    target.addEventListener('click', onClickAggiungiPreferiti);
    target.removeEventListener('click', onClickRimuoviPreferiti);
    //Rimuovo dalla navbar
    cerca('#preferiti-navbar .item', stringa).remove();
    //Nel caso in cui i preferiti sono zero, nascondo la sezione preferiti e faccio comparire il div 
    //nella navbar che mi informa che non ci sono preferiti
    if (parseInt(num_preferiti.textContent) === 0) {
        nascondi();
    }
}

//Listener associato agli elementi nei preferiti navbar
function onClickRimuoviDaiPreferitiNavbar(e) {
    const target = e.currentTarget;
    //Decremento il contatore nella navbar
    num_preferiti.textContent = parseInt(num_preferiti.textContent) - 1;
    //Rimuovo l'elemento cliccato
    target.parentNode.remove();
    //Creo una stringa per poter cercare l'elemento nella sezione principale e cambiarne l'immagine del tasto
    //considerando la coppia marca+nome chiave
    const stringa = creaStringa(target.parentNode);
    //Cerco l'elemento da rimuovere dalla sezione preferiti e lo rimuovo
    cerca('.preferiti .item', stringa).remove();
    //cerco e cambio l'immagine e il listener nella sezione principale 
    const cuore = cerca('#prodotti .item', stringa).querySelector('.cuore');
    cuore.src = 'img/cuore-bianco.png';
    cuore.addEventListener('click', onClickAggiungiPreferiti);
    cuore.removeEventListener('click', onClickRimuoviPreferiti);
    //Nel caso in cui i preferiti sono zero, nascondo la sezione preferiti e faccio comparire il div 
    //nella navbar che mi informa che non ci sono preferiti
    if (parseInt(num_preferiti.textContent) === 0) {
        nascondi();
    }
}

function onClickAggiungiPreferiti(e) {
    const target = e.currentTarget;
    target.src = "img/cuore-spezzato.png";
    //Incremento il contatore della navbar
    num_preferiti.textContent = parseInt(num_preferiti.textContent) + 1;
    //Seleziono l'ogetto da aggiungere ai preferiti 
    const item = target.parentNode.parentNode;
    //Creo la struttura voluta copiando i contenuti dall'elemento da aggiungere
    /*

    <div class="item">
        <img src="https://linkimmagine.jpg">
        <span class="content">
            <strong>Marca</strong>
            <span> - </span>
            <span class="nome">Nome</span>
        </span>
        <div class="overlay">
            <img src="img/cuore-spezzato.png">
        </div>
    </div>

    */
    const itemToAdd = document.createElement('div');
    itemToAdd.classList.add('item');
    const nuovaImmagine = document.createElement('img');
    nuovaImmagine.src = item.querySelector('img').src;
    itemToAdd.appendChild(nuovaImmagine);
    const span = document.createElement('span');
    span.classList.add('content');
    itemToAdd.appendChild(span);
    const marca = document.createElement('strong');
    marca.textContent = item.querySelector('strong').textContent;
    span.appendChild(marca);
    const trattino = document.createElement('span');
    trattino.textContent = ' - ';
    span.appendChild(trattino);
    const nome = document.createElement('span');
    nome.classList.add('nome');
    nome.textContent = item.querySelector('.nome').textContent;
    span.appendChild(nome);
    const overlay = document.createElement('div');
    overlay.classList.add('overlay');
    itemToAdd.appendChild(overlay);
    const cuore = document.createElement('img');
    cuore.src = 'img/cuore-spezzato.png';
    cuore.addEventListener('click', onClickRimuoviDaiPreferiti);
    overlay.appendChild(cuore);
    sezione_preferiti.appendChild(itemToAdd);
    target.removeEventListener('click', onClickAggiungiPreferiti);
    target.addEventListener('click', onClickRimuoviPreferiti);
    //Preferiti navbar
    /*

    <div class="item">
        <img class="immagine" src="link immagine">
        <div class="content">
            <strong>Marca</strong>
            <em class="nome">Nome</em>
        </div>
        <img class="btn-cuore" src="img/cuore-spezzato-rosso.png">
    </div>

    */
    const navbar = document.querySelector('#preferiti-navbar');
    //Nel caso in cui sto inserendo il primo preferito mostro la sezione
    if (parseInt(num_preferiti.textContent) === 1) {
        titolo_preferiti.classList.remove('hidden');
        sezione_preferiti.classList.remove('hidden');

        const empty = document.querySelector('#preferiti-navbar .empty');
        empty.classList.add('hidden');
    }
    const itemPreferitoNavbar = document.createElement('div');
    itemPreferitoNavbar.classList.add('item');
    navbar.appendChild(itemPreferitoNavbar);
    const imgPreferitoNavbar = document.createElement('img');
    imgPreferitoNavbar.classList.add('immagine');
    imgPreferitoNavbar.src = item.querySelector('img').src;
    itemPreferitoNavbar.appendChild(imgPreferitoNavbar);
    const contenutoPreferitoNavbar = document.createElement('div');
    contenutoPreferitoNavbar.classList.add('content');
    const marcaPreferito = document.createElement('strong');
    marcaPreferito.textContent = item.querySelector('strong').textContent
    contenutoPreferitoNavbar.appendChild(marcaPreferito);
    const nomePreferito = document.createElement('em');
    nomePreferito.classList.add('nome');
    nomePreferito.textContent = item.querySelector('.nome').textContent
    contenutoPreferitoNavbar.appendChild(nomePreferito);
    itemPreferitoNavbar.appendChild(contenutoPreferitoNavbar);
    const buttonCuore = document.createElement('img');
    buttonCuore.classList.add('btn-cuore');
    buttonCuore.src = 'img/cuore-spezzato-rosso.png';
    buttonCuore.addEventListener('click', onClickRimuoviDaiPreferitiNavbar);
    itemPreferitoNavbar.appendChild(buttonCuore);
}

const num_preferiti = document.querySelector('#desideri .numero');



const sezione = document.querySelector('#prodotti');
for (const content of contents) {
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
    sezione.appendChild(item);
    const a = document.createElement('a');
    a.href = '';
    item.appendChild(a);
    const immagine = document.createElement('img');
    immagine.src = content.immagine;
    a.appendChild(immagine);
    const contenuto = document.createElement('span');
    contenuto.classList.add('content');
    a.appendChild(contenuto);
    const marca = document.createElement('strong');
    marca.textContent = content.marca;
    contenuto.appendChild(marca);
    const trattino = document.createElement('span');
    trattino.textContent = ' - ';
    contenuto.appendChild(trattino);
    const nome = document.createElement('span');
    nome.classList.add('nome');
    nome.textContent = content.nome;
    contenuto.appendChild(nome);
    a.appendChild(document.createElement('br'));
    const stelle = document.createElement('span');
    stelle.classList.add('stelle');
    let textStelle = '';
    for (let i = 0; i < content.stelle; i++)
        textStelle += '★';
    for (let i = 0; i < 5 - content.stelle; i++)
        textStelle += '☆';
    stelle.textContent = textStelle;
    a.appendChild(stelle);
    const recensioni = document.createElement('span');
    recensioni.textContent = ' ' + content.recensioni + ' recensioni';
    a.appendChild(recensioni);
    a.appendChild(document.createElement('br'));
    const prezzo = document.createElement('span');
    //Per formattare il prezzo, fornito come numero decimale, in valuta
    const formatter = new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    })
    prezzo.textContent = formatter.format(content.prezzo);
    a.appendChild(prezzo);

    const dettagli = document.createElement('span');
    dettagli.textContent = 'Mostra maggiori dettagli';
    dettagli.classList.add('dettagli');
    dettagli.addEventListener('click', onClickDettagli);
    item.appendChild(dettagli);

    const descrizione = document.createElement('span');
    descrizione.textContent = content.descrizione;
    descrizione.classList.add('descrizione');
    descrizione.classList.add('hidden');
    item.appendChild(descrizione);

    const overlay = document.createElement('div');
    overlay.classList.add('overlay');
    item.appendChild(overlay);

    const immagineCuore = document.createElement('img');
    immagineCuore.classList.add('cuore');
    immagineCuore.src = 'img/cuore-bianco.png';
    immagineCuore.addEventListener('click', onClickAggiungiPreferiti);
    overlay.appendChild(immagineCuore);

    const immagineCarrello = document.createElement('img');
    immagineCarrello.src = 'img/carrello-bianco.png';
    immagineCarrello.classList.add('carrello');
    overlay.appendChild(immagineCarrello);
}
const immagine = document.createElement('img');
immagine.src = 'img/non-trovato.png';
sezione.appendChild(immagine);
immagine.classList.add('hidden');
immagine.id = 'non-trovato';