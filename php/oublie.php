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
                $req3->bind_param("si", $ready_password, intval($result['id']));
                $req3->execute();
                $req3->close();


                //TODO: Envoyer mail avec nouveau mot de passe
                //header('Location: index.php?change');

                echo $password;
                //exit();
              } else {
                $erreur = "Erreur lors de la mise à jour du mot de passe.";
              }
            } else {
              $erreur = "L'email n'éxiste pas.";
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
  } else { //formulaire non envoyé
    include("header.php");
?>
    <div class="content">
      <div class="container">
        <form action="" method="post">
          Nous allons vous envoyer un nouveau mot de passe par mail. <br />
          <label for="mail">Email :</label>
          <input maxlength="255" type="email" name="email" required><br />
          <input type="submit" name="submit" value="Se connecter">
        </form>
      </div>
    </div>
  <?php
  }
} else {
  include("header.php");
  ?>
  <div class="content">
    <div class="erreur">Vous êtes déjà connecté.</div>
  </div>
<?php
}

if (isset($erreur)) {
  //si on doit afficher le formulaire avec un message d'erreur
  include("header.php");
?>
  <div class="content">
    <div class="container">
      <div class="erreur"><?php echo $erreur; ?></div>
      <form action="" method="post">
        Nous allons vous envoyer un nouveau mot de passe par mail. <br />
        <label for="mail">Email :</label>
        <input maxlength="255" type="email" name="email" value="<?php if (isset($_POST['email'])) {
                                                                  echo htmlspecialchars($_POST['email']);
                                                                } ?>" required><br />
        <input type="submit" name="submit" value="Se connecter">
      </form>
    </div>
  </div>

<?php
}

?>


<?php
include("footer.php");
?>