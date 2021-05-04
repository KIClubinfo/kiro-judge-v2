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
        <h2 style="font-size: 2.7em">Upload des Instances</h2>
        <span class="byline">Vous pouvez déposer vos instances en cliquant sur le bouton ci-dessous :</span>
      </div>
      <ul class="actions">
        <li><button class="button" onclick="self.location.href='drag/tamplate.html'">Upload</button></li>
        <br>
      </ul>
    </section>
</div>

<?php
include("footer.php");
?>