//mega giga bozza sto solo cercando di capire js
// caricata all' onload
function addFunction(){
  document.getElementById("name").addEventListener("blur",function(){Validate(document.getElementById("name"))});
  document.getElementById("last_name").addEventListener("blur",function(){Validate(document.getElementById("last_name"))});
  document.getElementById("birthday").addEventListener("blur",function(){dateValidate(document.getElementById("birthday"))});
  document.getElementById("username").addEventListener("blur",function(){usernameValidate(document.getElementById("username"))});
  document.getElementById("password").addEventListener("blur",function(){Validate(document.getElementById("password"))});
  document.getElementById("repeat_password").addEventListener("blur",function(){ r_PasswordValidate(document.getElementById("repeat_password"))});
}

// se l' input e' valido ritorna true altrimenti ritorna false e aggiunge un messaggio di errore
function Validate(element){
  var parent= element.parentNode;
  if(parent.children.length==2){
      parent.removeChild(parent.children[1]);
  }
  
  if(!x.checkValidity()){
    var a=document.createElement('strong');
    error= element.validity.valueMissing ? element.dataset.msgEmpty:element.dataset.msgInvalid;
    a.appendChild(document.createTextNode(error));
    parent.appendChild(a);
    element.focus();
    element.select();
    return false; 
  }
  
  return true;
}

function dateValidate(element){
  input=Date.parse(element.value);
  now= new Date();
  if(Validate(element)){
    if(input.getFullYear() <= now.getFullYear()){
      if(input.getMonth()<= now.getMonth()){
        if(input.getDate()<=now.getDate()){
          return true;
        }
      }
    }
    var a=document.createElement('strong');
    a.appendChild(document.createTextNode(element.dataset.msgInvalid));
    parent.appendChild(a);
    element.focus();
    element.select();
  }
  return false;
}

// controlla che il valore inserito sia valido e uguale a quello inserito nel campo password
function r_PasswordValidate(element){
  if(element.value == document.getElementById("password").value) return Validate(element);

  var a=document.createElement('strong');
  a.appendChild(document.createTextNode("Le password non coincidono"));
  parent.appendChild(a);
  element.focus();
  element.select();
  return false;
}


function formValidate(element){
  nome=element.getElementById("name");
  lastname=element.getElementById("lastname");
  birthday=element.getElementById("birthday");
  gender=element.getElementById("gender");
  school=element.getElementById("school");
  username=element.getElementById("username");
  password=element.getElementById("password");
  r_password=element.getElementById("repeat_password");

  return Validate(nome) & Validate(lastname) & Validate(birthday) & Validate(user) & Validate(password) & r_passwordValidate(r_password);
}

function hideFieldset() {
  // Get the the button and the target
  var targetFieldset = document.getElementById("set_username_password");

  // Remove the "hidden" class from the target fieldset
  targetFieldset.classList.remove("skip");
}

