<?php
include("config.php");


if (isset($_SESSION['user'])){
  print_r($_SESSION['user']);
}

if (isset($_GET['inscr'])){ //On affiche un message pour signifier la bonne deconnexion
  $msg = "Ton inscription a bien été prise en compte, il te reste qu'à te connecter avec le mot de passe envoyé par mail, pense à vérifier dans tes spams.";
}
?>



<a href="inscription.php"> Inscription</a>
<a href="connexion.php"> Inscription</a>

<?php
include("footer.php");
?>
