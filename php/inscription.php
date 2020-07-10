<?php

include("config.php");



if (!(isset($_SESSION['user']))){ //vérifie que l'utilisateur n'est pas connecté
  //Date inscription
  if (isset($_POST['submit'])){ //Il a envoyé le formulaire

    if (isset($_POST['prenom-1']) and !empty($_POST['prenom-1']) and isset($_POST['prenom-2']) and !empty($_POST['prenom-2']) and isset($_POST['prenom-3'])
    and !empty($_POST['prenom-3']) and
    isset($_POST['nom-1']) and !empty($_POST['nom-1']) and isset($_POST['nom-2']) and !empty($_POST['nom-2']) and isset($_POST['nom-3'])
    and !empty($_POST['nom-3']) and
    isset($_POST['email-1']) and !empty($_POST['email-1']) and isset($_POST['email-2']) and !empty($_POST['email-2']) and isset($_POST['email-3'])
    and !empty($_POST['email-3']) and
    isset($_POST['tel-1']) and !empty($_POST['tel-1']) and isset($_POST['tel-2']) and !empty($_POST['tel-2']) and isset($_POST['tel-3'])
    and !empty($_POST['tel-3']) and
    isset($_POST['team-name']) and !empty($_POST['team-name']) and isset($_POST['team-hub']) and !empty($_POST['team-hub'] and
    isset($_POST['ecole-1']) and !empty($_POST['ecole-1']) and isset($_POST['ecole-2']) and !empty($_POST['ecole-2']) and isset($_POST['ecole-3']) and !empty($_POST['ecole-3'])){ //Si il a bien tout rempli

      if (is_string($_POST['prenom-1']) and is_string($_POST['prenom-2']) and is_string($_POST['prenom-3']) and
      is_string($_POST['nom-1']) and is_string($_POST['nom-2']) and is_string($_POST['nom-3']) and
      is_string($_POST['email-1']) and is_string($_POST['email-2']) and is_string($_POST['email-3']) and
      is_string($_POST['tel-1']) and is_string($_POST['tel-2']) and is_string($_POST['tel-3']) and
      is_string($_POST['team-name']) and is_numeric($_POST['team-hub'] and
      is_string($_POST['ecole-1']) and is_string($_POST['ecole-2']) and is_string($_POST['ecole-3']))){ //il a bien envoyé des chaines de caractères

        if (strlen($_POST['email-1'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-1']) and
            strlen($_POST['email-2'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-2']) and
            strlen($_POST['email-3'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-3'])){ //Les emails sont valides

          if (isset($_POST['GCU'])){ //gcu validées

              if (strlen($_POST['nom-1'])<=100 and strlen($_POST['nom-2'])<=100 and strlen($_POST['nom-3'])<=100 and
                  strlen($_POST['prenom-1'])<=100 and strlen($_POST['prenom-2'])<=100 and strlen($_POST['prenom-3']) <= 100 and
                  intval($_POST['team-hub']) <3 and  intval($_POST['team-hub']) > 1 and strlen($_POST['team-name']) <= 180 and
                  strlen($_POST['ecole-1'])<=300 and strlen($_POST['ecole-2'])<=300 and strlen($_POST['ecole-3']) <= 300){ //nom prenom pas trop grands

                if (strlen($_POST['tel-1'])<=15 and preg_match("^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$", $_POST['tel-1']) and
                    strlen($_POST['tel-2'])<=15 and preg_match("^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$", $_POST['tel-2']) and
                    strlen($_POST['tel-3'])<=15 and preg_match("^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$", $_POST['tel-3'])){ //Les tel sont valides

                      $email_1 = str_replace(array("\n","\r",PHP_EOL),'',$_POST['email-1']); //faille CRLF
                      $email_2 = str_replace(array("\n","\r",PHP_EOL),'',$_POST['email-2']); //faille CRLF
                      $email_3 = str_replace(array("\n","\r",PHP_EOL),'',$_POST['email-3']); //faille CRLF
                      //securisation des entrées
                      $safe_email_1 = sanitize_string($email_1);
                      $safe_email_2 = sanitize_string($email_2);
                      $safe_email_3 = sanitize_string($email_3);

                    //on veut être sur qu'aucun compte n'existe déjà avec ce mail
                    if ($req4 = $conn->prepare("SELECT * FROM users WHERE (email=? OR email=? OR email=?)")) { //Savoir si compte existe
                        $req4->bind_param("sss", $safe_email_1,$safe_email_2,$safe_email_3);
                        $req4->execute();
                        $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                        $req4->close();
                        if (empty($result4)){ //il ne faut pas qu'un compte existe deja avec un des mails


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

                          if ($req2 = $conn->prepare("INSERT INTO users (prenom, nom, password, mail, ecole, tel, mdp_a_changer)
                          VALUES (?,?,?,?,?,?,1),(?,?,?,?,?,?,1),(?,?,?,?,?,?,1)")) { //Creation des 3 users
                            $req2->bind_param("ssssssssssssssssss", $safe_prenom_1,$safe_nom_1,$ready_password_1,$safe_email_1,$safe_ecole_1,$safe_tel_1,$safe_prenom_2,$safe_nom_2,$ready_password_2,$safe_email_2,$safe_ecole_2,$safe_tel_2,$safe_prenom_3,$safe_nom_3,$ready_password_3,$safe_email_3,$safe_ecole_3,$safe_tel_3);
                            $req2->execute();
                            $req2->close();
                            //Maintenant on créé la team


                          }
                          else{
                                  $erreur = "Erreur lors de la création d'un utilisateur.";
                          }
                      }
                      else{
                        $erreur = "Un compte existe déjà avec l'une des adresses mails.";
                      }
                 }
                 else{
                   $erreur = "Erreur lors de la création du compte.";
                 }
                }
                else{
                  $erreur = "Un des numéros de téléphone n'est pas dans le bon format.";
                }
              }
              else{
                $erreur = "Les noms et prénoms ne peuvent dépasser 100 caractères, le nom d'équipe ne peut dépasser 180 caractères, le nom de l'école ne peut dépasser 300 caractères.";
              }
          }
          else{
            $erreur = "Vous devez accepter le réglement du concours.";
          }
        }
        else{
          $erreur = "Un des emails n'est pas dans le bon format ou est trop long (255 caractères maximum).";
        }
      }
      else{
        $erreur = "Vous n'avez pas envoyé des chaînes de caractères.";
      }
    }
    else{
      $erreur = "Vous n'avez pas rempli tous les champs du formulaire.";
    }

  }
  else{ //formulaire pas envoyé

    ?>
    <form action ="" method="post" name="inscription">
      <div id="equipe" style="display: block;">
        <ul class="steps">
          <li class="is-active">Équipe</li>
          <li>Participant 1</li>
          <li>Participant 2</li>
          <li>Participant 3</li>
        </ul>
        <label for="team-name">Nom d'équipe :</label>
        <input type="text" id="team-name" name="team-name" maxlength="180" required> <br />
        <label for="team-hub">Choix du lieu:</label>
        <select name="team-hub" id="team-hub">
            <option value="1">Hub de l'École des Ponts</option>
            <option value="2">Hub du plateau Saclay</option>
        </select><br />

        <button onclick="javascript:avance('equipe', 'participant-1');">Étape suivante </button>
      </div>
      </div>
      <div id="participant-1" style="display: none;">
        <ul class="steps">
          <li>Équipe</li>
          <li class="is-active">Participant 1</li>
          <li>Participant 2</li>
          <li>Participant 3</li>
        </ul>
        <label for="prenom-1">Prénom :</label>
        <input type="text" id="prenom-1" name="prenom-1" maxlength="100" required> <br />
        <label for="nom-1">Nom de famille:</label>
        <input type="text" id="nom-1" name="nom-1" maxlength="100" required> <br />
        <label for="email-1">Email:</label>
        <input type="email" id="email-1" name="email-1" maxlength="255" required> <br />
        <label for="tel-1">Numéro de téléhpone:</label>
        <input type="tel" id="tel-1" name="tel-1" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />
        <label for="ecole-1">École :</label>
        <input type="text" id="ecole-1" name="ecole-1" maxlength="300" required> <br />
        <input type="button" value="Étape précédente" onclick="javascript:avance('participant-1', 'equipe');"><br />
        <button onclick="javascript:avance('participant-1', 'participant-2');">Étape suivante </button>
      </div>
      <div id="participant-2" style="display: none;">
        <ul class="steps">
          <li>Équipe</li>
          <li>Participant 1</li>
          <li class="is-active">Participant 2</li>
          <li>Participant 3</li>
        </ul>
        <label for="prenom-2">Prénom :</label>
        <input type="text" id="prenom-2" name="prenom-2" maxlength="100" required> <br />
        <label for="nom-2">Nom de famille:</label>
        <input type="text" id="nom-2" name="nom-2" maxlength="100" required> <br />
        <label for="email-2">Email:</label>
        <input type="email" id="email-2" name="email-2" maxlength="255" required> <br />
        <label for="tel-2">Numéro de téléhpone:</label>
        <input type="tel" id="tel-2" name="tel-2" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />
        <input type="button" value="Étape précédente" onclick="javascript:avance('participant-2', 'participant-1');"><br />
        <label for="ecole-2">École :</label>
        <input type="text" id="ecole-2" name="ecole-2" maxlength="300" required> <br />
        <button onclick="javascript:avance('participant-2', 'participant-3');">Étape suivante </button>
      </div>
      <div id="participant-3" style="display: none;">
        <ul class="steps">
          <li>Équipe</li>
          <li>Participant 1</li>
          <li>Participant 2</li>
          <li class="is-active">Participant 3</li>
        </ul>
        <label for="prenom-3">Prénom :</label>
        <input type="text" name="prenom-3" maxlength="100" required><br />
        <label for="nom-2">Nom de famille:</label>
        <input type="text" name="nom-3" maxlength="100" required><br />
        <label for="email-2">Email:</label>
        <input type="email" name="email-3" maxlength="255" required><br />
        <label for="tel-3">Numéro de téléhpone:</label>
        <input type="tel" name="tel-3" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required><br />
        <label for="ecole-3">École :</label>
        <input type="text" id="ecole-3" name="ecole-3" maxlength="300" required> <br />
        <label for="GCU">Nous acceptons le <a target="_blank" href="https://hackathon.enpc.org/#reglement">réglement du concours</a></label>
        <input type="checkbox" id="GCU" name="GCU" required><br />
        <input type="button" value="Étape précédente" onclick="javascript:avance('participant-3', 'participant-2');"><br />
        <input type="submit" name="submit" value="S'inscrire">
      </div>
    </form>
    <?php
  }
}


else{ //si l'utilisateur est déjà connecté
  ?>
  Vous êtes déjà connecté.
  <?php
}
if (isset($erreur)){
  //si on doit afficher le formulaire avec un message d'erreur

  ?>
  <span class="erreur"><?php echo $erreur; ?> </span>
  <form action ="" method="post" name="inscription">
    <div id="equipe" style="display: block;">
      <ul class="steps">
        <li class="is-active">Équipe</li>
        <li>Participant 1</li>
        <li>Participant 2</li>
        <li>Participant 3</li>
      </ul>
      <label for="team-name">Nom d'équipe :</label>
      <input type="text" id="team-name" name="team-name" maxlength="180" required> <br />
      <label for="team-hub">Choix du lieu:</label>
      <select name="team-hub" id="team-hub">
          <option value="1">Hub de l'École des Ponts</option>
          <option value="2">Hub du plateau Saclay</option>
      </select><br />

      <button onclick="javascript:avance('equipe', 'participant-1');">Étape suivante </button>
    </div>
    </div>
    <div id="participant-1" style="display: none;">
      <ul class="steps">
        <li>Équipe</li>
        <li class="is-active">Participant 1</li>
        <li>Participant 2</li>
        <li>Participant 3</li>
      </ul>
      <label for="prenom-1">Prénom :</label>
      <input type="text" id="prenom-1" name="prenom-1" maxlength="100" required> <br />
      <label for="nom-1">Nom de famille:</label>
      <input type="text" id="nom-1" name="nom-1" maxlength="100" required> <br />
      <label for="email-1">Email:</label>
      <input type="email" id="email-1" name="email-1" maxlength="255" required> <br />
      <label for="tel-1">Numéro de téléhpone:</label>
      <input type="tel" id="tel-1" name="tel-1" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />
      <label for="ecole-1">École :</label>
      <input type="text" id="ecole-1" name="ecole-1" maxlength="300" required> <br />
      <input type="button" value="Étape précédente" onclick="javascript:avance('participant-1', 'equipe');"><br />
      <button onclick="javascript:avance('participant-1', 'participant-2');">Étape suivante </button>
    </div>
    <div id="participant-2" style="display: none;">
      <ul class="steps">
        <li>Équipe</li>
        <li>Participant 1</li>
        <li class="is-active">Participant 2</li>
        <li>Participant 3</li>
      </ul>
      <label for="prenom-2">Prénom :</label>
      <input type="text" id="prenom-2" name="prenom-2" maxlength="100" required> <br />
      <label for="nom-2">Nom de famille:</label>
      <input type="text" id="nom-2" name="nom-2" maxlength="100" required> <br />
      <label for="email-2">Email:</label>
      <input type="email" id="email-2" name="email-2" maxlength="255" required> <br />
      <label for="tel-2">Numéro de téléhpone:</label>
      <input type="tel" id="tel-2" name="tel-2" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required> <br />
      <input type="button" value="Étape précédente" onclick="javascript:avance('participant-2', 'participant-1');"><br />
      <label for="ecole-2">École :</label>
      <input type="text" id="ecole-2" name="ecole-2" maxlength="300" required> <br />
      <button onclick="javascript:avance('participant-2', 'participant-3');">Étape suivante </button>
    </div>
    <div id="participant-3" style="display: none;">
      <ul class="steps">
        <li>Équipe</li>
        <li>Participant 1</li>
        <li>Participant 2</li>
        <li class="is-active">Participant 3</li>
      </ul>
      <label for="prenom-3">Prénom :</label>
      <input type="text" name="prenom-3" maxlength="100" required><br />
      <label for="nom-2">Nom de famille:</label>
      <input type="text" name="nom-3" maxlength="100" required><br />
      <label for="email-2">Email:</label>
      <input type="email" name="email-3" maxlength="255" required><br />
      <label for="tel-3">Numéro de téléhpone:</label>
      <input type="tel" name="tel-3" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required><br />
      <label for="ecole-3">École :</label>
      <input type="text" id="ecole-3" name="ecole-3" maxlength="300" required> <br />
      <label for="GCU">Nous acceptons le <a target="_blank" href="https://hackathon.enpc.org/#reglement">réglement du concours</a></label>
      <input type="checkbox" id="GCU" name="GCU" required><br />
      <input type="button" value="Étape précédente" onclick="javascript:avance('participant-3', 'participant-2');"><br />
      <input type="submit" name="submit" value="S'inscrire">
    </div>
  </form>
  <?php
}
include("footer.php");
?>
