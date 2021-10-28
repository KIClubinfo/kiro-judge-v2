<?php
// Route kiro.enpc.org/sujets.php
// Le site ou l'on peut trouver le sujet et les instances.

include("config.php");


if (!isset($_SESSION["user"])){
  header('Location: index.php?not_connected');
  exit();
}

include("header.php");
include("navbar.php");

?>
    <!-- Masthead-->
    <header class="masthead" >
        <div class="container-fluid">
            <div class="row">
                <?php include("menuconcours.php");?>
                <div class="col-lg-8" id="main">
                    <div class="container">
                        <div class="box-concours" style="padding-top:2rem;">
                            <h3 style="color:black;">Sujet du concours :</h3>
                            <p style="color:#2f2f2f; font-size:large;">Vous trouverez ci-dessous le sujet du concours :</p>
                            <?php
                            $date = new DateTime(null, new DateTimeZone('Europe/Paris'));

                            if ($date>=$dateconcours) {
                                                echo '
                                                <span style="color:black; font-size:large;">
                                                Accédez au <a style="font-weight:700;" href="download.php?path=/var/www/html/sujet_concours/sujet.pdf">sujet du concours</a>.</br>
                                                Téléchargez les <a style="font-weight:700;" href="download.php?path=/var/www/html/sujet_concours/sujet.zip">instances</a> du sujet.
                                                </span>
                                            ';
                                            } else {
                                                echo '<span style="color:black; font-size:large;">Le sujet et les instances apparaîtront ici au début de l\'épreuve : <br> Le <B>jeudi 25 novembre 2021 à 14h</B>.</span>';
                                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php
include("footer.php");
?>
