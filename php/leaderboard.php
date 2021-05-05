<?php
include("config.php");
include("header.php");
include("navbar.php");
?>

    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="styletest.css"/>
    </head>

    <div class="content limiter" style="min-height: 100%; margin-top:15vh">
        <?php
        include("menuconcours.php");
        ?>
        <section class="concours">
            <div class="title" style="margin-top:20px; text-align:center;">
                <h3 style="font-size: 2.2em">LEADERBOARD (PLUS LE SCORE EST BAS MIEUX C'EST)</h3>
                <div id="leaderboard"></div>
            </div>
        </section>


                <script src="scripts/runtime-main.7f94bd84.js"></script>
                <script src="scripts/2.1477e5ea.chunk.js"></script>
                <script src="scripts/main.a424d41f.chunk.js"></script>

            </div>
    </div>

<?php
include("footer.php")
?>