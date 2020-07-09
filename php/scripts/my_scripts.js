function validatePassword(){
  var password = document.getElementById("password");
  var confirm_password = document.getElementById("password-verif");
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Les mots de pass ne sont pas identiques");
  } else {
    confirm_password.setCustomValidity('');
  }
}
