<?php
include("config.php");


if (isset($_SESSION['user'])){
  print_r($_SESSION['user']);
  print_r($_SESSION['team']);
}

if (isset($_GET['inscr'])){ //On affiche un message pour signifier la bonne deconnexion
  $msg = "Ton inscription a bien été prise en compte, il te reste qu'à te connecter avec le mot de passe envoyé par mail, pense à vérifier dans tes spams.";
}
if (isset($_GET['co'])){ //On affiche un message pour signifier la bonne deconnexion
  $msg = "Tu est bien connecté.";
}
if (isset($_GET['co2'])){ //On affiche un message pour signifier la bonne deconnexion
  $msg = "Tu est bien connecté et ton mot de passe a été modifié.";
}
?>



<a href="inscription.php"> Inscription</a>
<a href="connexion.php"> Connexion</a>

<?php
include("footer.php");
?>
