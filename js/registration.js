//mega giga bozza sto solo cercando di capire js
// caricata all' onload
function addFunction(){
  const name = document.getElementById("name");
  const lastName = document.getElementById("last_name");
  const birthday = document.getElementById("birthday");
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const repeatPassword = document.getElementById("repeat_password");

  name.addEventListener("blur", function(){Validate(name)});
  lastName.addEventListener("blur", function(){Validate(lastName)});
  birthday.addEventListener("blur", function(){dateValidate(birthday)});
  username.addEventListener("blur", function(){Validate(username)});
  password.addEventListener("blur", function(){Validate(password)});
  repeatPassword.addEventListener("blur", function(){ r_PasswordValidate(repeatPassword)});
}

// se l' input e' valido ritorna true altrimenti ritorna false e aggiunge un messaggio di errore
function Validate(element){
  var parent= element.parentNode;
  if(parent.children.length==2){
      parent.removeChild(parent.children[1]);
  }
  
  if(!element.checkValidity()&& (element.value.length < parseInt(element.getAttribute('maxlength'), 10)) && (element.value.length > parseInt(element.getAttribute('minlength'), 10))){
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
  var input=Date.parse(element.value);
  var now= new Date();
  if(element.checkValidity() && (input >= Date.parse(element.getAttribute('min')))){
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
  var a=document.createElement('strong');
  a.appendChild(document.createTextNode(element.dataset.msgEmpty));
  parent.appendChild(a);
  element.focus();
  element.select();
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


function formValidate(form) {
  var name = form.getElementById("name");
  var lastname = form.getElementById("lastname");
  var birthday = form.getElementById("birthday");
  var username = form.getElementById("username");
  var password = form.getElementById("password");
  var r_password = form.getElementById("repeat_password");

  return Validate(name) & Validate(lastname) & Validate(birthday) & Validate(username) & Validate(password) & r_PasswordValidate(r_password);
}

function hideFieldset() {
  // Get the the button and the target
  var targetFieldset = document.getElementById("set_username_password");

  // Remove the "hidden" class from the target fieldset
  targetFieldset.classList.remove("skip");
}

