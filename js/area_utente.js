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
    
function OnInsert(){
    var commento= document.getElementById('commento');
    var parent=commento.parentNode;
    if(commento.value.length<10 || commento.value.length>200){
        if(parent.children.length==2){
            parent.removeChild(parent.children[1]);
        }
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo commento non può essere vuoto e deve contenere da 10 a 200 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )"));
       parent.appendChild(a);
       commento.focus();
       commento.select();
       return false;
    }
    
    return true;
}
function Ondate(){
    var datai= document.getElementById('datai');
    var dataf=document.getElementById('dataf');
    if(new Date(dataf.value)<=new Date(datai.value)){
        var parent=dataf.parentNode;
        if(parent.children.length==2){
            parent.removeChild(parent.children[1]);
        }
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode("Il campo data di fine studi deve essere maggiore della data di inizio"));
       parent.appendChild(a);
       dataf.focus();
       dataf.select();
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
   
 