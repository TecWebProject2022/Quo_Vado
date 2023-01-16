var test={
    "email":/^([\w\-\+\.]+)\@([\w\-\+\.]+)\.([\w\-\+\.]+)$/,
    "commento":/^[!?@a-zA-Z .,_-]{10,500}$/
   }; 
   var respond={
    "commento":"Il campo commento/mesaggio può contenere da 10 a 500 caratteri (sono amessi i seguenti simboli: .,_-!?@)",
    "email": "Il campo email non corrisponde ad una email valida"
   }
   function Validation(element){
    var parent= element.parentNode;
    
    if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
    if(element.value.search(test[element.id])==-1){
        var b=document.createElement('strong');
        b.appendChild(document.createTextNode(respond[element.id]));
        parent.appendChild(b); 
        element.focus();
        element.select();
        return false;
    }
    return true;
   }
   function Validate(){
    var email=document.getElementById('email');
    var commento=document.getElementById('commento');
    if(Validation(commento) & Validation(email) ){
        return true;
    }
    return false; 
}