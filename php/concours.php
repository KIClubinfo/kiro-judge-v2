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
<a href="sujets/sujet4.pdf">Accéder au sujet</a> truc choli <a href="sujets/sujet4.zip">Télécharger les instances</a>

<form action="send_instance.php" method="post">
  <label for="entree">Instance:</label>
  <input type="file" name="entree">
  <input type="submit" value="Envoyer">
</form>


<?php
include("footer.php")
?>
