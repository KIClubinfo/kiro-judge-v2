<?php
include("config.php");

if (!isset($_SESSION["user"])){
    header('Location: index.php?not_connected');
    exit();
}
  
include("date_protection.php");
protect_before($dateconcours, $datefinconcours);

include("header.php");
include("navbar.php");
?>
    <!-- Masthead-->
    <header class="masthead" >
        <div class="container-fluid">
            <div class="row">
                <?php include("menuconcours.php");?>
                <div class="col-lg-8">
                    <div class="container">
                        <div class="box-concours" style="padding-top:2rem;">
                            <h3 style="color:black;">Contact :</h3>
                            <p style="color:#2f2f2f; font-size:large;">Vous trouverez ci-dessous tous les contacts dont vous aurez besoin en cas de problème :</p>
                            <ul class="list-centered">
                                <li style="color:black; font-size:large;">Contact 1 : kiro.enpc@gmail.com</li>
                                <li style="color:black; font-size:large;">Contact 2 : 07.50.24.52.18 || Pour les questions générales</li>
                                <li style="color:black; font-size:large;">Contact 3 : 07.81.70.52.38 || Pour les problèmes avec Discord</li>
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