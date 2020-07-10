<?php

include("config.php");

if (!(isset($_SESSION['user']))){ //vérifie que l'utilisateur n'est pas connecté
  if (isset($_POST['submit'])){ //Il a envoyé le formulaire

    if (isset($_POST['prenom-1']) and !empty($_POST['prenom-1']) and isset($_POST['prenom-2']) and !empty($_POST['prenom-2']) and isset($_POST['prenom-3'])
    and !empty($_POST['prenom-3']) and
    isset($_POST['nom-1']) and !empty($_POST['nom-1']) and isset($_POST['nom-2']) and !empty($_POST['nom-2']) and isset($_POST['nom-3'])
    and !empty($_POST['nom-3']) and
    isset($_POST['email-1']) and !empty($_POST['email-1']) and isset($_POST['email-2']) and !empty($_POST['email-2']) and isset($_POST['email-3'])
    and !empty($_POST['email-3']) and
    isset($_POST['tel-1']) and !empty($_POST['tel-1']) and isset($_POST['tel-2']) and !empty($_POST['tel-2']) and isset($_POST['tel-3'])
    and !empty($_POST['tel-3'])){ //Si il a bien tout rempli

      if (is_string($_POST['prenom-1']) and is_string($_POST['prenom-2']) and is_string($_POST['prenom-3']) and
      is_string($_POST['nom-1']) and is_string($_POST['nom-2']) and is_string($_POST['nom-3']) and
      is_string($_POST['email-1']) and is_string($_POST['email-2']) and is_string($_POST['email-3']) and
      is_string($_POST['tel-1']) and is_string($_POST['tel-2']) and is_string($_POST['tel-3'])){ //il a bien envoyé des chaines de caractères

        if (strlen($_POST['email-1'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-1']) and
            strlen($_POST['email-2'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-2']) and
            strlen($_POST['email-3'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email-3'])){ //Les emails sont valides

          if (isset($_POST['GCU'])){ //gcu validées

              if (strlen($_POST['nom-1'])<=100 and strlen($_POST['nom-2'])<=100 and strlen($_POST['nom-3'])<=100 and
                  strlen($_POST['prenom-1'])<=100 and strlen($_POST['prenom-2'])<=100 and strlen($_POST['prenom-3']) <= 100){ //nom prenom pas trop grands

                if (strlen($_POST['tel-1'])<=15 and preg_match("^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$", $_POST['tel-1']) and
                    strlen($_POST['tel-2'])<=15 and preg_match("^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$", $_POST['tel-2']) and
                    strlen($_POST['tel-3'])<=15 and preg_match("^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$", $_POST['tel-3'])){ //Les tel sont valides

                  $email = str_replace(array("\n","\r",PHP_EOL),'',$_POST['email']); //faille CRLF
                  //securisation des entrées
                  $safe_email = sanitize_string($email);
                  $id_bde_safe = intval($_POST['bde']);
                  //on veut être sur qu'aucun compte n'existe déjà avec ce mail
                  if ($req4 = $conn->prepare("SELECT * FROM users WHERE email=?")) { //requete préparée vers le bde
                      $req4->bind_param("s", $safe_email);
                      $req4->execute();
                      $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                      $req4->close();
                      if (empty($result4)){ //il ne faut pas qu'un compte existe deja avec ce mail
                        if ($req1 = $conn->prepare("SELECT * FROM bde WHERE id=?")) { //requete préparée vers le bde
                            $req1->bind_param("i", $id_bde_safe);
                            $req1->execute();
                            $result1 = $req1->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                            $req1->close();
                            if (!empty($result1)){ //si l'id du bde existe

                              $ready_password_bde = sanitize_string($_POST['password-bde']);

                              if (password_verify($ready_password_bde, $result1['password'])){ //tout est bon on peut l'enregistrer

                                $safe_password = sanitize_string($_POST['password']);
                                $ready_password = password_hash($safe_password, PASSWORD_BCRYPT);

                                $safe_nom = ucfirst(sanitize_string($_POST['nom']));
                                $safe_prenom = ucfirst(sanitize_string($_POST['prenom']));
                                $code_active = bin2hex(random_bytes(15)); //code pour activer le compte
                                $jeton_long =  bin2hex(random_bytes(32));
                                if ($req2 = $conn->prepare("INSERT INTO users (prenom, nom, id_bde, password, admin, valide, email, code_validation, token_plus_longtemps) VALUES (?,?,?,?,0,0,?,?,?)")) { //requete préparée vers le bde
                                    $req2->bind_param("ssissss", $safe_prenom,$safe_nom,$id_bde_safe,$ready_password,$safe_email,$code_active,$jeton_long);
                                    $req2->execute();
                                    $req2->close();
                                    if ($req3 = $conn->prepare("UPDATE bde SET nombre_membres =? WHERE id=?")) { //requete préparée vers le bde
                                        $tmp = $result1['nombre_membres']+1;
                                        $req3->bind_param("ii", $tmp,$id_bde_safe);
                                        $req3->execute();
                                        $req3->close();
                                        //tout est bon il ne reste plus qu'à le rediriger vers l'index, avec un message pour qu'il valide son compte
                                        //envoyer mail de validation email

                                        header('Location: index.php?inscr');
                                        exit();
                                    }
                                    else{
                                      $erreur = "Erreur lors de la mise à jour du BDE.";
                                    }
                                }
                                else{
                                  $erreur = "Erreur lors de la création de l'utilisateur.";
                                }
                              }
                              else{
                                $erreur = "Le mot de passe entré pour ce BDE n'est pas correct.";
                              }
                            }
                            else{
                              $erreur = "Le bde que vous avez choisi n'éxiste pas.";
                            }
                        }
                      }
                      else{
                        $erreur = "Un compte existe déjà avec cette adresse mail.";
                      }
                 }
                 else{
                   $erreur = "Erreur lors de la création du compte.";
                 }
                }
                else{
                  $erreur = "Le mot de passe doit faire au moins 6 caractères.";
                }
              }
              else{
                $erreur = "Votre nom et votre prénom ne peut exceder 100 caractères.";
              }
          }
          else{
            $erreur = "Vous devez acceptez les conditions générales d'utilisation.";
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
      $erreur = "Vous n'avez pas rempli tous les champs du formulaire.";
    }

  }
  else{ //formulaire pas envoyé

    ?>
    <form action ="" method="post" name="inscription">
      <div id="participant-1" style="display: block;">
        <ul class="steps">
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
        <button onclick="javascript:avance('participant-1', 'participant-2');">Étape suivante </button>
      </div>
      <div id="participant-2" style="display: none;">
        <ul class="steps">
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
        <button onclick="javascript:avance('participant-2', 'participant-3');">Étape suivante </button>
      </div>
      <div id="participant-3" style="display: none;">
        <ul class="steps">
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
    <div id="participant-1" style="display: block;">
      <ul class="steps">
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
      <button onclick="javascript:avance('participant-1', 'participant-2');">Étape suivante </button>
    </div>
    <div id="participant-2" style="display: none;">
      <ul class="steps">
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
      <button onclick="javascript:avance('participant-2', 'participant-3');">Étape suivante </button>
    </div>
    <div id="participant-3" style="display: none;">
      <ul class="steps">
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
