function Box_Validate(){
    var box=document.getElementsByName('commento[]'); // è una lista quindi devo verificare se esiste al meno un elemento selezionato altrimenti do un errore
    var parent= box[0].parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    for(var i=0; i<box.length; i++){
        if(box[i].checked){
            return true;
        }
    }
   
    var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Selezionare un commento per cancellarlo"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       box[0].focus();
       box[0].select();
   return false;
}

function OnDelete(){
    if(Box_Validate()){
        return window.confirm("Sei sicuro di voler elminirare gli elementi selezionati?");
    }
    return false;
}
    
function OnInsert(){
    var commento= document.getElementById('commento');
    var parent=commento.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(commento.value.length<10 || commento.value.length>200){
       
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo commento non può essere vuoto e deve contenere da 10 a 200 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       commento.focus();
       commento.select();
       return false;
    }
    var p_complessivo=document.getElementById('p_complessivo');
    var parent=p_complessivo.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(p_complessivo.value<1 || p_complessivo.value>5){
        
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo punteggio complessivo deve essere compreso tra 1 e 5"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       p_complessivo.focus();
       p_complessivo.select();
       return false;
    }
    var p_acc_fisica=document.getElementById('p_acc_fisica');
    var parent=p_acc_fisica.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(p_acc_fisica.value<1 || p_acc_fisica.value>5){
        
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo punteggio accessibilità fisica deve essere compreso tra 1 e 5"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       p_acc_fisica.focus();
       p_acc_fisica.select();
       return false;
    }
    var p_inclusione=document.getElementById('p_inclusione');
    var parent=p_inclusione.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(p_inclusione.value<1 || p_inclusione.value>5){
        
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo punteggio servizio inclusione deve essere compreso tra 1 e 5"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       p_inclusione.focus();
       p_inclusione.select();
       return false;
    }
    var p_tempestivita=document.getElementById('p_tempestivita');
    var parent=p_tempestivita.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(p_tempestivita.value<1 || p_tempestivita.value>5){
        
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo punteggio tempestività burocratica deve essere compreso tra 1 e 5"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       p_tempestivita.focus();
       p_tempestivita.select();
       return false;
    }
    var p_insegnamento=document.getElementById('p_insegnamento');
    var parent=p_insegnamento.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(p_insegnamento.value<1 || p_insegnamento.value>5){
        
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo punteggio sulla qualità dell'insegnamento  deve essere compreso tra 1 e 5"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       p_insegnamento.focus();
       p_insegnamento.select();
       return false;
    }
    return true;
}
function Ondate(){
    var datai= document.getElementById('datai');
    var dataf=document.getElementById('dataf');
    var parent=dataf.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(new Date(dataf.value)<=new Date(datai.value)){
       
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo data di fine studi deve essere maggiore della data di inizio"));
       a.classList.add('errorjs');
       parent.appendChild(a);
       dataf.focus();
       dataf.select();
       return false;
    }
    console.log(new Date(datai.value));
    if(datai.value<'1960-01-01' || datai.value>'2100-01-01'){
       
        var a=document.createElement('strong');
        a.appendChild(document.createTextNode("Le date valide vanno dal 01-01-1960 al 01-01-2100"));
        a.classList.add('errorjs');
        parent.appendChild(a);
        dataf.focus();
        dataf.select();
        return false;
    }
    var punteggio=document.getElementById('punteggio');
    var parent=punteggio.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(punteggio.value<1 || punteggio.value>5){
       
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo punteggio scuola di provenienza deve compreso tra 1 e 5 "));
       a.classList.add('errorjs');
       parent.appendChild(a);
       punteggio.focus();
       punteggio.select();
       return false;
    }

    return true
    }
    function OnPassword(){
        var pw=document.getElementById('oldpassword');
        var parent= pw.parentNode;
        if(parent.children.length==2){
            parent.removeChild(parent.children[1]);
        }
        if(!pw.value.length){
           
           var a=document.createElement('strong');
           a.appendChild(document.createTextNode("Il campo vecchia password non può essere vuoto"));
           a.classList.add('errorjs');
           parent.appendChild(a);
           pw.focus();
           pw.select();
           return false;
        }
        var nuova = document.getElementById('newpassword');
        var parent= nuova.parentNode;
            if(parent.children.length==2){
                parent.removeChild(parent.children[1]);
            }
        if(!nuova.value.length){
            
           var a=document.createElement('strong');
           a.appendChild(document.createTextNode("Il campo nuova password non può essere vuoto"));
           a.classList.add('errorjs');
           parent.appendChild(a);
           nuova.focus();
           nuova.select();
           return false;
        }
        var repeat = document.getElementById('repeat');
        var parent= repeat.parentNode;
        if(parent.children.length==2){
            parent.removeChild(parent.children[1]);
        }
        if(!repeat.value.length){
          
           var a=document.createElement('strong');
           a.appendChild(document.createTextNode("Il campo nuova password non può essere vuoto"));
           a.classList.add('errorjs');
           parent.appendChild(a);
           repeat.focus();
           repeat.select();
           return false;
        }
        return true;


    }
   
 