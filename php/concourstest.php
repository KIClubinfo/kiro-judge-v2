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
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styletest.css" />
</head>

<div class="content" style="margin-top: 15vh">
    
    <?php
    include("menuconcours.php");
    ?>

    <section class="concours">
        <div class="title" style="margin-top:20px; text-align:center;">
            <h2 style="font-size: 2.7em">Concours</h2>
            <span class="byline">Vous êtes sur la page du concours.<br></span>
            <span class="byline">Vous trouverez sur cette page toutes les informations relatives au concours le jour J.<br><br></span>
            <ul style="text-align:left; list-style-type:disc; margin-left:40px;">
                <li class="byline" style="color:black; font-size:x-large;">Pour accéder au sujet et aux instances associées, rendez-vous dans l'onglet "Sujet"</li>
                <li class="byline" style="color:black; font-size:x-large;">Pour uploader votre solution, rendez-vous dans l'onglet "Upload"</li>
                <li class="byline" style="color:black; font-size:x-large;">Pour visualiser le classement en temps réel, rendez-vous dans l'onglet "Classement"</li>
                <li class="byline" style="color:black; font-size:x-large;">Pour contacter le KI en cas de problème, rendez-vous dans l'onglet "Contact"</li>
            </ul>
        </div>
    </section>
</div>

<?php
include("footer.php");
?>