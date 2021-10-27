<?php
include("config.php");


if (!(isset($_SESSION['user']))) { //Si l'utilisateur n'est pas connecté
  if (isset($_POST['submit'])) { //Il a envoyé le formulaire
    if (isset($_POST['email']) and !empty($_POST['email']) and isset($_POST['password']) and !empty($_POST['password'])) { //Si il a bien tout rempli
      if (is_string($_POST['email']) and is_string($_POST['password'])) { //il a bien envoyé des chaines de caractères
        if (strlen($_POST['email']) <= 255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email'])) { //Son email est valide

          $safe_email = sanitize_string($_POST['email']);
          $ready_password = sanitize_string($_POST['password']);

          if ($req = $conn->prepare("SELECT * FROM users WHERE mail=?")) {
            $req->bind_param("s", $safe_email);
            $req->execute();
            $result = $req->get_result()->fetch_array(MYSQLI_ASSOC);
            $req->close();
            if (!empty($result) and password_verify($ready_password, $result['password'])) { //l'utilisateur a le bon mot de passe
              if (!$result['mdp_a_changer']) { //s'il ne doit pas modifier son mot de passe
                if ($req3 = $conn->prepare("UPDATE teams SET valide=1 WHERE id=?")) { //Si la team n'est pas encore valide on la valide
                  $id_team = intval($result['id_team']);
                  $req3->bind_param("i", $id_team);
                  $req3->execute();
                  $req3->close();
                  $user = new user($result['id']); // mettre en session
                  $_SESSION['user'] = $user;
                  $team = new team($result['id_team']);
                  $_SESSION['team'] = $team;
                  if ($_SESSION['user']->admin === 0){
                    header('Location: index.php?co2');
                    exit();
                  } else {
                      header('Location: index.php?co2');
                      exit();
                  }
                } else {
                  $erreur = "Erreur lors de la mise à jour du status de l'équipe.";
                }
              } else { //Doit changer son mot de passe
                $_SESSION['id'] = intval($result['id']); //ca sera utile pour modifier le mot de passe
                $_SESSION['id_team'] = intval($result['id_team']); //ca sera utile pour modifier le mot de passe
                include("header.php");
                include("navbar.php");
?>
                <header class="masthead">
                    <div class="container" style="max-width:45rem;">
                        <form class="box" action="" method="post">
                            <?php 
                            echo 
                              '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                Vous devez modifier votre mot de passe
                              </div>';
                            ?>
                            <h4 class="">Vous devez modifier votre mot de passe</h4>
                            <div class="form-group">
                              <label for="password" class="form-label mt-4">Nouveau mot de passe (6 caractères minimum) :</label>
                              <input minlength="6" type="password" name="password" class="form-control" placeholder="Saisissez votre nouveau mot de passe" onchange='validatePassword();' required>
                            </div>
                            <div class="form-group">
                              <label for="password-verif" class="form-label mt-4">Confirmez le nouveau mot de passe :</label>
                              <input minlength="6" type="password" name="password-verif" class="form-control" placeholder="Veuillez confirmer votre nouveau mot de passe" onchange='validatePassword();' required>
                            </div>
                            <!--<input type="submit" name="submit22" value="Changer mon mot de passe">-->
                            <button type="submit" name="submit22"class="btn btn-info">Changer mon mot de passe</button>
                        </form>
                    </div>
                </header>
    <?php
              }
            } else {
              $erreur = "Couple email/mot de passe incorrect.";
            }
          } else {
            $erreur = "Erreur lors du traitement de la requête.";
          }
        } else {
          $erreur = "Votre email n'est pas dans le bon format ou est trop long (255 caractères maximum).";
        }
      } else {
        $erreur = "Vous n'avez pas envoyé des chaînes de caractères.";
      }
    } else {
      $erreur = "Vous n'avez pas rempli tous les champs.";
    }
  } else if (!isset($_POST['submit22'])) { //formulaire non envoyé
    include("header.php");
    include("navbar.php");

    ?>
    <header class="masthead">
        <div class="container" style="max-width:45rem;">
            <form class="box" action="" method="post">
                <div class="form-group">
                  <label for="email" class="form-label mt-4">Adresse Email :</label>
                  <input maxlength="255" type="email" name="email" required class="form-control" placeholder="Saisissez votre email">
                </div>
                <div class="form-group">
                  <label for="password" class="form-label mt-4">Mot de passe :</label>
                  <input minlength="6" type="password" name="password" required class="form-control" placeholder="Saisissez votre mot de passe">
                </div>
                <div class="padding:1rem;">
                  <a href="oublie.php">Mot de passe oublié ?</a>
                </div>
                <!--<input type="submit" name="submit" value="Se connecter">-->
                <button type="submit" name="submit" class="btn btn-info">Se connecter</button>
            </form>
        </div>
    </header>
  <?php
  }
} else { //deja connecte
  header('Location: /index.php?already_co');
  exit();


}

if (isset($erreur)) {
  //si on doit afficher le formulaire avec un message d'erreur
  include("header.php");
  include("navbar.php");
  echo 
    '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      '.$erreur.'
    </div>';

?>
  <header class="masthead">
        <div class="container" style="max-width:45rem;">
            <form class="box" action="" method="post">
                <div class="form-group">
                  <label for="email" class="form-label mt-4">Adresse Email :</label>
                  <input maxlength="255" type="email" name="email" required class="form-control" value="<?php if (isset($_POST['email'])) {echo htmlspecialchars($_POST['email']);} ?>" required>
                </div>
                <div class="form-group">
                  <label for="password" class="form-label mt-4">Mot de passe :</label>
                  <input minlength="6" type="password" name="password" required class="form-control" placeholder="Saisissez votre mot de passe">
                </div>
                <div class="padding:1rem;">
                  <a href="oublie.php">Mot de passe oublié ?</a>
                </div>
                <!--<input type="submit" name="submit" value="Se connecter">-->
                <button type="submit" name="submit" class="btn btn-info">Se connecter</button>
            </form>
        </div>
    </header>
<?php
}


if (!(isset($_SESSION['user'])) and isset($_POST['submit22']) and (isset($_SESSION['id']))) {  //S'il a envoyé le changement de mot de passe
  if (isset($_POST['password-verif']) and !empty($_POST['password-verif']) and isset($_POST['password']) and !empty($_POST['password'])) { //Si il a bien tout rempli
    if (is_string($_POST['password']) and is_string($_POST['password-verif'])) { //il a bien envoyé des chaines de caractères
      $safe_password = sanitize_string($_POST['password']);
      $safe_password_verif = sanitize_string($_POST['password-verif']);
      $ready_password = password_hash($safe_password, PASSWORD_BCRYPT);

      if ($safe_password === $safe_password_verif) { //password correpondent
        if ($req3 = $conn->prepare("UPDATE users SET password =?, mdp_a_changer=0 WHERE id=?")) { //Il pourra désormais se connecter
          $req3->bind_param("si", $ready_password, $_SESSION['id']);
          $req3->execute();
          $req3->close();
          $user = new user($_SESSION['id']); // mettre en session
          $_SESSION['user'] = $user;
          $team = new team($_SESSION['id_team']);
          $_SESSION['team'] = $team;
          unset($_SESSION['id']); //On supprime cette variable et on connecte l'user
          unset($_SESSION['id_team']); //On supprime cette variable et on connecte l'user
          if ($_SESSION['user']->admin === 0){
            header('Location: index.php?co2');
            exit();
          }


        } else {
          $erreur22 = "Erreur lors de la mise à jour du mot de passe.";
        }
      } else {
        $erreur22 = "Les deux mots de passe ne correspondent pas.";
      }
    } else {
      $erreur22 = "Vous n'avez pas entré des chaînes de caractères.";
    }
  } else {
    $erreur22 = "Vous n'avez pas rempli tous les champs.";
  }
}

if (isset($erreur22)) {
  include("header.php");
  include("navbar.php");
  echo 
    '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      '.$erreur22.'
    </div>';
?>
  <header class="masthead">
      <div class="container" style="max-width:45rem;">
          <form class="box" action="" method="post">
              <h4 class="">Vous devez modifier votre mot de passe</h4>
              <div class="form-group">
                <label for="password" class="form-label mt-4">Nouveau mot de passe (6 caractères minimum) :</label>
                <input minlength="6" type="password" name="password" class="form-control" placeholder="Saisissez votre nouveau mot de passe" onchange='validatePassword();' required>
              </div>
              <div class="form-group">
                <label for="password-verif" class="form-label mt-4">Confirmez le nouveau mot de passe :</label>
                <input minlength="6" type="password" name="password-verif" class="form-control" placeholder="Veuillez confirmer votre nouveau mot de passe" onchange='validatePassword();' required>
              </div>
              <!--<input type="submit" name="submit22" value="Changer mon mot de passe">-->
              <button type="submit" name="submit22"class="btn btn-info">Changer mon mot de passe</button>
          </form>
      </div>
  </header>
<?php
}
?>


<?php
include("footer.php");
?>
