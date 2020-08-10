<?php
include("../config.php");
header('Content-type: application/json');

if (isset($_SESSION['user'])){ //Si connecté
    http_response_code(200);
    $data = $_SESSION['user'];
} else {
    http_response_code(401);
    $data = [];
}

echo(json_encode($data));

