<?php
include("config.php");


if (!(isset($_SESSION['user']))) { //Si l'utilisateur n'est pas connecté
  if (isset($_POST['submit'])) { //Il a envoyé le formulaire
    if (isset($_POST['email']) and !empty($_POST['email'])) { //Si il a bien tout rempli
      if (is_string($_POST['email'])) { //il a bien envoyé des chaines de caractères

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
