<?php
include("config.php");


if (!(isset($_SESSION['user']))) { //vérifie que l'utilisateur n'est pas connecté

  if ($aujourdhui < $date_limite_inscription) { //Date inscription

    if (isset($_POST['submit'])) { //Il a envoyé le formulaire

      if (
        isset($_POST['prenom-1']) and !empty($_POST['prenom-1']) and isset($_POST['prenom-2']) and !empty($_POST['prenom-2']) and isset($_POST['prenom-3'])
        and !empty($_POST['prenom-3']) and
        isset($_POST['nom-1']) and !empty($_POST['nom-1']) and isset($_POST['nom-2']) and !empty($_POST['nom-2']) and isset($_POST['nom-3'])
        and !empty($_POST['nom-3']) and
        isset($_POST['email-1']) and !empty($_POST['email-1']) and isset($_POST['email-2']) and !empty($_POST['email-2']) and isset($_POST['email-3'])
        and !empty($_POST['email-3']) and
        isset($_POST['tel-1']) and !empty($_POST['tel-1']) and isset($_POST['tel-2']) and !empty($_POST['tel-2']) and isset($_POST['tel-3'])
        and !empty($_POST['tel-3'])  and
        isset($_POST['team-name']) and !empty($_POST['team-name']) and isset($_POST['team-hub'])  and !empty($_POST['team-hub']) and
        isset($_POST['ecole-1']) and !empty($_POST['ecole-1']) and isset($_POST['ecole-2'])  and !empty($_POST['ecole-2']) and isset($_POST['ecole-3']) and !empty($_POST['ecole-3']) and
        isset($_POST['type_equipe']) and !empty($_POST['type_equipe'])
      ) { //Si il a bien tout rempli


        if (
          is_string($_POST['prenom-1']) and is_string($_POST['prenom-2']) and is_string($_POST['prenom-3']) and
          is_string($_POST['nom-1']) and is_string($_POST['nom-2']) and is_string($_POST['nom-3']) and
          is_string($_POST['email-1']) and is_string($_POST['email-2']) and is_string($_POST['email-3']) and
          is_string($_POST['tel-1']) and is_string($_POST['tel-2']) and is_string($_POST['tel-3']) and
          is_string($_POST['team-name']) and is_numeric($_POST['team-hub']) and
          is_string($_POST['ecole-1']) and is_string($_POST['ecole-2']) and is_string($_POST['ecole-3'])
          and  is_numeric($_POST['type_equipe'])
        ) { //il a bien envoyé des chaines de caractères


          if (
            strlen($_POST['email-1']) <= 255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-1']) and
            strlen($_POST['email-2']) <= 255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-2']) and
            strlen($_POST['email-3']) <= 255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-3'])
          ) { //Les emails sont valides

            if (isset($_POST['GCU'])) { //gcu validées

              if (
                strlen($_POST['nom-1']) <= 100 and strlen($_POST['nom-2']) <= 100 and strlen($_POST['nom-3']) <= 100 and
                strlen($_POST['prenom-1']) <= 100 and strlen($_POST['prenom-2']) <= 100 and strlen($_POST['prenom-3']) <= 100 and
                intval($_POST['team-hub']) < 4 and  intval($_POST['team-hub']) > 0 and strlen($_POST['team-name']) <= 180 and
                strlen($_POST['ecole-1']) <= 300 and strlen($_POST['ecole-2']) <= 300 and strlen($_POST['ecole-3']) <= 300 and
                intval($_POST['type_equipe']) >= 0 and intval($_POST['type_equipe']) < 4
              ) { //nom prenom pas trop grands, type equipe pas bon

                if (
                  strlen($_POST['tel-1']) <= 15 and preg_match("#^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$#", $_POST['tel-1']) and
                  strlen($_POST['tel-2']) <= 15 and preg_match("#^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$#", $_POST['tel-2']) and
                  strlen($_POST['tel-3']) <= 15 and preg_match("#^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$#", $_POST['tel-3'])
                ) { //Les tel sont valides

                  $email_1 = str_replace(array("\n", "\r", PHP_EOL), '', $_POST['email-1']); //faille CRLF
                  $email_2 = str_replace(array("\n", "\r", PHP_EOL), '', $_POST['email-2']); //faille CRLF
                  $email_3 = str_replace(array("\n", "\r", PHP_EOL), '', $_POST['email-3']); //faille CRLF
                  //securisation des entrées
                  $safe_email_1 = sanitize_string($email_1);
                  $safe_email_2 = sanitize_string($email_2);
                  $safe_email_3 = sanitize_string($email_3);

                  //on veut être sur qu'aucun compte n'existe déjà avec ce mail

                  if ($req4 = $conn->prepare("SELECT * FROM users WHERE (mail=? OR mail=? OR mail=?)")) { //Savoir si compte existe
                    $req4->bind_param("sss", $safe_email_1, $safe_email_2, $safe_email_3);
                    $req4->execute();
                    $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                    $req4->close();

                    if (empty($result4) and $safe_email_1 != $safe_email_2 and $safe_email_2 != $safe_email_3 and $safe_email_1 != $safe_email_3) { //il ne faut pas qu'un compte existe deja avec un des mails


                      $safe_nom_1 = ucfirst(sanitize_string($_POST['nom-1']));
                      $safe_nom_2 = ucfirst(sanitize_string($_POST['nom-2']));
                      $safe_nom_3 = ucfirst(sanitize_string($_POST['nom-3']));
                      $safe_prenom_1 = ucfirst(sanitize_string($_POST['prenom-1']));
                      $safe_prenom_2 = ucfirst(sanitize_string($_POST['prenom-2']));
                      $safe_prenom_3 = ucfirst(sanitize_string($_POST['prenom-3']));
                      $safe_tel_1 = sanitize_string($_POST['tel-1']);
                      $safe_tel_2 = sanitize_string($_POST['tel-2']);
                      $safe_tel_3 = sanitize_string($_POST['tel-3']);
                      $safe_ecole_1 = ucfirst(sanitize_string($_POST['ecole-1']));
                      $safe_ecole_2 = ucfirst(sanitize_string($_POST['ecole-2']));
                      $safe_ecole_3 = ucfirst(sanitize_string($_POST['ecole-3']));

                      $password1 = bin2hex(random_bytes(9)); //On génère les 3 mots de passes aléatoire
                      $password2 = bin2hex(random_bytes(9));
                      $password3 = bin2hex(random_bytes(9));

                      $ready_password_1 = password_hash($password1, PASSWORD_BCRYPT);
                      $ready_password_2 = password_hash($password2, PASSWORD_BCRYPT);
                      $ready_password_3 = password_hash($password3, PASSWORD_BCRYPT);

                      $team_name = sanitize_string($_POST['team-name']);
                      $team_hub = intval($_POST['team-hub']);

                      //On veut etre sur qu'aucune team existe avec ce nom
                      if ($req4 = $conn->prepare("SELECT * FROM teams WHERE nom=?")) {
                        $req4->bind_param("s", $team_name);
                        $req4->execute();
                        $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                        $req4->close();

                        if (empty($result4)) { //il ne faut pas qu'une team existe avec ce nom
                          if ($req44 = $conn->prepare("SELECT MAX(id) FROM teams")) { //Savoir emplacement
                            $req44->execute();
                            $result44 = $req44->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                            $req44->close();   //selectionne l'emplacement max pour savoir le nouvel emplacement
                            $id_team = intval($result44['MAX(id)']) + 1;

                            if ($req2 = $conn->prepare("INSERT INTO users (prenom, nom, password, mail, ecole, tel, mdp_a_changer,id_team) VALUES (?,?,?,?,?,?,1,?),(?,?,?,?,?,?,1,?),(?,?,?,?,?,?,1,?)")) { //Creation des 3 users
                              $req2->bind_param("ssssssissssssissssssi", $safe_prenom_1, $safe_nom_1, $ready_password_1, $safe_email_1, $safe_ecole_1, $safe_tel_1, $id_team, $safe_prenom_2, $safe_nom_2, $ready_password_2, $safe_email_2, $safe_ecole_2, $safe_tel_2, $id_team, $safe_prenom_3, $safe_nom_3, $ready_password_3, $safe_email_3, $safe_ecole_3, $safe_tel_3, $id_team);
                              $req2->execute();
                              $req2->close();

                              if ($req4 = $conn->prepare("SELECT MAX(numero_emplacement) FROM teams WHERE hub=?")) { //Savoir emplacement
                                $req4->bind_param("i", $team_hub);
                                $req4->execute();
                                $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                                $req4->close();   //selectionne l'emplacement max pour savoir le nouvel emplacement
                                $valeur_emplacement = intval($result4['MAX(numero_emplacement)']) + 1;
                                //Maintenant on créé la team
                                if ($req2 = $conn->prepare("INSERT INTO teams (nom, score, classement,valide,hub,numero_emplacement)
                                        VALUES (?,0,0,0,?,?)")) { //Creation de la team
                                  $req2->bind_param("sii", $team_name, $team_hub, $valeur_emplacement);
                                  $req2->execute();
                                  $req2->close();

                                  include("send_mail.php");


                                  #send_mail($safe_email_1, htmlspecialchars($team_name), $safe_prenom_1, $safe_prenom_2, $safe_prenom_3, $safe_nom_2, $safe_nom_3, $email_1, $password1);
                                  #send_mail($safe_email_2, htmlspecialchars($team_name), $safe_prenom_2, $safe_prenom_3, $safe_prenom_1, $safe_nom_3, $safe_nom_1, $email_2, $password2);
                                  #send_mail($safe_email_3, htmlspecialchars($team_name), $safe_prenom_3, $safe_prenom_1, $safe_prenom_2, $safe_nom_1, $safe_nom_2, $email_3, $password3);
                                  header('Location: /index.php');
                                  exit();


                                } else {
                                  $erreur = "Erreur lors de la création de la team.";
                                }
                              } else {
                                $erreur = "Erreur lors de la récupération de l'emplacement.";
                              }
                            } else {
                              $erreur = "Erreur lors de la création d'un utilisateur.";
                            }
                          } else {
                            $erreur = "Erreur lors de la récupération de l'id de la team.";
                          }
                        } else {
                          $erreur = "Une équipe porte dejà ce nom.";
                        }
                      } else {
                        $erreur = "Erreur lors de la vérification des équipes existantes.";
                      }
                    } else {
                      $erreur = "Un compte existe déjà avec l'une des adresses mails.";
                    }
                  } else {
                    $erreur = "Erreur lors de la création du compte.";
                  }
                } else {
                  $erreur = "Un des numéros de téléphone n'est pas dans le bon format.";
                }
              } else {
                $erreur = "Les noms et prénoms ne peuvent dépasser 100 caractères, le nom d'équipe ne peut dépasser 180 caractères, le nom de l'école ne peut dépasser 300 caractères.";
              }
            } else {
              $erreur = "Vous devez accepter le réglement du concours.";
            }
          } else {
            $erreur = "Un des emails n'est pas dans le bon format ou est trop long (255 caractères maximum).";
          }
        } else {
          $erreur = "Vous n'avez pas envoyé des chaînes de caractères.";
        }
      } else {
        $erreur = "Vous n'avez pas rempli tous les champs du formulaire.";
      }
    } else { //formulaire pas envoyé
      include("header.php");
      include("navbar.php");
?>
      <form action="" method="post" name="inscription">

        <div id="equipe" style="display: block; padding-top: 20vh" class="content">
          <div class="container containergrey">
            <ul class="steps2">
              <li class="is-active">Équipe</li>
              <li>Participant 1</li>
              <li>Participant 2</li>
              <li>Participant 3</li>
            </ul>

            <label for="team-name">Nom d'équipe :</label>
            <input type="text" id="team-name" name="team-name" maxlength="180" onkeydown="if (event.keyCode == 13)  document.getElementById('button-1').click()" required> <br />
            <label for="team-hub">Choix du lieu:</label>
            <select name="team-hub" id="team-hub">
              <option value="1">Hub de l'École des Ponts</option>
              <option value="2">Hub du plateau Saclay</option>
              <option value="3">Hub distanciel (Discord)</option>
            </select><br />
            <INPUT type= "radio" name="type_equipe" value="1"> Équipe 1A des ponts
            <INPUT type= "radio" name="type_equipe" value="2"> Équipe étudiante
            <INPUT type= "radio" name="type_equipe" value="3" checked> Autre
            <input type="button" id="button-1" value="Étape suivante" onclick="javascript:avance('equipe', 'participant-1');">
          </div>
        </div>

        <div id="participant-1" style="display: none; padding-top: 20vh" class="content">
          <div class="container containergrey">
            <ul class="steps2">
              <li>Équipe</li>
              <li class="is-active">Participant 1</li>
              <li>Participant 2</li>
              <li>Participant 3</li>
            </ul>

            <legend>
              <div class="number">1</div> Informations personnelles
            </legend>
            <label for="prenom-1">Prénom :</label>
            <input type="text" id="prenom-1" name="prenom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="100" required> <br />
            <label for="nom-1">Nom de famille:</label>
            <input type="text" id="nom-1" name="nom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="100" required> <br />
            <label for="ecole-1">École :</label>
            <input type="text" id="ecole-1" name="ecole-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="300" required> <br />
            <legend>
              <div class="number">2</div> Contact
            </legend>
            <label for="email-1">Email:</label>
            <input type="email" id="email-1" name="email-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="255" required> <br />
            <label for="tel-1">Numéro de téléphone:</label>
            <input type="tel" id="tel-1" name="tel-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />

            <div class="container2">
              <input type="button" value="Étape précédente" onclick="javascript:avance('participant-1', 'equipe');">
              <input type="button" id="button-2" value="Étape suivante" onclick="javascript:avance('participant-1', 'participant-2');">
            </div>
          </div>
        </div>


        <div id="participant-2" style="display: none;  padding-top: 20vh" class="content">
          <div class="container containergrey">
            <ul class="steps2">
              <li>Équipe</li>
              <li>Participant 1</li>
              <li class="is-active">Participant 2</li>
              <li>Participant 3</li>
            </ul>
            <legend>
              <div class="number">1</div> Informations personnelles
            </legend>
            <label for="prenom-2">Prénom :</label>
            <input type="text" id="prenom-2" name="prenom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="100" required> <br />
            <label for="nom-2">Nom de famille:</label>
            <input type="text" id="nom-2" name="nom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="100" required> <br />
            <label for="ecole-2">École :</label>
            <input type="text" id="ecole-2" name="ecole-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="300" required> <br />
            <legend>
              <div class="number">2</div> Contact
            </legend>
            <label for="email-2">Email:</label>
            <input type="email" id="email-2" name="email-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="255" required> <br />
            <label for="tel-2">Numéro de téléphone:</label>
            <input type="tel" id="tel-2" name="tel-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />

            <div class="container2">
              <input type="button" value="Étape précédente" onclick="javascript:avance('participant-2', 'participant-1');">
              <input type="button" id="button-3" value="Étape suivante" onclick="javascript:avance('participant-2', 'participant-3');">
            </div>
          </div>
        </div>


        <div id="participant-3" style="display: none;  padding-top: 20vh" class="content">
          <div class="container containergrey">
            <ul class="steps2">
              <li>Équipe</li>
              <li>Participant 1</li>
              <li>Participant 2</li>
              <li class="is-active">Participant 3</li>
            </ul>
            <legend>
              <div class="number">1</div> Informations personnelles
            </legend>
            <label for="prenom-3">Prénom :</label>
            <input type="text" name="prenom-3" maxlength="100" required><br />
            <label for="nom-2">Nom de famille:</label>
            <input type="text" name="nom-3" maxlength="100" required><br />
            <label for="ecole-3">École :</label>
            <input type="text" id="ecole-3" name="ecole-3" maxlength="300" required> <br />
            <legend>
              <div class="number">2</div> Contact
            </legend>
            <label for="email-2">Email:</label>
            <input type="email" name="email-3" maxlength="255" required><br />
            <label for="tel-3">Numéro de téléphone:</label>
            <input type="tel" name="tel-3" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required><br />
            <label for="GCU">Nous acceptons le <a target="_blank" href="/#reglement">réglement du concours</a></label>
            <input type="checkbox" id="GCU" name="GCU" required><br />
            <div class="container2">
              <input type="button" value="Étape précédente" onclick="javascript:avance('participant-3', 'participant-2');">
              <input type="submit" name="submit" value="S'inscrire">
            </div>
          </div>
        </div>
      </form>
    <?php
    }
  } else //Si date d'inscription dépassée
  {
    include("header.php");
 include("navbar.php");

 popup("Les inscriptions sont closes.", 6000, "error");
    ?>
  <?php
  }
} else { //si l'utilisateur est déjà connecté
  include("header.php");
include("navbar.php");

popup("Vous êtes déjà connecté.", 6000, "error")
  ?>

<?php
}

if (isset($erreur)) {
  include("header.php");
  include("navbar.php");
  //si on doit afficher le formulaire avec un message d'erreur
?>

 <?php popup($erreur, 6000, "error");?>
  <form action="" method="post" name="inscription">
    <div id="equipe" style="display: block;  padding-top: 20vh" class="content">
      <div class="container containergrey">
        <ul class="steps2">
          <li class="is-active">Équipe</li>
          <li>Participant 1</li>
          <li>Participant 2</li>
          <li>Participant 3</li>
        </ul>

        <label for="team-name">Nom d'équipe :</label>
        <input type="text" id="team-name" name="team-name" onkeydown="if (event.keyCode == 13)  document.getElementById('button-1').click()" maxlength="180" value="<?php if (isset($_POST['team-name'])) {
          echo htmlspecialchars($_POST['team-name']);
          } ?>" required> <br />

        <label for="team-hub">Choix du lieu:</label>
        <select name="team-hub" id="team-hub">
          <option value="1" <?php if (isset($_POST['team-hub'])) {
                              if ($_POST['team-hub'] == 1) {
                                echo 'selected';
                              }
                            } ?>>Hub de l'École des Ponts</option>
          <option value="2" <?php if (isset($_POST['team-hub'])) {
                              if ($_POST['team-hub'] == 2) {
                                echo 'selected';
                              }
                            } ?>>Hub du plateau Saclay</option>
        <option value="3" <?php if (isset($_POST['team-hub'])) {
                                                if ($_POST['team-hub'] == 3) {
                                                  echo 'selected';
                                                }
                                              } ?>>Hub distanciel (Discord)</option>

                                              <INPUT type= "radio" name="type_equipe" value="1"> Équipe 1A des ponts
                                              <INPUT type= "radio" name="type_equipe" value="2"> Équipe étudiante
                                              <INPUT type= "radio" name="type_equipe" value="3" checked> Autre

        <input type="button" id="button-1" value="Étape suivante" onclick="javascript:avance('equipe', 'participant-1');">
      </div>
    </div>

    <div id="participant-1" style="display: none; padding-top: 20vh" class="content">
      <div class="container containergrey">
        <ul class="steps2">
          <li>Équipe</li>
          <li class="is-active">Participant 1</li>
          <li>Participant 2</li>
          <li>Participant 3</li>
        </ul>

        <legend>
          <div class="number">1</div> Informations personnelles
        </legend>
        <label for="prenom-1">Prénom :</label>
        <input type="text" id="prenom-1" name="prenom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" value="<?php if (isset($_POST['prenom-1'])) {
                                                                                                                                                    echo htmlspecialchars($_POST['prenom-1']);
                                                                                                                                                  } ?>" maxlength="100" required> <br />
        <label for="nom-1">Nom de famille:</label>
        <input type="text" id="nom-1" name="nom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" value="<?php if (isset($_POST['nom-1'])) {
                                                                                                                                              echo htmlspecialchars($_POST['nom-1']);
                                                                                                                                            } ?>" maxlength="100" required> <br />
        <label for="ecole-1">École :</label>
        <input type="text" id="ecole-1" name="ecole-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" value="<?php if (isset($_POST['ecole-1'])) {
                                                                                                                                                  echo htmlspecialchars($_POST['ecole-1']);
                                                                                                                                                } ?>" maxlength="300" required> <br />
        <legend>
          <div class="number">2</div> Contact
        </legend>
        <label for="email-1">Email:</label>
        <input type="email" id="email-1" name="email-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" value="<?php if (isset($_POST['email-1'])) {
                                                                                                                                                    echo htmlspecialchars($_POST['email-1']);
                                                                                                                                                  } ?>" maxlength="255" required> <br />
        <label for="tel-1">Numéro de téléphone:</label>
        <input type="tel" id="tel-1" name="tel-1" maxlength="15" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" value="<?php if (isset($_POST['tel-1'])) {
                                                                                                                                                            echo htmlspecialchars($_POST['tel-1']);
                                                                                                                                                          } ?>" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />

        <div class="container2">
          <input type="button" value="Étape précédente" onclick="javascript:avance('participant-1', 'equipe');">
          <input type="button" id="button-2" value="Étape suivante" onclick="javascript:avance('participant-1', 'participant-2');">
        </div>
      </div>
    </div>


    <div id="participant-2" style="display: none; padding-top: 20vh" class="content">
      <div class="container containergrey">
        <ul class="steps2">
          <li>Équipe</li>
          <li>Participant 1</li>
          <li class="is-active">Participant 2</li>
          <li>Participant 3</li>
        </ul>
        <legend>
          <div class="number">1</div> Informations personnelles
        </legend>
        <label for="prenom-2">Prénom :</label>
        <input type="text" id="prenom-2" name="prenom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" value="<?php if (isset($_POST['prenom-2'])) {
                                                                                                                                                    echo htmlspecialchars($_POST['prenom-2']);
                                                                                                                                                  } ?>" maxlength="100" required> <br />
        <label for="nom-2">Nom de famille:</label>
        <input type="text" id="nom-2" name="nom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" value="<?php if (isset($_POST['nom-2'])) {
                                                                                                                                              echo htmlspecialchars($_POST['nom-2']);
                                                                                                                                            } ?>" maxlength="100" required> <br />
        <label for="ecole-2">École :</label>
        <input type="text" id="ecole-2" name="ecole-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" value="<?php if (isset($_POST['ecole-2'])) {
                                                                                                                                                  echo htmlspecialchars($_POST['ecole-2']);
                                                                                                                                                } ?>" maxlength="300" required> <br />
        <legend>
          <div class="number">2</div> Contact
        </legend>
        <label for="email-2">Email:</label>
        <input type="email" id="email-2" name="email-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" value="<?php if (isset($_POST['email-2'])) {
                                                                                                                                                    echo htmlspecialchars($_POST['email-2']);
                                                                                                                                                  } ?>" maxlength="255" required> <br />
        <label for="tel-2">Numéro de téléphone:</label>
        <input type="tel" id="tel-2" name="tel-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="15" value="<?php if (isset($_POST['tel-2'])) {
                                                                                                                                                            echo htmlspecialchars($_POST['tel-2']);
                                                                                                                                                          } ?>" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />

        <div class="container2">
          <input type="button" value="Étape précédente" onclick="javascript:avance('participant-2', 'participant-1');">
          <input type="button" id="button-3" value="Étape suivante" onclick="javascript:avance('participant-2', 'participant-3');">
        </div>
      </div>
    </div>


    <div id="participant-3" style="display: none; padding-top: 20vh" class="content">
      <div class="container containergrey">
        <ul class="steps2">
          <li>Équipe</li>
          <li>Participant 1</li>
          <li>Participant 2</li>
          <li class="is-active">Participant 3</li>
        </ul>
        <legend>
          <div class="number">1</div> Informations personnelles
        </legend>
        <label for="prenom-3">Prénom :</label>
        <input type="text" name="prenom-3" maxlength="100" value="<?php if (isset($_POST['prenom-3'])) {
                                                                    echo htmlspecialchars($_POST['prenom-3']);
                                                                  } ?>" required><br />
        <label for="nom-2">Nom de famille:</label>
        <input type="text" name="nom-3" maxlength="100" value="<?php if (isset($_POST['nom-3'])) {
                                                                  echo htmlspecialchars($_POST['nom-3']);
                                                                } ?>" required><br />
        <label for="ecole-3">École :</label>
        <input type="text" id="ecole-3" name="ecole-3" value="<?php if (isset($_POST['ecole-3'])) {
                                                                echo htmlspecialchars($_POST['ecole-3']);
                                                              } ?>" maxlength="300" required> <br />
        <legend>
          <div class="number">2</div> Contact
        </legend>
        <label for="email-2">Email:</label>
        <input type="email" name="email-3" maxlength="255" value="<?php if (isset($_POST['email-3'])) {
                                                                    echo htmlspecialchars($_POST['email-3']);
                                                                  } ?>" required><br />
        <label for="tel-3">Numéro de téléphone:</label>
        <input type="tel" name="tel-3" value="<?php if (isset($_POST['tel-3'])) {
                                                echo htmlspecialchars($_POST['tel-3']);
                                              } ?>" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required><br />
        <label for="GCU">Nous acceptons le <a target="_blank" href="/#reglement">réglement du concours</a></label>
        <input type="checkbox" id="GCU" name="GCU" required><br /> <br />
        <div class="container2">
          <input type="button" value="Étape précédente" onclick="javascript:avance('participant-3', 'participant-2');">
          <input type="submit" name="submit" value="S'inscrire">
        </div>
      </div>
    </div>
  </form>

<?php
}

include("footer.php");
?>
