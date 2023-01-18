//Validazione dei campi
var test={
    "com_utente":/^[@a-zA-Z0-9._-]{4,40}$/,
    "com_classe":/^(L|LM)[0-9]{2}$/,
    "cor_classe":/^(L|LM)[0-9]{2}$/,
    "cor_nome":/^[a-zA-Z\s]+$/,
    "cor_ateneo":/^[a-zA-Z\s]+$/,
    "cor_link": /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/,
    "cor_accesso":/^(Accesso programmato|Accesso libero con prova|Accesso a numero chiuso|Accesso libero cronologico)$/
   }; 
   
function Validate(element){
    var parent= element.parentNode;
    
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(!element.value.length){
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode(element.dataset.msgEmpty));
       a.classList.add('error');
       parent.appendChild(a);
       element.focus();
       element.select();
       return false;
    }
    else if(element.value.search(test[element.id])==-1){
        console.log(element.dataset.control);
        console.log(element.value);
        var b=document.createElement('strong');
        b.appendChild(document.createTextNode(element.dataset.msgInvalid));
        b.classList.add('error');
        parent.appendChild(b); 
        element.focus();
        element.select();
        return false;
    }
    return true;
}

function OnCourseAdd(){
    return Validate(document.getElementById('cor_nome')) && Validate(document.getElementById('cor_classe')) && Validate(document.getElementById('cor_ateneo')) && Validate(document.getElementById('cor_accesso')) && Validate(document.getElementById('cor_link'));
}

function OnCourseDelete(){
    if(Validate(document.getElementById('cor_nome')) && Validate(document.getElementById('cor_classe')) && Validate(document.getElementById('cor_ateneo'))){
        return window.confirm("Sei sicuro di voler eliminare questo corso?");
    }
    return false;
}

function OnCommentFind(){
    var username=document.getElementById('com_utente');         
    var classe=document.getElementById('com_classe');    
    
    if (username.value.lenght && classe.value.lenght){ 
        return Validate(username) & Validate(classe);//entrambi utilizzati li verifico tutti e due
    } 
    else{
        if(username.value.lenght || classe.value.lenght) {
            //almeno uno utilizzato
            return username.value.lenght ? Validate(username) : Validate(classe);
        }
        //nessuno dei due utilizzato
        var parent= document.getElementById('formTrovaCommenti').parentNode;
        var a=document.createElement('strong');
        a.classList.add('error');
        a.appendChild(document.createTextNode('riempire almeno uno dei due campi'));
        parent.appendChild(a);
        return false;
    }  
}

function Box_Validate(){
    var box=document.getElementsByName('commento[]'); // è una lista quindi devo verificare se esiste al meno un elemento selezionato altrimenti do un errore
    for(var i=0; i<box.length; i++){
        if(box[i].checked){
            return true;
        }
    }
    var parent= box[0].parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Selezionare un commento per cancellarlo"));
       parent.appendChild(a);
       box[0].focus();
       box[0].select();
   return false;
}

function OnCommentDelete(){
    if(Box_Validate()){
        return window.confirm("Sei sicuro di voler elminirare gli elementi selezionati?");
    }
    return false;
}