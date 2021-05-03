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

<div class="content" style="margin-top: 15vh">
  <div class="container containergrey" style="margin-bottom: 5vh">
<?php
if (isset($_SESSION['user'])) {
  $link = $_ENV["discord_link"];
  echo '
<p style="text-align: center;">Rejoignez le serveur Discord pour participer au concours à distance: <br>
 <a href="'; echo $link; echo '" title="Discord">Rejoindre le Discord</a></p>
';
}
?>
    <p style="text-align: center;">Accédez au sujet pour participer au concours:<br>
    <a href="sujets.php">Accéder au sujet</a></p> 
  </div>

  <div class="container containergrey">
    <p style="text-align: center;">Uploadez des instances pour que votre score soit pris en compte:<br>
    <a href="upload.php">Uploader des instances</a></p>
    <p style="text-align: center;">Accédez au classement en temps réel de tous les candidats:<br>      <a href="leaderboard.php">Accéder au classement</a></p>
  </div>
</div>

<?php
include("footer.php");
?>
