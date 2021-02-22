<?php
include("config.php");
include("header.php");

if (isset($_SESSION['user'])){
  print_r($_SESSION['user']);
  print_r($_SESSION['team']);
}


if (isset($_GET['inscr'])){ //On affiche un message pour signifier la bonne inscription
  $msg = "Ton inscription a bien été prise en compte, il te reste qu'à te connecter avec le mot de passe envoyé par mail, pense à vérifier dans tes spams.";
}
if (isset($_GET['co'])){ //On affiche un message pour signifier la bonne donnexion
  $msg = "Tu es bien connecté.";
}
if (isset($_GET['co2'])){ //On affiche un message pour signifier la bonne connexion
  $msg = "Tu es bien connecté et ton mot de passe a été modifié.";
}
if (isset($_GET['deco'])){ //On affiche un message pour signifier la bonne connexion
  $msg = "Tu a bien été déconnecté.";
}

if (isset($_GET['change'])){ //On affiche un message pour signifier la bonne connexion
  $msg = "Ton nouveau mot de passe vous a été envoyé par email.";
}

if (isset($_GET['maj_admin'])){ //On affiche un message pour signifier la bonne connexion
  $msg = "Les données de l'utilisateur ont été mises à jour.";
}
echo $msg;

?>


<a href="inscription.php"> Inscription</a> <br />
<a href="connexion.php"> Connexion</a> <br />
<a href="teams.php?id_team=11"> Teams</a> <br />
<a href="deconnexion.php"> Deconnexion</a> <br />
<?php if(is_admin()) { echo '<a href="edit_info_admin.php"> Editer des infos</a> <br />'; } ?>

<?php
include("footer.php");
?>
