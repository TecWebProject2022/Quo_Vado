function Validation(element){
    var parent= element.parentNode;
    if(parent.children.lenght==2){
        parent.removeChild(parent.children[1]);
    }
    if(!element.value.lenght){
       var a=document.createElement('strong');
       a.appendChild(document.createTextNode(element.dataset.msgEmpty));
       parent.appendChild(a);
       element.focus();
       element.select();
       return false;
    }
    if(element.value>element.dataset.limit){
        var a=document.createElement('em');
        a.appendChild(document.createTextNode(element.dataset.msgInvalid));
        parent.appendChild(a); 
        element.focus();
        element.select();
        return false;
    } 
    return true;
}
    

function Validate(){
    var user= document.getElementById('username');
    var pw= document.getElementById('password');
    if(Validation(user) & Validation(pw))
        return true;
    return false; 
}




