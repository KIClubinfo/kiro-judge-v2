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
                    <div class="container" style="margin-bottom:2rem;">
                        <div class="box-concours" style="padding-top:2rem;">
                            <h3 style="color:black;">Contact :</h3>
                            <p style="color:#2f2f2f; font-size:large;">Vous trouverez ci-dessous tous les contacts dont vous aurez besoin en cas de problème :</p>
                            <ul class="list-centered">
                                <li style="color:black; font-size:large;">Contact-mail 1 : faustine.delorme@eleves.enpc.fr</li>
                                <li style="color:black; font-size:large;">Contact-mail 2 : anwar.kardid@eleves.enpc.fr</li>
                                <li style="color:black; font-size:large;">Contact-mail 2 : Charles.DE-BOURGOING@eleves.enpc.fr</li>
                                <li style="color:black; font-size:large;">Contact-num  1 : +33 6 18 46 22 05 Faustine D.|| Pour les questions générales</li>
                                <li style="color:black; font-size:large;">Contact-num  2 : +33 6 52 83 00 14 Thomas L.  || Pour les problèmes techniques</li>
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