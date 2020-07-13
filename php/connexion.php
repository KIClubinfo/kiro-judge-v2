<?php
include("config.php");

if (!(isset($_SESSION['user']))){ //Si l'utilisateur n'est pas connecté
  if (isset($_POST['submit'])){ //Il a envoyé le formulaire
    if  (isset($_POST['email']) and !empty($_POST['email']) and isset($_POST['password']) and !empty($_POST['password'])){ //Si il a bien tout rempli
      if (is_string($_POST['email']) and is_string($_POST['password'])){ //il a bien envoyé des chaines de caractères
        if (strlen($_POST['email'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email'])){ //Son email est valide

          $safe_email = sanitize_string($_POST['email']);
          $ready_password = sanitize_string($_POST['password']);

          if ($req = $conn->prepare("SELECT * FROM users WHERE email=?")) {
            $req->bind_param("s", $safe_email);
            $req->execute();
            $result = $req->get_result()->fetch_array(MYSQLI_ASSOC);
            $req->close();
            if (!empty($result) and password_verify($ready_password, $result['password'])){ //l'utilisateur a le bon mot de passe
              if (!$result['mdp_a_changer']){ //s'il ne doit pas modifier son mot de passe


              }
              else{
                ?>
                php formulaire changer son mot de passe
                <?php
              }

            }
            else{
              $erreur = "Couple email/mot de passe incorrect.";
            }
          }
          else{
            $erreur = "Erreur lors du traitement de la requête.";
          }
        }
        else{
          $erreur = "Votre email n'est pas dans le bon format ou est trop long (255 caractères maximum).";
        }

      }
      else{
        $erreur = "Vous n'avez pas envoyé des chaînes de caractères.";
      }

    }
    else{
      $erreur = "Vous n'avez pas rempli tous les champs.";
    }

  }
  else{ //formulaire non envoyé
    ?>
    <form action="" method="post">
      <label for="mail">Email :</label>
      <input maxlength="255" type="email" name="email" required><br />
      <label for="password">Mot de passe :</label>
      <input minlength="6" type="password" name="password" required><br />
      <input type="submit" name="submit" value="Se connecter">
    </form>
    <?php
  }

}
else{
    ?>
    Vous êtes déjà connectés.
  <?php
}




?>

<label for="password">Mot de passe (6 caractères minimum):</label>
<input minlength="6" type="password" name="password" onchange='validatePassword();' required>
<label for="password-verif">Mot de passe (vérification):</label>
<input minlength="6" type="password" name="password-verif" onchange='validatePassword();' required>

<?php
include("footer.php");
?>
