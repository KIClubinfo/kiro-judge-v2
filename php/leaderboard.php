<?php
include("config.php");
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
                            <h3 style="color:black;">Leaderboard :</h3>
                            <p style="color:#2f2f2f; font-size:large;">Vous trouverez ci-dessous le classement en temps réel :</p>
                            <div id="leaderboard">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <script src="scripts/runtime-main.7f94bd84.js"></script>
    <script src="scripts/2.1477e5ea.chunk.js"></script>
    <script src="scripts/main.a424d41f.chunk.js"></script>
<?php
include("footer.php");
?>
