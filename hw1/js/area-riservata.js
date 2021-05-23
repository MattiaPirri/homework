//aggiungo i listener a tutti i button
const query1 = document.getElementById("q1");
query1.addEventListener('click', eseguiQuery1);

const query2 = document.getElementById("q2");
query2.addEventListener('click', eseguiQuery2);

const query3 = document.getElementById("q3");
query3.addEventListener('click', eseguiQuery3);

const query4 = document.getElementById("q4");
query4.addEventListener('click', eseguiQuery4);

function eseguiQuery1() {
    fetch("esegui_operazione.php?op=1").then(onResponse).then(mostraRisultato1);
}

function eseguiQuery2() {
    const str = prompt("Città che inizia con: ");
    if (str != null)
        fetch("esegui_operazione.php?op=2&s=" + str).then(onResponse).then(mostraRisultato2);
}


function isValidDate(dateString) {
    var regEx = /^\d{4}-\d{2}-\d{2}$/;
    //Se il formato non è valido in partenza ritorno false
    if (!dateString.match(regEx)) return false;
    var d = new Date(dateString);
    var dNum = d.getTime();
    if (!dNum && dNum !== 0) return false; // NaN , data non valida
    //d.toISOString() ritorna nel formato 2013-03-01T00:00:00.000Z nel caso in cui la data è 2013-03-29 difatto controllando il bisestile
    return d.toISOString().slice(0, 10) === dateString;
}

function eseguiQuery3() {
    //CALL P3("PRRMTT89S02C351G","Mattia", "Pirri","1991-11-02", "Via Indirizzo numero", "3825403794", 8000);

    let cf, nome, cognome, dataNascita, indirizzo, telefono, salario;
    //chiedo i valori finquando l'utente non decide di annullare l'operazione
    do {
        //Veficico che non sia il primo ciclo
        if (cf != null) {
            alert("Codice fiscale non valido");
            cf = prompt("Reinserisci codice fiscale: ");
        } else
            cf = prompt("Inserisci codice fiscale: ");
    } while (!/^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/i.test(cf) && cf != null); //continuo finchè il codice fiscale sia in un formato valido, (può comunnque essere errato, non mi sono concentrato su quest'aspetto) e finchè l'utente non decide di annullare l'operazione 

    if (cf != null)
        do {
            if (nome != null)
                alert("Non è possibile lasciare questo campo vuoto");
            nome = prompt("Inserisci nome: ");
        } while (nome != null && nome.length <= 0); //nel caso in cui la prima condizione non è verificata, la seconda non viene nemmeno presa in considerazione. Le condizioni le ho messe in quest'ordine per evitare che mi dicesse che non è possibile accedere alla proprieta length di un elemento nullo.
    if (nome != null)
        do {
            if (cognome != null)
                alert("Non è possibile lasciare questo campo vuoto");
            cognome = prompt("Inserisci cognome: ");
        } while (cognome != null && cognome.length <= 0);
    if (cognome != null)
        do {
            if (dataNascita != null)
                alert("Inserire una data valida");
            dataNascita = prompt("Inserire data di nascita (aaaa-mm-gg): ");
        } while (dataNascita != null && !isValidDate(dataNascita));

    if (dataNascita != null)
        do {
            if (indirizzo != null)
                alert("Non è possibile lasciare questo campo vuoto");
            indirizzo = prompt("Inserisci indirizzo: ");
        } while (indirizzo != null && indirizzo.length <= 0);

    if (indirizzo != null)
        do {
            if (telefono != null)
                alert("Inserire un numero di telefono valido");
            telefono = prompt("Inserisci numero di telefono: ");
        } while (telefono != null && !/^\d{10}$/.test(telefono));

    if (telefono != null)

        do {
        if (salario != null)
            alert("Inserire un salario valido");
        salario = prompt("Inserisci salario: ");

    } while (salario != null && (isNaN(salario) ? true : Math.sign(salario) == -1)); //finquando l'utente inserisce un numero positivo

    if (salario != null)
        fetch("esegui_operazione.php?op=3&cf=" + cf +
            "&n=" + nome +
            "&c=" + cognome +
            "&d=" + dataNascita +
            "&i= " + indirizzo +
            "&t=" + telefono +
            "&s=" + salario
        ).then(onResponse).then(mostraRisultato3);
}

function eseguiQuery4() {
    fetch("esegui_operazione.php?op=4").then(onResponse).then(mostraRisultato4);
}

function onResponse(promise) {
    return promise.json();
}

function mostraRisultato1(json) {
    alert("Perdita: " + json.perdita);
}

function mostraRisultato2(json) {
    let stringaAlert = json.length + " risultati.\n\n\n";
    for (magazzino of json)
        stringaAlert += "Id:" + magazzino.Id + "  Indirizzo:" + magazzino.Indirizzo + "  Città:" + magazzino.Città + "  Impiegati:" + magazzino.Impiegati + "\n\n";
    alert(stringaAlert);
}

function mostraRisultato3(json) {
    if (json == true)
        alert("Inserito");
}

function mostraRisultato4(json) {
    let stringaAlert = json.length + " risultati.\n\n\n";
    for (prodotto of json)
        stringaAlert += "EAN:" + prodotto.EAN + "  Marca:" + prodotto.Marca + "  Nome:" + prodotto.Nome + "\n\n";
    alert(stringaAlert);
}