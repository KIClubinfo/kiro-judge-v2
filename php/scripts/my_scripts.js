function validatePassword(){
  var password = document.getElementById("password");
  var confirm_password = document.getElementById("password-verif");
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Les mots de passe ne sont pas identiques");
  } else {
    confirm_password.setCustomValidity('');
  }
}

function avance(aMasquer, aAfficher){
  var ancien = document.getElementById(aMasquer);
  var nouveau = document.getElementById(aAfficher);
  if (aMasquer === "participant-1" && aAfficher === "participant-2"){ //Pour passer du 1 au 2, que si tout est valide
    if (document.getElementById("prenom-1").validity.valid && document.getElementById("nom-1").validity.valid && document.getElementById("email-1").validity.valid && document.getElementById("tel-1").validity.valid && document.getElementById("ecole-1").validity.valid){ //si tous les champs sont ok on envoie la suite
      ancien.style.display = "none";
      nouveau.style.display = "block";
    }
  }
  else if (aMasquer === "participant-2" && aAfficher === "participant-3"){ //Pour passer du 2 au 3, que si tout est valide
    if (document.getElementById("prenom-2").validity.valid && document.getElementById("nom-2").validity.valid && document.getElementById("email-2").validity.valid && document.getElementById("tel-2").validity.valid && document.getElementById("ecole-2").validity.valid){ //si tous les champs sont ok on envoie la suite
      ancien.style.display = "none";
      nouveau.style.display = "block";
    }
  }
  else if (aMasquer === "equipe" && aAfficher === "participant-1"){
    if (document.getElementById("team-name").validity.valid){ //si tous les champs sont ok on envoie la suite
      ancien.style.display = "none";
      nouveau.style.display = "block";
    }


  }
  else{ //Si on n'avance pas alors trkl
       ancien.style.display = "none";
      nouveau.style.display = "block";
  }
}
