function VerificaJS(){
    // gestione menù 
	var mini = document.getElementsByClassName('minimenu');
	mini[0].classList.add('not_view');

    // imposta wizard
    document.getElementById("begin").classList.add("toShow");
    document.getElementById("questions").classList.add("toHide");
    document.getElementById("end").classList.add("toHide");

    var t = document.getElementsByClassName("tab");
    for (var i=0; i<t.length; i++){
        t[i].classList.add("toHide");
    }

    var btn = document.getElementsByTagName("button");
    for (var i=0; i<btn.length; i++){
        btn[i].classList.replace("toHide","toShow");
    }
}

function View(){
	var minimenu = document.getElementById('menu-content');
	if(minimenu.classList.contains('not_view')){
		minimenu.classList.remove('not_view');
	}
	else{
		minimenu.classList.add('not_view');
	}
}


var currentTab = 0; // Imposta la visualizzazione dal primo tab

//Predispone la pagina per iniziare il questionario
function startQuest() { 
    document.getElementById("begin").classList.replace("toShow", "toHide");
    document.getElementById("questions").classList.replace("toHide", "toShow");
    currentTab = 0;
    showTab(currentTab);
}

//Mostra le varie parti/tab del questionatio
function showTab(n) {
    // mostra il testo
    var x = document.getElementsByClassName("tab");
    x[n].classList.replace("toHide","toShow");

    //sistema i pulsanti di controllo
    if (n == 0) {
        document.getElementById("prevBtn").classList.replace("toShow","toHide");
    }
    else {
        document.getElementById("prevBtn").classList.replace("toHide","toShow");
    }
    if (n < (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Avanti";
    } else {
        document.getElementById("nextBtn").innerHTML = "Fine";
    }

    //aggiusta indicatore di avanzamento
    fixStepIndicator(n)

}

//avanzamento  e mostra il successivo
function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    //nasconde tab attuale
    x[currentTab].classList.replace("toShow","toHide");
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
   document.getElementById("questions").classList.replace("toShow","toHide");
   document.getElementById("end").classList.replace("toHide","toShow");
}

//consente di ripetere il questionario
function reStart(){
    document.getElementById("end").classList.replace("toShow","toHide");
    document.getElementById("begin").classList.replace("toHide","toShow");
}