<?php
// Route kiro.enpc.org/concours.php
// Le site ou l'on peut trouver le sujet, poster des solutions et autre.

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
                <?php include("menuconcours.php"); ?>
                <div class="col-lg-8">
                    <div class="container">
                        <div class="box-concours" style="padding-top:2rem;">
                            <h3 style="color:black;">Bienvenue dans l'espace dédié au concours :</h3>
                            <ul class="list-centered">
                                <li style="color:black; font-size:large;">Pour accéder au sujet et aux instances associées, rendez-vous dans l'onglet "Sujet"</li>
                                <li style="color:black; font-size:large;">Pour uploader votre solution, rendez-vous dans l'onglet "Upload des instances"</li>
                                <li style="color:black; font-size:large;">Pour visualiser le classement en temps réel, rendez-vous dans l'onglet "Classement"</li>
                                <li style="color:black; font-size:large;">Pour contacter le KI en cas de problème, rendez-vous dans l'onglet "Contact"</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php
include("footer.php");
?>
