<?php
// Route kiro.enpc.org/sujets.php
// Le site ou l'on peut trouver le sujet et les instances.

include("config.php");


if (!isset($_SESSION["user"])){
  header('Location: index.php?not_connected');
  exit();
}

include("date_protection.php");
$dateconcours = new DateTime('2021-05-06 11:30:00');
protect_before($dateconcours);

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
            <h2 style="font-size: 2.7em; margin-bottom:30px;">Sujet du concours</h2>
            <?php
            $date = new DateTime(null, new DateTimeZone('Europe/Paris'));
            $dateconcours = new DateTime('2021-05-06 12:00:00');

            if ($date>=$dateconcours or is_admin()) {
                                echo '
                                <span class="byline">Accédez au <a href="download.php?path=sujets/sujet4.pdf">sujet du concours</a>.<br><br></span>
                                
                                <span class="byline">Téléchargez les <a href="download.php?path=sujets/sujet4.zip">instances</a> du sujet.</span>
                            ';
                            } else {
                                echo '<span class="byline">Le sujet et les instances apparaîtront ici au début de l\'épreuve : <br> Le <B>jeudi 6 mai 2021 à 12h</B>.</span>';
                            }
            ?>
        </div>
    </section>
</div>

<?php
include("footer.php");
?>
