//Validazione dei campi 
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
    else if(element.value.search(element.dataset.control)==-1){
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




