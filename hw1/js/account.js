//effettuto la fetch per ottenere i dati del cliente
fetch("get_account.php").then(onResponse).then(onAccountJson);

function onResponse(promise) {
    return promise.json();
}
//appena arrivano i dati li stampo
function onAccountJson(json) {
    //nascondo il loader
    document.getElementById('loader').classList.add('hidden');
    const userInfo = document.getElementById("user-info");
    //serve a cancellare il contenuto quando viene modificato un campo e ricarico tutti i dati
    userInfo.innerHTML = "";
    const nome = document.createElement('div');
    nome.textContent = json.Nome;
    //tramite questa funzione aggiungo l'icona per modificare i dati con il relativo eventListener
    addEdit(nome, "nome");
    userInfo.appendChild(nome);
    const cognome = document.createElement('div');
    cognome.textContent = json.Cognome;
    addEdit(cognome, "cognome");
    userInfo.appendChild(cognome);
    const email = document.createElement('div');
    email.textContent = json.Email;
    addEdit(email, "email");
    userInfo.appendChild(email);
    const telefono = document.createElement('div');
    telefono.textContent = json.Telefono;
    addEdit(telefono, "telefono");
    userInfo.appendChild(telefono);
    const indirizzo = document.createElement('div');
    indirizzo.textContent = json.Indirizzo;
    addEdit(indirizzo, "indirizzo");
    userInfo.appendChild(indirizzo);
    //creo il button solo la prima volta, quando ricarico i dati non lo creo nuovamente
    if (document.getElementById('button-ordini') == null) {
        const ordini = document.createElement('a');
        ordini.href = "ordini.php";
        ordini.textContent = "Visualizza ordini effettuati";
        ordini.id = "button-ordini";
        document.querySelector("article").appendChild(ordini);
    }
}
//aggiunge l'icona per modificare e il listener al click su di essa, il primo parametro è il div a cui appendere l'icona, il secondo parametro è una stringa che contiene il campo da modificare nel db
function addEdit(div, campo) {
    const edit = document.createElement('span');
    edit.innerHTML = " <i class=\"fas fa-user-edit\"></i>";
    edit.addEventListener("click", onClickEdit);
    //salvo il campo da modificare nel dataset
    edit.dataset.campo = campo;
    div.appendChild(edit);
}
//quando clicca su una delle icone
function onClickEdit(event) {
    //leggo il nome del campo da modificare
    const campo = event.currentTarget.dataset.campo;
    //mostro un prompt per mermettere all'utente di inserire il nuovo valore
    const nuovoValore = prompt(campo == "email" ? "Inserisci la nuova email" : "Inserisci il nuovo " + campo);
    //nel caso in cui non clicca su annulla
    if (nuovoValore != null) {
        //effettuo la richiesta di modificare il nome
        fetch("modifica_account.php?campo=" + campo + "&valore=" + nuovoValore).then(onResponse).then(onModificato);
        document.getElementById('loader').classList.remove('hidden');
    }
}

function onModificato(json) {
    //se la modifica va a buon fine ricarico tutti i dati
    if (json == true)
        fetch("get_account.php").then(onResponse).then(onAccountJson);
}