function Box_Validate(){
    var box=document.getElementsByName('commento[]'); // è un alista quindi devo verificare se esiste al meno un elemento selezionato altrimenti do un errore
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
function OnDelete(){
    if(Box_Validate()){
        return window.confirm("Sei sicuro di voler elminirare gli elementi selezionati?")
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
 