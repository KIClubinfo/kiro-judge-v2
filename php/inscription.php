<?php

include("config.php");

if (!(isset($_SESSION['user']))){ //vérifie que l'utilisateur n'est pas connecté
  if (isset($_POST['submit'])){ //Il a envoyé le formulaire
    if (isset($_POST['mail']) and !empty($_POST['mail']) and isset($_POST['password-bde']) and !empty($_POST['password-bde']) and isset($_POST['bde'])
    and !empty($_POST['bde']) and isset($_POST['nom']) and !empty($_POST['nom']) and isset($_POST['prenom']) and !empty($_POST['prenom']) and isset($_POST['password']) and !empty($_POST['password'])){ //Si il a bien tout rempli
      if (is_string($_POST['mail']) and is_string($_POST['password']) and is_string($_POST['prenom']) and is_string($_POST['password-bde']) and is_numeric($_POST['bde']) and is_string($_POST['nom']) ){ //il a bien envoyé des chaines de caractères
        if (strlen($_POST['email'])<=255 and preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email'])){ //Son email est valide
          if (isset($_POST['GCU'])){ //gcu
              if (strlen($_POST['nom'])<=100 and strlen($_POST['prenom'])<=100){ //nom prenom pas trop grands
                if (strlen($_POST['password'])>=6){ //password assez long
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
      <label for="prenom">Prénom:</label>
      <input type="prenom" name="prenom" maxlength="100" required>
      <label for="nom">Nom de famille:</label>
      <input type="nom" name="nom" maxlength="100" required>
      <label for="email">Email:</label>
      <input type="email" name="email" maxlength="255" required>
      <label for="password">Mot de passe (6 caractères minimum):</label>
      <input minlength="6" type="password" name="password" onchange='validatePassword();' required>
      <label for="password-verif">Mot de passe (vérification):</label>
      <input minlength="6" type="password" name="password-verif" onchange='validatePassword();' required>
      <label for="tel">Numéro de téléhpone:</label>
      <input type="tel" name="tel" maxlength="15" required>
      <label for="GCU">J'accepte les <a href="gcu.php">conditions générales d'utilisation</a></label>
      <input type="checkbox" id="GCU" name="GCU" required>
      <input type="submit" name="submit" value="Se connecter">
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
  if ($req = $conn->prepare("SELECT id, nom_ecole FROM bde")) { //requete préparée
      $req->execute();
      $result = $req->get_result()->fetch_all(MYSQLI_ASSOC); //resulats de la requête
      $req->close();
  }
  ?>
  <span class="erreur"><?php echo $erreur; ?> </span>
  <form action ="" method="post" name="inscription">
    <label for="email" >Email:</label>
    <input type="email" name="email" value="<?php if (isset($_POST['email'])){ echo htmlspecialchars($_POST['email']);}?>" required>
    <label for="password">Mot de passe (8 caractères minimum):</label>
    <input minlength="8" type="password" name="password" required>
    <label for="prenom">Prénom:</label>
    <input type="prenom" name="prenom" value="<?php if (isset($_POST['prenom'])){ echo htmlspecialchars($_POST['prenom']);}?>" required>
    <label for="nom">Nom de famille:</label>
    <input type="nom" name="nom" value="<?php if (isset($_POST['nom'])){ echo htmlspecialchars($_POST['nom']);}?>" required>

    <label for="password-bde">Mot de passe du BDE:</label>
    <input type="password" name="password-bde" required>
    <label for="GCU">J'accepte les <a href="gcu.php">conditions générales d'utilisation</a></label>
    <input type="checkbox" id="GCU" name="GCU" required>
    <input type="submit" name="submit" value="Se connecter">
  <?php
}
include("footer.php")
?>
