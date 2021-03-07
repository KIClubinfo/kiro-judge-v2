<?php
include("config.php");

if (is_admin()) { //Si l'utilisateur est un admin
  if (isset($_GET['id']) and !empty($_GET['id']) and is_numeric($_GET['id'])) { //Si on sait déja l'id de l'user et qu'il est valide
    if (isset($_POST['submit'])) { //Posté le changement
      if (
        isset($_POST['prenom']) and !empty($_POST['prenom']) and
        isset($_POST['nom']) and !empty($_POST['nom']) and
        isset($_POST['ecole']) and !empty($_POST['ecole']) and
        isset($_POST['tel']) and !empty($_POST['tel']) and
        isset($_POST['token_csrf']) and !empty($_POST['token_csrf'])
      ) {

        if (is_string($_POST['prenom']) and is_string($_POST['nom']) and is_string($_POST['ecole']) and is_string($_POST['tel']) and is_string($_POST['token_csrf'])) {

          if ($_POST['token_csrf'] === $_SESSION['token_csrf']) {

            if (strlen($_POST['email']) <= 255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email'])) {

              if (
                strlen($_POST['nom']) <= 100 and
                strlen($_POST['prenom']) <= 100 and
                strlen($_POST['ecole']) <= 300
              ) {

                if (strlen($_POST['tel']) <= 15 and preg_match("#^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$#", $_POST['tel'])) {

                  $email = str_replace(array("\n", "\r", PHP_EOL), '', $_POST['email']); //faille CRLF
                  $safe_email = sanitize_string($email);
                  $safe_prenom = sanitize_string($_POST['prenom']);
                  $safe_nom = sanitize_string($_POST['nom']);
                  $safe_tel = sanitize_string($_POST['tel']);
                  $safe_email = sanitize_string($_POST['email']);
                  $safe_ecole = sanitize_string($_POST['ecole']);

                  if ($req4 = $conn->prepare("SELECT * FROM users WHERE (mail=? AND id !=?) ")) { //Savoir si compte existe
                    $req4->bind_param("si", $safe_email, intval($_GET['id']));
                    $req4->execute();
                    $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                    $req4->close();
                    if (empty($result4)) {
                      if ($req3 = $conn->prepare("UPDATE users SET prenom=?,nom=?,mail=?,ecole=?,tel=? WHERE id=?")) { //Si la team n'est pas encore valide on la valide
                        $req3->bind_param("sssssi", $safe_prenom, $safe_nom, $safe_email, $safe_ecole, $safe_tel, intval($_GET['id']));
                        $req3->execute();
                        $req3->close();
                        header('Location: index.php?maj_admin');
                        exit();
                      } else {
                        $erreur2 = "Erreur lors de la mise à jour des données.";
                      }
                    } else {
                      $erreur2 = "Un compte existe déjà avec cette adresse mail.";
                    }
                  } else {
                    $erreur2 = "Erreur lors de la selection des users.";
                  }
                } else {
                  $erreur2 = "Le numéro de télephone n'est pas valable.";
                }
              } else {
                $erreur2 = "Un des champs est trop long.";
              }
            } else {
              $erreur2 = "L'email n'est pas sous le bon format.";
            }
          } else {
            $erreur2 = "Le jeton CSRF est invalide.";
          }
        } else {
          $erreur2 = "Vous n'avez pas envoyé des chaînes de caractères.";
        }
      } else {
        $erreur2 = "Vous n'avez pas rempli tous les champs.";
      }
    } else { //formulaire modif user
      $id_user = intval(sanitize_string($_GET['id']));
      $user = new user($id_user);
      if (!empty($user->prenom)) { //Si un user existe
        $token_csrf = bin2hex(random_bytes(32)); //empecher attaque xss
        $_SESSION['token_csrf'] = $token_csrf;
        include("header.php");
?>
        <div class="content">
          <div class="container">
            <form action="" method="post">
              <legend>
                <div class="number">1</div> Informations personnelles
              </legend>
              <label for="prenom">Prénom :</label>
              <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user->prenom); ?>" maxlength="100" required> <br />
              <label for="nom">Nom de famille:</label>
              <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user->nom); ?>" maxlength="100" required> <br />
              <label for="ecole">École :</label>
              <input type="text" id="ecole" name="ecole" value="<?php echo htmlspecialchars($user->ecole); ?>" maxlength="300" required> <br />
              <legend>
                <div class="number">2</div> Contact
              </legend>
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->mail); ?>" maxlength="255" required> <br />
              <label for="tel">Numéro de téléphone:</label>
              <input type="tel" id="tel" name="tel" omaxlength="15" value="<?php echo htmlspecialchars($user->tel); ?>" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />
              <input type="hidden" name="token_csrf" id="token_csrf" value="<?php echo $token_csrf; ?>"> <br />
              <input type="submit" name="submit" value="Modifier les informations">
            </form>
          </div>
        </div>
      <?php

      } else {

        include("header.php");
      ?>
        <div class="content">
          <div class="erreur">Pas d'utilisateur avec cet id.</div>
        </div>

      <?php
      }
    }
  } else { //On cherche l'id de l'user
    include("header.php");
    if ($req = $conn->prepare("SELECT id,id_team FROM users")) { //requete préparée
      $req->execute();
      $result = $req->get_result()->fetch_all(MYSQLI_ASSOC); //resulats de la requête
      $req->close();
      ?>
      <table border="4">
        <thead>
          <tr>
            <th>Id équipe</th>
            <th>Nom d'équipe</th>
            <th>Id</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Mail</th>
            <th>Téléphone</th>
            <th>École</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($result as $user) { //On affiche tous les users
            echo '<tr>';
            $team = new team($user["id_team"]);
            $player = new user($user["id"]);
            echo '<td><a href="teams.php?id_team=' . htmlspecialchars($team->id) . '">' . htmlspecialchars($team->id) . '</a></td>';
            echo '<td>' . htmlspecialchars($team->nom) . '</td>';
            echo '<td><a href="edit_info_admin.php?id=' . htmlspecialchars($player->id) . '">' . htmlspecialchars($player->id) . '</a></td>';
            echo '<td>' . htmlspecialchars($player->prenom) . '</td>';
            echo '<td>' . htmlspecialchars($player->nom) . '</td>';
            echo '<td>' . htmlspecialchars($player->mail) . '</td>';
            echo '<td>' . htmlspecialchars($player->tel) . '</td>';
            echo '<td>' . htmlspecialchars($player->ecole) . '</td>';
            echo '</tr>';
          } ?>
        </tbody>
      </table>
    <?php
    } else {
      $erreur = "Erreur lors de la récupération des données";
    }
  }
  if (isset($erreur2)) { //Si erreur dans le formulaire
    $id_user = intval(sanitize_string($_GET['id']));
    $user = new user($id_user);
    $token_csrf = bin2hex(random_bytes(32)); //empecher attaque xss
    $_SESSION['token_csrf'] = $token_csrf;
    include("header.php");
    ?>

    <div class="content">
      <div class="container">
        <form action="" method="post">
          <div class="erreur"><?php echo $erreur2; ?> </div>
          <legend>
            <div class="number">1</div> Informations personnelles
          </legend>
          <label for="prenom">Prénom :</label>
          <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user->prenom); ?>" maxlength="100" required> <br />
          <label for="nom">Nom de famille:</label>
          <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user->nom); ?>" maxlength="100" required> <br />
          <label for="ecole">École :</label>
          <input type="text" id="ecole" name="ecole" value="<?php echo htmlspecialchars($user->ecole); ?>" maxlength="300" required> <br />
          <legend>
            <div class="number">2</div> Contact
          </legend>
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->mail); ?>" maxlength="255" required> <br />
          <label for="tel">Numéro de téléphone:</label>
          <input type="tel" id="tel" name="tel" omaxlength="15" value="<?php echo htmlspecialchars($user->tel); ?>" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />
          <input type="hidden" name="token_csrf" id="token_csrf" value="<?php echo $token_csrf; ?>"> <br />
          <input type="submit" name="submit" value="Modifier les informations">
        </form>
      </div>
    </div>
  <?php
  }
} else { //Pas un admin
  header('Location: index.php');
  exit();
}


if (isset($erreur)) {
  include("header.php");
  ?>
  <div class="content">
    <div class="erreur"><?php echo $erreur; ?></div>
  </div>
<?php

}


include("footer.php");
?>