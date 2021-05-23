function toggleClass(node, value, error = 0) {
    let spanErrore;
    //se non viene fornito il parametro error
    if (!error) {
        //seleziono l'errore che ha come id il nome dell'input
        spanErrore = document.getElementById(node.name);
    } else {
        //seleziono l'errore (email-1 o email-2)
        spanErrore = document.getElementById(node.name + "-" + error);
    }
    //cambio il colore e mostro o nascondo il messaggio di errore
    if (value) {
        node.classList.add(true);
        node.classList.remove(false);
        spanErrore.classList.add('hidden');
    } else {
        node.classList.add(false);
        node.classList.remove(true);
        spanErrore.classList.remove('hidden');
    }
}

function onResponse(response) {
    return response.json();
}

let errMail = 0;

function jsonCheckEmail(json) {
    const cond = !json.exists;
    //il campo email ha due possibili messaggi di errore, se l'email è già usata (2) e se l'email non è valida (1)
    toggleClass(emailInput, !json.exists, 2);
    errMail = !cond;
}
//Verifico che il campo sia riempito
function checkNome() {
    const cond = nomeInput.value.length > 0;
    //cambia il colore del bordo e mostra il messaggio
    toggleClass(nomeInput, cond);
    return cond ? 0 : 1;
}

function checkCognome() {
    const cond = cognomeInput.value.length > 0;
    toggleClass(cognomeInput, cond);
    return cond ? 0 : 1;
}

function checkPassword() {
    //Almeno un numero, un carattere minuscolo, uno maiuscolo e un carattere speciale
    //Tra 8 e 15 caratteri
    const cond = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,15}$/.test(passwordInput.value);
    toggleClass(passwordInput, cond);
    //se anche il campo conferma password è pieno lo controllo (per cambiare colore da rosso a verde in caso l'utente cambi la prima password per farla coincidere con la seconda)
    if (confermaInput.value.length)
        toggleClass(confermaInput, confermaInput.value == passwordInput.value);
    return cond ? 0 : 1;
}

function checkConfernma() {
    const cond = (confermaInput.value == passwordInput.value) && confermaInput.value != "";
    toggleClass(confermaInput, cond);
    return cond ? 0 : 1;
}

function checkIndirizzo() {
    const cond = indirizzoInput.value.length > 0;
    toggleClass(indirizzoInput, cond);
    return cond ? 0 : 1;
}

function checkTelefono() {
    // ^ inizio stringa, \d qualsiasi cifra, {10} 10 cifre, $ fine stringa
    const cond = /^\d{10}$/.test(String(telefonoInput.value));
    toggleClass(telefonoInput, cond);
    return cond ? 0 : 1;
}

function checkEmail() {
    //verifica che l'email sia nel formato valido
    const cond = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(emailInput.value).toLowerCase());
    toggleClass(emailInput, cond, 1);
    if (cond)
        fetch("check_mail.php?e=" + encodeURIComponent(emailInput.value)).then(onResponse).then(jsonCheckEmail);
    return cond ? 0 : 1;
}
//controlla nuovamente tutti i campi
function checkForm(event) {
    var errori = 0;
    //somma tutti gli errori
    errori += checkNome() +
        checkCognome() +
        checkEmail() +
        checkPassword() +
        checkConfernma() +
        checkIndirizzo() +
        checkTelefono() +
        errMail; //Email già in uso
    //se ci sono errori blocca l'invio dei dati
    if (errori)
        event.preventDefault();
}

//agggiungo ad ogni campo un listener all'evento blur per valida tale campo
const nomeInput = document.forms['signup'].nome;
nomeInput.addEventListener('blur', checkNome);
const cognomeInput = document.forms['signup'].cognome;
cognomeInput.addEventListener('blur', checkCognome);
const emailInput = document.forms['signup'].email;
emailInput.addEventListener('blur', checkEmail);
const passwordInput = document.forms['signup'].password;
passwordInput.addEventListener('blur', checkPassword);
const confermaInput = document.forms['signup'].conferma;
confermaInput.addEventListener('blur', checkConfernma);
const indirizzoInput = document.forms['signup'].indirizzo;
indirizzoInput.addEventListener('blur', checkIndirizzo);
const telefonoInput = document.forms['signup'].telefono;
telefonoInput.addEventListener('blur', checkTelefono);
//al submit associo un listener, ricontrolla tutti i campi, conta gli errori e nel caso in cui ci sono errori, inibisce l'invio dei dati
const submit = document.forms['signup'].submit;
submit.addEventListener('click', checkForm);