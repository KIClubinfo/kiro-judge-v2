<?php

$db_password = $_ENV["mysql_password"];

$conn = new mysqli('db', 'kiro_user', $db_password,'kiro');

if($conn->connect_error){
  die('Erreur lors de la connection à la base de donnée: ' .$conn->connect_error);
}



echo "It is working; I do not know why";

phpinfo();
?>
