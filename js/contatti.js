var test={
    "name":/^[a-zA-Z ]{2,20}$/,
    "last_name":/^[a-zA-Z ]{3,30}$/,
    "email":/^([\w\-\+\.]+)\@([\w\-\+\.]+)\.([\w\-\+\.]+)$/,
    "commento":/^[!?@a-zA-Z .,_-]{10,500}$/
   }; 
   var respond={
    "name":"Il campo nome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 20 caratteri",
    "last_name":"Il campo cognome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 40 caratteri",
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
    var name= document.getElementById('name');
    var surname= document.getElementById('last_name');
    var email=document.getElementById('email');
    var commento=document.getElementById('commento');
    if(Validation(name) & Validation(surname) & Validation(commento) & Validation(email) ){
        return true;
    }
    return false; 
}