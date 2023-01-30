var currentTab = 0; // Imposta la visualizzazione dal primo tab
showTab(currentTab); // Mostralo

//Predispone la pagina per iniziare il questionario
function startQuest() { 
    document.getElementById("begin").style.display = "none";
    document.getElementById("questions").style.display = "block";
    currentTab = 0; 
    showTab(currentTab); 
}

//Mostra le varie parti/tab del questionatio
function showTab(n) {
    // mostra il testo
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    //sistema i pulsanti di controllo
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Fine";
    } else {
        document.getElementById("nextBtn").innerHTML = "Avanti";
    }

    //aggiusta indicatore di avanzamento
    fixStepIndicator(n)

}

//avanzamento  e mostra il successivo
function nextPrev(n) {
var x = document.getElementsByClassName("tab");
    //nasconde tab attuale
    x[currentTab].style.display = "none";
    // mostra il successivo se esiste
    currentTab = currentTab + n;
    if (currentTab < x.length) {
        showTab(currentTab);
    } 
    else {
        exitQuest();
    }
}

//gestione avanzamento
function fixStepIndicator(n) {
// pulisco lo stato di avanzamento
var i, x = document.getElementsByClassName("pag");
for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
}
//aggiorno con lo stato attuale
x[n].className += " active";
}

//Esce dal questionario e mostra messaggio finale
function exitQuest(){
    document.getElementById("questions").style.display = "none";
    document.getElementById("end").style.display = "block";
}

//consente di ripetere il questionario
function reStart(){
    document.getElementById("begin").style.display = "block";
    document.getElementById("end").style.display = "none";
}