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
                and !empty($_POST['tel-3']) and
                isset($_POST['team-name']) and !empty($_POST['team-name']) and isset($_POST['team-hub']) and !empty($_POST['team-hub']) and
                isset($_POST['ecole-1']) and !empty($_POST['ecole-1']) and isset($_POST['ecole-2']) and !empty($_POST['ecole-2']) and isset($_POST['ecole-3']) and !empty($_POST['ecole-3']) and
                isset($_POST['type_equipe']) and !empty($_POST['type_equipe'])
            ) { //Si il a bien tout rempli


                if (
                    is_string($_POST['prenom-1']) and is_string($_POST['prenom-2']) and is_string($_POST['prenom-3']) and
                    is_string($_POST['nom-1']) and is_string($_POST['nom-2']) and is_string($_POST['nom-3']) and
                    is_string($_POST['email-1']) and is_string($_POST['email-2']) and is_string($_POST['email-3']) and
                    is_string($_POST['tel-1']) and is_string($_POST['tel-2']) and is_string($_POST['tel-3']) and
                    is_string($_POST['team-name']) and is_numeric($_POST['team-hub']) and
                    is_string($_POST['ecole-1']) and is_string($_POST['ecole-2']) and is_string($_POST['ecole-3'])
                    and is_numeric($_POST['type_equipe'])
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
                                intval($_POST['team-hub']) < 4 and intval($_POST['team-hub']) > 0 and strlen($_POST['team-name']) <= 25 and
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
                                            $type_equipe = intval($_POST['type_equipe']);
                                            $team_hub = intval($_POST['team-hub']);

                                            //On veut etre sur qu'aucune team existe avec ce nom
                                            if ($req4 = $conn->prepare("SELECT * FROM teams WHERE nom=?")) {
                                                $req4->bind_param("s", $team_name);
                                                $req4->execute();
                                                $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                                                $req4->close();

                                                if (empty($result4)) { //il ne faut pas qu'une team existe avec ce nom

                                                    $id_team = -1;
                                                    if ($req4 = $conn->prepare("SELECT MAX(numero_emplacement) FROM teams WHERE hub=?")) { //Savoir emplacement
                                                        $req4->bind_param("i", $team_hub);
                                                        $req4->execute();
                                                        $result4 = $req4->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
                                                        $req4->close();   //selectionne l'emplacement max pour savoir le nouvel emplacement
                                                        $valeur_emplacement = intval($result4['MAX(numero_emplacement)']) + 1;
                                                        //Maintenant on créé la team
                                                        if ($req2 = $conn->prepare("INSERT INTO teams (nom, score, classement,valide,hub,type_equipe,numero_emplacement)
                                        VALUES (?,2147483640,0,0,?,?,?)")) { //Creation de la team
                                                            $req2->bind_param("siii", $team_name, $team_hub, $type_equipe, $valeur_emplacement);
                                                            $req2->execute();
                                                            $id_team = $req2->insert_id;
                                                            $req2->close();

                                                            if ($req2 = $conn->prepare("INSERT INTO users (prenom, nom, password, mail, ecole, tel, mdp_a_changer,id_team) VALUES (?,?,?,?,?,?,1,?),(?,?,?,?,?,?,1,?),(?,?,?,?,?,?,1,?)")) { //Creation des 3 users
                                                                $req2->bind_param("ssssssissssssissssssi", $safe_prenom_1, $safe_nom_1, $ready_password_1, $safe_email_1, $safe_ecole_1, $safe_tel_1, $id_team, $safe_prenom_2, $safe_nom_2, $ready_password_2, $safe_email_2, $safe_ecole_2, $safe_tel_2, $id_team, $safe_prenom_3, $safe_nom_3, $ready_password_3, $safe_email_3, $safe_ecole_3, $safe_tel_3, $id_team);
                                                                $req2->execute();
                                                                $req2->close();


                                                                include("send_mail.php");


                                                                send_mail($safe_email_1, htmlspecialchars($team_name), $safe_prenom_1, $safe_prenom_2, $safe_prenom_3, $safe_nom_2, $safe_nom_3, $email_1, $password1);
                                                                send_mail($safe_email_2, htmlspecialchars($team_name), $safe_prenom_2, $safe_prenom_3, $safe_prenom_1, $safe_nom_3, $safe_nom_1, $email_2, $password2);
                                                                send_mail($safe_email_3, htmlspecialchars($team_name), $safe_prenom_3, $safe_prenom_1, $safe_prenom_2, $safe_nom_1, $safe_nom_2, $email_3, $password3);
                                                                header('Location: /index.php?inscr');
                                                                exit();
                                                            } else {
                                                                $erreur = "Erreur lors de la création d'un utilisateur.";
                                                            }
                                                        } else {
                                                            $erreur = "Erreur lors de la récupération de l'emplacement.";
                                                        }
                                                    } else {
                                                        $erreur = "Erreur lors de la création de la team.";
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
    <header class="masthead">
        <div class="container" style="max-width:45rem;">
            <form action="" method="post" name="inscription">
                <div id="equipe" style="display: block;" class="box">
                <!--<button class="page-link" onclick="javascript:avance('equipe', 'participant-1');" href="#">Participant 1</button>-->
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link active" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group">
                      <label for="team-name" class="form-label mt-4">Nom de l'équipe :</label>
                      <input type="text" id="team-name" name="team-name" maxlength="25" 
                      onkeydown="if (event.keyCode == 13)  document.getElementById('button-1').click()" 
                      required class="form-control" placeholder="Saisissez le nom de l'équipe">
                    </div>
                    <div class="form-group">
                      <label for="team-hub" class="form-label mt-4">Choix du lieu :</label>
                      <select name="team-hub" id="team-hub" class="form-select">
                        <option value="1">Hub de l'école des Ponts ParisTech</option>
                        <option value="3">Hub distanciel (Discord)</option>
                      </select>
                    </div>
                    <div class="form-group" style="margin:2rem;">
                      <INPUT type="radio" name="type_equipe" class="form-check-input" value="1"> Équipe 1A des ponts
                      <INPUT type="radio" name="type_equipe" class="form-check-input" value="2"> Équipe étudiante
                      <INPUT type="radio" name="type_equipe" class="form-check-input" value="3" checked> RO
                    </div>
                    <button type="button" id="button-1" onclick="javascript:avance('equipe', 'participant-1');" class="btn btn-info">Étape suivante</button>
                </div>
            
                <div id="participant-1" style="display: none;" class="box">
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">1. Informations Personnelles :</h4>
                      <label for="prenom-1" class="form-label mt-4">Prénom :</label>
                      <input type="text" id="prenom-1" name="prenom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="100" required class="form-control" placeholder="Saisissez le prénom du premier participant">
                      
                      <label for="nom-1" class="form-label mt-4">Nom :</label>
                      <input type="text" id="nom-1" name="nom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="100" required class="form-control" placeholder="Saisissez le nom du premier participant">

                      <label for="ecole-1" class="form-label mt-4">École :</label>
                      <input type="text" id="ecole-1" name="ecole-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="300" required class="form-control" placeholder="Saisissez l'école du premier participant">
                    </div>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">2. Contact :</h4>
                      <label for="email-1" class="form-label mt-4">Email :</label>
                      <input type="email" id="email-1" name="email-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="255" required class="form-control" placeholder="Saisissez le mail du premier participant">
                      
                      <label for="tel-1" class="form-label mt-4">Numéro de téléphone :</label>
                      <input type="tel" id="tel-1" name="tel-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required class="form-control" placeholder="Saisissez le numéro de téléphone du premier participant">
                    </div>
                    <button type="button" onclick="javascript:avance('participant-1', 'equipe');" class="btn btn-info">Étape précédente</button>
                    <button type="button" id="button-2" onclick="javascript:avance('participant-1', 'participant-2');" class="btn btn-info">Étape suivante</button>
                </div>

                <div id="participant-2" style="display: none;" class="box">
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">1. Informations Personnelles :</h4>
                      <label for="prenom-2" class="form-label mt-4">Prénom :</label>
                      <input type="text" id="prenom-2" name="prenom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="100" required class="form-control" placeholder="Saisissez le prénom du second participant">
                      
                      <label for="nom-2" class="form-label mt-4">Nom :</label>
                      <input type="text" id="nom-2" name="nom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="100" required class="form-control" placeholder="Saisissez le nom du second participant">

                      <label for="ecole-2" class="form-label mt-4">École :</label>
                      <input type="text" id="ecole-2" name="ecole-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="300" required class="form-control" placeholder="Saisissez l'école du second participant">
                    </div>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">2. Contact :</h4>
                      <label for="email-2" class="form-label mt-4">Email :</label>
                      <input type="email" id="email-2" name="email-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="255" required class="form-control" placeholder="Saisissez le mail du second participant">
                      
                      <label for="tel-2" class="form-label mt-4">Numéro de téléphone :</label>
                      <input type="tel" id="tel-2" name="tel-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required class="form-control" placeholder="Saisissez le numéro de téléphone du second participant">
                    </div>
                    <button type="button" onclick="javascript:avance('participant-2', 'participant-1');" class="btn btn-info">Étape précédente</button>
                    <button type="button" id="button-2" onclick="javascript:avance('participant-2', 'participant-3');" class="btn btn-info">Étape suivante</button>
                </div>

                <div id="participant-3" style="display: none;" class="box">
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">1. Informations Personnelles :</h4>
                      <label for="prenom-3" class="form-label mt-4">Prénom :</label>
                      <input type="text" name="prenom-3" maxlength="100" required class="form-control" placeholder="Saisissez le prénom du troisième participant">
                      
                      <label for="nom-3" class="form-label mt-4">Nom :</label>
                      <input type="text" name="nom-3" maxlength="100" required class="form-control" placeholder="Saisissez le nom du troisième participant">

                      <label for="ecole-3" class="form-label mt-4">École :</label>
                      <input type="text" id="ecole-3" name="ecole-3" maxlength="300" required class="form-control" placeholder="Saisissez l'école du troisième participant">
                    </div>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">2. Contact :</h4>
                      <label for="email-3" class="form-label mt-4">Email :</label>
                      <input type="email" name="email-3" maxlength="255" required class="form-control" placeholder="Saisissez le mail du troisième participant">
                      
                      <label for="tel-3" class="form-label mt-4">Numéro de téléphone :</label>
                      <input type="tel" name="tel-3" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required class="form-control" placeholder="Saisissez le numéro de téléphone du troisième participant">
                    </div>
                    <div class="form-check list-centered" style="margin-top:2rem;">
                      <input type="checkbox" id="GCU" name="GCU" required class="form-check-input">
                      <label class="form-check-label" for="GCU">Nous acceptons le <a target="_blank" href="/#reglement">réglement du concours</a></label>
                    </div>
                    <div class="box-invisible" style="color:grey;">
                      <p>
                        <em>En m’inscrivant je reconnais que j’accepte intégralement le réglement, 
                        et que mon nom et mon école soient affichés publiquement sur le site.<br/><br/>
                        Aucun autre usage que ceux requis par le concours ne sera fait de vos 
                        données, et vous pouvez en demander la suppression en vous adressant 
                        directement au Club Informatique.</em>
                      </p>
                    </div>
                    <button type="button" onclick="javascript:avance('participant-3', 'participant-2');" class="btn btn-info">Étape précédente</button>
                    <button type="submit" name="submit" class="btn btn-info">S'inscrire</button>
                </div>
            </form>
        </div>
    </header>
    <?php
        }
    } else //Si date d'inscription dépassée
    {
        header('Location: /index.php?inscriptions_closes');
        exit();
    }
} else { //si l'utilisateur est déjà connecté
    header('Location: /index.php?already_co');
    exit();
}

if (isset($erreur)) {
    include("header.php");
    include("navbar.php");
    //si on doit afficher le formulaire avec un message d'erreur
    echo 
    '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        '.$erreur.'
    </div>';
    ?>

    <header class="masthead">
        <!--AFFICHAGE DU FORMULAIRE AVEC ERREUR-->
        <div class="container" style="max-width:45rem;">
            <form action="" method="post" name="inscription">
                <div id="equipe" style="display: block;" class="box">
                <!--<button class="page-link" onclick="javascript:avance('equipe', 'participant-1');" href="#">Participant 1</button>-->
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link active" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group">
                      <label for="team-name" class="form-label mt-4">Nom de l'équipe :</label>
                      <input type="text" id="team-name" name="team-name" maxlength="25" 
                      onkeydown="if (event.keyCode == 13)  document.getElementById('button-1').click()" 
                      required class="form-control" value="<?php if (isset($_POST['team-name'])) {echo htmlspecialchars($_POST['team-name']);} ?>">
                    </div>
                    <div class="form-group">
                      <label for="team-hub" class="form-label mt-4">Choix du lieu :</label>
                      <select name="team-hub" id="team-hub" class="form-select">
                        <option value="1" <?php if (isset($_POST['team-hub'])) {if ($_POST['team-hub'] == 1) {echo 'selected';}} ?>>Hub de l'école des Ponts ParisTech</option>
                        <option value="3" <?php if (isset($_POST['team-hub'])) {if ($_POST['team-hub'] == 3) {echo 'selected';}} ?>>Hub distanciel (Discord)</option>
                      </select>
                    </div>
                    <div class="form-group" style="margin:2rem;">
                      <INPUT type="radio" name="type_equipe" class="form-check-input" value="1"> Équipe 1A des ponts
                      <INPUT type="radio" name="type_equipe" class="form-check-input" value="2"> Équipe étudiante
                      <INPUT type="radio" name="type_equipe" class="form-check-input" value="3" checked> RO
                    </div>
                    <button type="button" id="button-1" onclick="javascript:avance('equipe', 'participant-1');" class="btn btn-info">Étape suivante</button>
                </div>
            
                <div id="participant-1" style="display: none;" class="box">
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">1. Informations Personnelles :</h4>
                      <label for="prenom-1" class="form-label mt-4">Prénom :</label>
                      <input type="text" id="prenom-1" name="prenom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="100" required class="form-control" value="<?php if (isset($_POST['prenom-1'])) {echo htmlspecialchars($_POST['prenom-1']);} ?>">
                      
                      <label for="nom-1" class="form-label mt-4">Nom :</label>
                      <input type="text" id="nom-1" name="nom-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="100" required class="form-control" value="<?php if (isset($_POST['nom-1'])) {echo htmlspecialchars($_POST['nom-1']);} ?>">

                      <label for="ecole-1" class="form-label mt-4">École :</label>
                      <input type="text" id="ecole-1" name="ecole-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="300" required class="form-control" value="<?php if (isset($_POST['ecole-1'])) {echo htmlspecialchars($_POST['ecole-1']);} ?>">
                    </div>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">2. Contact :</h4>
                      <label for="email-1" class="form-label mt-4">Email :</label>
                      <input type="email" id="email-1" name="email-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="255" required class="form-control" value="<?php if (isset($_POST['email-1'])) {echo htmlspecialchars($_POST['email-1']);} ?>">
                      
                      <label for="tel-1" class="form-label mt-4">Numéro de téléphone :</label>
                      <input type="tel" id="tel-1" name="tel-1" onkeydown="if (event.keyCode == 13)  document.getElementById('button-2').click()" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required class="form-control" value="<?php if (isset($_POST['tel-1'])) {echo htmlspecialchars($_POST['tel-1']);} ?>">
                    </div>
                    <button type="button" onclick="javascript:avance('participant-1', 'equipe');" class="btn btn-info">Étape précédente</button>
                    <button type="button" id="button-2" onclick="javascript:avance('participant-1', 'participant-2');" class="btn btn-info">Étape suivante</button>
                </div>

                <div id="participant-2" style="display: none;" class="box">
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">1. Informations Personnelles :</h4>
                      <label for="prenom-2" class="form-label mt-4">Prénom :</label>
                      <input type="text" id="prenom-2" name="prenom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="100" required class="form-control" value="<?php if (isset($_POST['prenom-2'])) {echo htmlspecialchars($_POST['prenom-2']);} ?>">
                      
                      <label for="nom-2" class="form-label mt-4">Nom :</label>
                      <input type="text" id="nom-2" name="nom-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="100" required class="form-control" value="<?php if (isset($_POST['nom-2'])) {echo htmlspecialchars($_POST['nom-2']);} ?>">

                      <label for="ecole-2" class="form-label mt-4">École :</label>
                      <input type="text" id="ecole-2" name="ecole-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="300" required class="form-control" value="<?php if (isset($_POST['ecole-2'])) {echo htmlspecialchars($_POST['ecole-2']);} ?>">
                    </div>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">2. Contact :</h4>
                      <label for="email-2" class="form-label mt-4">Email :</label>
                      <input type="email" id="email-2" name="email-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="255" required class="form-control" value="<?php if (isset($_POST['email-2'])) {echo htmlspecialchars($_POST['email-2']);} ?>">
                      
                      <label for="tel-2" class="form-label mt-4">Numéro de téléphone :</label>
                      <input type="tel" id="tel-2" name="tel-2" onkeydown="if (event.keyCode == 13)  document.getElementById('button-3').click()" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required class="form-control" value="<?php if (isset($_POST['tel-2'])) {echo htmlspecialchars($_POST['tel-2']);} ?>">
                    </div>
                    <button type="button" onclick="javascript:avance('participant-2', 'participant-1');" class="btn btn-info">Étape précédente</button>
                    <button type="button" id="button-2" onclick="javascript:avance('participant-2', 'participant-3');" class="btn btn-info">Étape suivante</button>
                </div>

                <div id="participant-3" style="display: none;" class="box">
                    <ul class="nav nav-tabs nav-fill justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="#">Équipe</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 1</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Participant 2</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#">Participant 3</a></li>
                    </ul>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">1. Informations Personnelles :</h4>
                      <label for="prenom-3" class="form-label mt-4">Prénom :</label>
                      <input type="text" name="prenom-3" maxlength="100" required class="form-control" value="<?php if (isset($_POST['prenom-3'])) {echo htmlspecialchars($_POST['prenom-3']);} ?>">
                      
                      <label for="nom-3" class="form-label mt-4">Nom :</label>
                      <input type="text" name="nom-3" maxlength="100" required class="form-control" value="<?php if (isset($_POST['nom-3'])) {echo htmlspecialchars($_POST['nom-3']);} ?>">

                      <label for="ecole-3" class="form-label mt-4">École :</label>
                      <input type="text" id="ecole-3" name="ecole-3" maxlength="300" required class="form-control" value="<?php if (isset($_POST['ecole-3'])) {echo htmlspecialchars($_POST['ecole-3']);} ?>">
                    </div>
                    <div class="form-group" style="margin-top:2rem;">
                      <h4 style="color:#2f2f2f">2. Contact :</h4>
                      <label for="email-3" class="form-label mt-4">Email :</label>
                      <input type="email" name="email-3" maxlength="255" required class="form-control" value="<?php if (isset($_POST['email-3'])) {echo htmlspecialchars($_POST['email-3']);} ?>">
                      
                      <label for="tel-3" class="form-label mt-4">Numéro de téléphone :</label>
                      <input type="tel" name="tel-3" maxlength="15" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" required class="form-control" value="<?php if (isset($_POST['tel-3'])) {echo htmlspecialchars($_POST['tel-3']);} ?>">
                    </div>
                    <div class="form-check list-centered" style="margin-top:2rem;">
                      <input type="checkbox" id="GCU" name="GCU" required class="form-check-input">
                      <label class="form-check-label" for="GCU">Nous acceptons le <a target="_blank" href="/#reglement">réglement du concours</a></label>
                    </div>
                    <div class="box-invisible" style="color:grey;">
                      <p>
                        <em>En m’inscrivant je reconnais que j’accepte intégralement le réglement, 
                        et que mon nom et mon école soient affichés publiquement sur le site.<br/><br/>
                        Aucun autre usage que ceux requis par le concours ne sera fait de vos 
                        données, et vous pouvez en demander la suppression en vous adressant 
                        directement au Club Informatique.</em>
                      </p>
                    </div>
                    <button type="button" onclick="javascript:avance('participant-3', 'participant-2');" class="btn btn-info">Étape précédente</button>
                    <button type="submit" name="submit" class="btn btn-info">S'inscrire</button>
                </div>
            </form>
        </div>
    </header>

<?php
}

include("footer.php");
?>