<?php
include("config.php");

if (isset($_SESSION['user'])) { //Si connecté
  session_destroy();
  header('Location: index.php?deco');
  exit();
} else { //Sinon
  header('Location: index.php');
  exit();
}
