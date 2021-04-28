<?php
// Route kiro.enpc.org/sujet.php
// Le site ou l'on peut trouver le sujet et les instances.

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
    <div class="container containergrey">

<?php
$date = new DateTime(null, new DateTimeZone('Europe/Paris'));
$dateconcours = new DateTime('2021-05-06 12:00:00');
//test pour afficher les liens
//$dateconcours = new DateTime('2021-04-27 12:00:00');
if ($date>=$dateconcours) {
                    echo '
                    <p style="text-align: center;">Accédez au <a href="download.php?path=sujets/sujet3.pdf">sujet du concours</a>.</p>
                    <p style="text-align: center;">Téléchargez les <a href="download.php?path=sujets/sujet3.zip">instances</a> du sujet.</p>
                ';
                } else {
                    echo '<p style="text-align: center;">Le sujet et les instances apparaîtront ici au début de l\'épreuve, le <B>jeudi 6 mai 2021 à 12h</B>.</p>';
                }
?>

    </div>
</div>

<?php
include("footer.php")
?>
