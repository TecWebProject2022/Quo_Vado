var currentTab = 0; // Imposta la visualizzazione dal primo tab
showTab(currentTab); // Mostralo

//Predispone la pagina per iniziare il questionario
function startQuest() { 
    document.getElementById("begin").classList.add("none");
    document.getElementById("questions").classList.add("block");
    
    currentTab = 0; 
    showTab(currentTab); 
}

//Mostra le varie parti/tab del questionatio
function showTab(n) {
    // mostra il testo
    var x = document.getElementsByClassName("tab");
    x[n].classList.remove('tab');
    x[n].classList.add('block');

    //sistema i pulsanti di controllo
    if (n == 0) {
        document.getElementById("prevBtn").classList.add("none");
    } else {
        document.getElementById("prevBtn").classList.add("inline");
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
var x = document.getElementsByClassName("block");
    //nasconde tab attuale
    x[currentTab].classList.remove('block');
    x[currentTab].classList.add('tab');
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
    document.getElementById("questions").classList.remove("block");
    document.getElementById("questions").classList.add("none");
    document.getElementById("end").classList.remove('none');
    document.getElementById("end").classList.add('block');
}

//consente di ripetere il questionario
function reStart(){
    document.getElementById('begin').classList.remove('none');
    document.getElementById("begin").classList.add('block');
    document.getElementById("end").classList.remove('block');
    document.getElementById("end").classList.add('none');
}