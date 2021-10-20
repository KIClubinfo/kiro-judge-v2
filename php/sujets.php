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
                            $dateconcours = new DateTime('2021-05-06 12:00:00');

                            if ($date>=$dateconcours) {//AJOUTER UN or is_admin() ET LES LIENS
                                                echo '
                                                <span style="color:black; font-size:large;">
                                                Accédez au <a style="font-weight:700;" href="">sujet du concours</a>.</br>
                                                Téléchargez les <a style="font-weight:700;" href="">instances</a> du sujet.
                                                </span>
                                            ';
                                            } else {
                                                echo '<span style="color:black; font-size:large;">Le sujet et les instances apparaîtront ici au début de l\'épreuve : <br> Le <B>jeudi 6 mai 2021 à 12h</B>.</span>';
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
