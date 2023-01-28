//Validazione dei campi
var test={
    "username":/^[@a-zA-Z0-9._-]{4,40}$/,
    "password":/^[@a-zA-Z0-9._-]{4,20}$/
   }; 
function Validation(element){
    
    var parent= element.parentNode;
    
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(!element.value.length){
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode(element.dataset.msgEmpty));
       a.classList.add('errorjs');
       parent.appendChild(a);
       element.focus();
       element.select();
       return false;
    }
    return true;
}
//Chiamata al submit
function Validate(){
    var user= document.getElementById('username');
    var pw= document.getElementById('password');
    if(Validation(user) & Validation(pw)){
        return true;
    }
    return false; 
}
//Aggiunta funzioni all'onload




