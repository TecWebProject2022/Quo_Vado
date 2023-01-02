function Validation(element){
    let parent= element.parentNode;
    if(parent.children.lenght==2){
        parent.removeChild(parent.children[1]);
    }
    if(!element.value.lenght){
       let a=document.createElement('strong');
       a.appendChild(document.createTextNode(element.dataset.msgEmpty));
       parent.appendChild(a);
       element.focus();
       element.select();
       return false;
    }
    else if(element.value>element.dataset.limit){
        let a=document.createElement('em');
        a.appendChild(document.createTextNode(element.dataset.msgInvalid));
        parent.appendChild(a); 
        element.focus();
        element.select();
        return false;
    } 
    return true;
}
function AddFunction(){
    let user= document.getElementById('username');
    let pw= document.getElementById('password');
    user.onblur=function(){ Validation(this)};
    pw.onblur=function(){Validation(this)};
}
function Validate(){
    let user= document.getElementById('username');
    let pw= document.getElementById('password');
    return Validation(user) && Validation(pw);
}




