<?php
// Route kiro.enpc.org/concours.php
// Le site ou l'on peut trouver le sujet, poster des solutions et autre.

include("config.php");

if (!is_admin()) {
                  header('Location: index.php?ns');
                  exit();
}


?>
