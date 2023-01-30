
function Validate(){
   var filtro=document.getElementsByName('filtri[]');
   var parent=filtro[0].parentNode;
   if(parent.children.length==2){
    parent.removeChild(parent.children[1]);
}
   for (var i= 0; i<filtro.length ;  i++){
   if(filtro[i].checked){
    return true;
   }
   }
   
   
    var a=document.createElement('strong');
   a.appendChild(document.createTextNode("Selezionare almeno un filtro"));
   a.classList.add('errorjs');
   parent.appendChild(a);
   filtro[0].focus();
   filtro[0].select();
   return false;
}