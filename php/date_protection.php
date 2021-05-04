<?php

function protect_before($dateconcours) {
    $date = new DateTime(null, new DateTimeZone('Europe/Paris'));
    if ($date<$dateconcours and !is_admin()) {
        header('Location: index.php?ns');
        exit;
    }
}
