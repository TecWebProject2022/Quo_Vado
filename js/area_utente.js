function OnDelete(){
return window.confirm("Sei sicuro di voler elminirare l'elemento selezionato?")
}
function OnInsert(){
    var commento= document.getElementById('commento');
    console.log(commento.value.length);
    if(commento.value.length<10 || commento.value.length>200 )
    console.log("hh");
    return false;
}
 