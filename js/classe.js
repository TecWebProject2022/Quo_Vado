//Validazione dei campi
var test={
    "commento":/^[a-zA-ZÀ-ÿ\s]{1,200}$/,
    "p_complessivo":/^([1-5])$/,
    "p_acc_fisica":/^([1-5])$/,
    "p_servizio":/^([1-5])$/,
    "p_tempestivita":/^([1-5])$/,
    "p_insegnamento":/^([1-5])$/
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
function Validate(event){
    for(var i in test){
        var element=document.getElementById(i);
        if(!Validation(element))
            event.preventDefault();
            return false;
       }
       return true;

}