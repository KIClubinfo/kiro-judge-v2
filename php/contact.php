<?php
include("config.php");
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
        <h2 style="font-size: 2.7em">Contact</h2>
        <span class="byline">Vous trouverez ci-dessous tous les contacts dont vous aurez besoin en cas de problème :</span>
        <ul style="text-align:left; margin-left:300px;">
            <li class="byline" style="color:black;">Contact 1 : adresse@enpc.fr</li>
            <li class="byline" style="color:black;">Contact 2 : adresse@enpc.fr</li>
            <li class="byline" style="color:black;">Contact 3 : adresse@enpc.fr</li>
        </ul>
      </div>
    </section>
</div>

<?php
include("footer.php");
?>