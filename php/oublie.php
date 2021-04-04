<?php
include("config.php");


if (!(isset($_SESSION['user']))) { //Si l'utilisateur n'est pas connecté
  if (isset($_POST['submit'])) { //Il a envoyé le formulaire
    if (isset($_POST['email']) and !empty($_POST['email'])) { //Si il a bien tout rempli
      if (is_string($_POST['email'])) { //il a bien envoyé des chaines de caractères
        if (strlen($_POST['email']) <= 255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email'])) { //Son email est valide

          $safe_email = sanitize_string($_POST['email']);

          if ($req = $conn->prepare("SELECT * FROM users WHERE mail=?")) { //verifie si un compte existe
            $req->bind_param("s", $safe_email);
            $req->execute();
            $result = $req->get_result()->fetch_array(MYSQLI_ASSOC);
            $req->close();
            if (!empty($result)) { //le mail existe bel et bien

              $password = bin2hex(random_bytes(9)); //On génère un mot de passe aléatoire
              $ready_password = password_hash($password, PASSWORD_BCRYPT);

              if ($req3 = $conn->prepare("UPDATE users SET password=?,mdp_a_changer=1 WHERE id=?")) { //Si la team n'est pas encore valide on la valide
                $id =  intval($result['id']);
                $req3->bind_param("si", $ready_password, $id);
                $req3->execute();
                $req3->close();
                
                include("send_mail.php");
                send_password($safe_email, $password, sanitize_string($result['prenom']));
                header('Location: /index.php?change');
                exit();

              } else {
                $erreur = "Erreur lors de la mise à jour du mot de passe.";
              }
            } else {
              $erreur = "L'email n'existe pas.";
            }
          } else {
            $erreur = "Erreur lors du traitement de la requête.";
          }
        } else {
          $erreur = "Votre email n'est pas dans le bon format ou est trop long (255 caractères maximum).";
        }
      } else {
        $erreur = "Vous n'avez pas envoyé de chaîne de caractère.";
      }
    } else {
      $erreur = "Vous n'avez pas rempli tous les champs.";
    }
  } else { //formulaire non envoyé
    include("header.php");
    include("navbar.php");
?>
    <div class="content" style="min-height: 70%; margin-top: 20vh">
      <div class="container containergrey">
        <form action="" method="post">
          <p style="text-align: center">Nous allons vous envoyer un nouveau mot de passe par mail. <br/></p>
          <br/>
          <label for="mail">Email :</label>
          <input maxlength="255" type="email" name="email" required><br />
          <input type="submit" name="submit" value="Réinitialiser votre mot de passe">
        </form>
      </div>
    </div>
  <?php
  }
} else {

  header('Location: /index.php?already_co');
  exit();
}

if (isset($erreur)) {
  //si on doit afficher le formulaire avec un message d'erreur
  include("header.php");
  include("navbar.php");
  popup($erreur, 6000, "error");
?>
  <div class="content" style="min-height: 70%;  margin-top: 20vh">
    <div class="container containergrey">
      <form action="" method="post">
        <p style="text-align: center">Nous allons vous envoyer un nouveau mot de passe par mail. <br/></p>
        <br/>
        <label for="mail">Email :</label>
        <input maxlength="255" type="email" name="email" value="<?php if (isset($_POST['email'])) {
                                                                  echo htmlspecialchars($_POST['email']);
                                                                } ?>" required><br />
        <input type="submit" name="submit" value="Réinitialiser votre mot de passe">
      </form>
    </div>
  </div>

<?php
}

include("footer.php");
?>
