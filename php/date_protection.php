<?php

function protect_before($dateconcours,$datefinconcours) {
    $date = new DateTime(null, new DateTimeZone('Europe/Paris'));
    if ($date<$dateconcours and !is_admin()) {
        header('Location: index.php?ns');
        exit;
    }
    if($date>$datefinconcours and !is_admin()){
    	header('Location: index.php?finished');
        exit;
    }
}

function protect_freeze($dateconcours,$datefinconcours) {
    $date = new DateTime(null, new DateTimeZone('Europe/Paris'));
    if ($date<$dateconcours and !is_admin()) {
        header('Location: index.php?ns');
        exit;
    }
    if($date>$datefinconcours and !is_admin()){
    	header('Location: index.php?freeze_leaderboard');
        exit;
    }
}

?>
