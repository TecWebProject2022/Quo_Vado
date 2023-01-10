//Validazione dei campi
var test={
    "name":/^[a-zA-Z ]{2,20}$/,
    "last_name":/^[a-zA-Z ]{2,40}$/,
    "birthday":/\d{4}\-\d{2}\-\d{2}/,
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
    for(var i in test){
        var element=document.getElementById(i);
        if(!Validation(element))
            return false;
       }
       return true;

}
//Aggiunta funzioni all'onload
function AddFunction(){
   for(var i in test){
    var element=document.getElementById(i);
    element.onblur=function(){Validation(this);};
    element.onblur=function(){Validation(this);};
   }
    
}





