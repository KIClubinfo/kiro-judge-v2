<?php
// Check if we can display concours pages
include("../config.php");

if (!is_admin()) {
    header('Location: ../index.php?ns');
    exit();
}
