<?php
// Route kiro.enpc.org/concours.php
// Le site ou l'on peut trouver le sujet, poster des solutions et autre.

include("config.php");

if (!is_admin()) {
                  header('Location: index.php?ns');
                  exit();
}


if (!isset($_SESSION["user"])){
  header('Location: index.php?not_connected');
  exit();
}

include("header.php");
include("navbar.php");

?>
<a href="sujets.php">Accéder au sujet</a> 

<form action="send_instance.php" method="post">
  <label for="entree">Instance:</label>
  <input type="file" name="entree">
  <input type="submit" value="Envoyer">
</form>

<?php
if (isset($_SESSION['user'])) {
  $link = $_ENV["discord_link"];
  echo '
<p style="text-align: center;">Rejoignez le serveur Discord pour participer au concours à distance: <br>
 <a href="'; echo $link; echo '" title="Discord">Rejoindre le Discord</a></p>
';
}
?> 

<?php
include("footer.php")
?>
