//mega giga bozza sto solo cercando di capire js
function displayMessage(element){
  var parent= element.parentNode;
  var a=document.createElement('strong');
  error= element.validity.valueMissing ? element.dataset.msgEmpty:element.dataset.msgInvalid;
  a.appendChild(document.createTextNode(error));
  parent.appendChild(a);
  element.focus();
  element.select();
}
// se l' input e' valido ritorna true altrimenti ritorna false e aggiunge un messaggio di errore
function Validate(element){
  var parent= element.parentNode;
  if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
  if(element.validity.patternMismatch || element.validity.tooLong || element.validity.tooShort || element.validity.valueMissing){
    displayMessage(element);    
    return false; 
  }
    return true;
} 


function dateValidate(element) {
  var parent= element.parentNode;
  if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
  if (!(element.validity.patternMismatch && element.validity.rangeUnderflow && element.validity.valueMissing)) {
    var input = new Date(element.value);
    var now = new Date();
    if (input > now) {
      displayMessage(element);
      return false;
    }
  } else {
    displayMessage(element);
    return false;
  }
  return true;
}

// controlla che il valore inserito sia valido e uguale a quello inserito nel campo password
function r_PasswordValidate(element){
  var parent= element.parentNode;
  if(parent.children.length==2){
        parent.removeChild(parent.children[1]);
    }
  if(element.value == document.getElementById("password").value) return Validate(element);
  else{
    var parent= element.parentNode;
    if(parent.children.length==2){
          parent.removeChild(parent.children[1]);
    }
    var a=document.createElement('strong');
    error= element.validity.valueMissing ? element.dataset.msgEmpty:element.dataset.msgInvalid;
    a.appendChild(document.createTextNode("le password non coincidono"));
    parent.appendChild(a);
    element.focus();
    element.select();
    return false;
  }
}


function formValidate(form) {
  var name = form.getElementById("name");
  var lastname = form.getElementById("lastname");
  var birthday = form.getElementById("birthday");
  var username = form.getElementById("username");
  var password = form.getElementById("password");
  var r_password = form.getElementById("repeat_password");

  return Validate(name) & Validate(lastname) & dateValidate(birthday) & Validate(username) & Validate(password) & r_PasswordValidate(r_password);
}

// caricata all' onload
function addFunction() {
  let name = document.getElementById("name");
  let lastName = document.getElementById("last_name");
  let birthday = document.getElementById("birthday");
  let username = document.getElementById("username");
  let password = document.getElementById("password");
  let repeatPassword = document.getElementById("repeat_password");

  name.onblur = function() { Validate(name) };
  lastName.onblur = function() { Validate(lastName) };
  birthday.onblur = function() { dateValidate(birthday) };
  username.onblur = function() { Validate(username) };
  password.onblur = function() { Validate(password) };
  repeatPassword.onblur = function() { r_PasswordValidate(repeatPassword) };
}
function hideFieldset() {
  // Get the the button and the target
  var targetFieldset = document.getElementById("set_username_password");

  // Remove the "hidden" class from the target fieldset
  targetFieldset.classList.remove("skip");
}

