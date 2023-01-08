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
        parent.appendChild(b); 
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
function AddFunction(){
    var user= document.getElementById('username');
    var pw= document.getElementById('password');
    user.onblur=function(){Validation(user);};
    pw.onblur=function(){Validation(pw);};
}





