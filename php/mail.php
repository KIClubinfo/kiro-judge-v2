<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

require('send_mail.php');

send_mail("antonin.parrot@eleves.enpc.fr", "Suceur de queue", "Jean-Loup", "Pierre", "Glandon", "Mathis", "Girard", "THIS_IS_ID", "archlinux_is_the_best");
