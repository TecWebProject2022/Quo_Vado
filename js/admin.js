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
       a.classList.add('error_js');
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
function OnPassword(){
    var pw=document.getElementById('oldpassword');
    var parent= pw.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(!pw.value.length){
       
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo vecchia password non può essere vuoto"));
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
       parent.appendChild(a);
       repeat.focus();
       repeat.select();
       return false;
    }
    return true;


}
function Cancella(){
    var user=document.getElementById('com_utente');
    var classe=document.getElementById('com_classe');
    var parent= user.parentNode;
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(!classe.value.length && !user.value.length){
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo user o il campo classe di laurea deve essere riempito. "));
       parent.appendChild(a);
       user.focus();
       return false;
    }

    
    return true;
}
function Remove(){
    var classe=document.getElementById('cor_classe');
 var parent= classe.parentNode;
 if(parent.children.length==2){
     parent.removeChild(parent.children[1]);
 }
 if(!classe.value.length){
    var a=document.createElement('strong');
    a.appendChild(document.createTextNode("Il campo Classe di  laurea non può essere vuoto"));
    parent.appendChild(a);
    classe.focus();
    return false;
 }
 var ateneo=document.getElementById('cor_ateneo');
 var parent= ateneo.parentNode;
 if(parent.children.length==2){
     parent.removeChild(parent.children[1]);
 }
 if(!ateneo.value.length){
    var a=document.createElement('strong');
    a.appendChild(document.createTextNode("Il campo Ateneo non può essere vuoto"));
    parent.appendChild(a);
    ateneo.focus();
    return false;
 }
 var nome=document.getElementById('cor_nome');
 var parent= nome.parentNode;
 if(parent.children.length==2){
     parent.removeChild(parent.children[1]);
 }
 if(!nome.value.length){
    var a=document.createElement('strong');
    a.appendChild(document.createTextNode("Il campo nome corso di studio non può essere vuoto"));
    parent.appendChild(a);
    nome.focus();
    nome.select();
    return false;
 }
 
 
 return true;
}
function Add(){
    if(Remove()){
        var link=document.getElementById('cor_link');
        var parent= link.parentNode;
        if(parent.children.length==2){
            parent.removeChild(parent.children[1]);
        }
        if(!link.value.length){
            var a=document.createElement('strong');
            a.appendChild(document.createTextNode("Il campo link non può essere vuoto"));
            parent.appendChild(a);
            link.focus();
            link.select();
            return false;
        }
        var accesso=document.getElementById('cor_accesso');
        var parent= accesso.parentNode;
        if(parent.children.length==2){
            parent.removeChild(parent.children[1]);
        }
        if(!accesso.value.length){
            var a=document.createElement('strong');
            a.appendChild(document.createTextNode("Il campo acesso  non può essere vuoto"));
            parent.appendChild(a);
            accesso.focus();
            return false;
        }
        return true;
    }
    return false;
}
