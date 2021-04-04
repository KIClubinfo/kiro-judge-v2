<?php
include("config.php");
include("header.php");
include("navbar.php");

$team_id_affiche = -1; //pas de team selectionnee
if (isset($_GET['id_team'])) { //Si on veut voir une team spécificique
  if (is_numeric($_GET['id_team'])) {
    $team_id = intval(sanitize_string($_GET['id_team']));
    if ($req = $conn->prepare("SELECT MAX(id) FROM teams")) { //Savoir emplacement
      $req->execute();
      $result = $req->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
      $req->close();   //selectionne l'emplacement max pour savoir le nouvel emplacement
      $max_team_id = intval($result['MAX(id)']);
      if ($team_id <= $max_team_id and $team_id > 0) {
        $team_id_affiche = $team_id;
      } else {
        $erreur = "L'équipe demandée n'existe pas.";
      }
    } else {
      $erreur = "Erreur lors de la sélection des teams.";
    }
  } else {
    $erreur = "Vous n'avez pas entré un chiffre.";
  }
}

if ($team_id_affiche != -1){
  $team_affiche = new team($team_id_affiche);
  if ($req = $conn->prepare("SELECT id FROM users WHERE id_team=?")) { //requete préparée
    $req->bind_param("i", $team_id_affiche);
    $req->execute();
    $result = $req->get_result()->fetch_all(MYSQLI_ASSOC); //resulats de la requête
    $req->close();
    include("header.php");
    $membre_1 = new user($result[0]["id"]);
    $membre_2 = new user($result[1]["id"]);
    $membre_3 = new user($result[2]["id"]);
  } else {
    $erreur2 = "Erreur lors de la sélection des membres de l'équipe.";
  }

}


if (isset($erreur)) { //si erreur dans la team demandé
  include("header.php");
?>
  <div class="content" style="min-height: 35%;">
    <div class="container">
      <?php popup($erreur); ?>

    </div>
  </div>
<?php
}

if (isset($erreur2)) { //Si erreur dans l'afficage de la team
  include("header.php");
?>
  <div class="content" style="min-height: 35%;">
    <div class="container">
    <?php popup($erreur2); ?>
    </div>
  </div>
<?php
}

if (isset($membre_3)) { //Si tout a bien marché on affiche tout
  include("header.php");
?>
  <div class="content limiter" style="min-height: 35%;">
    <div class="container">
      <?php
      if (!$team_affiche->valide) { //Si la team n'est pas active
        popup("Cette équipe n\'est pas active.");
      }
      ?>
      <div class="wrap-table100" style="margin-top: 5vh;">
        <div class="table">

          <div class="row2 header">
            <?php if (is_admin()) {
              echo '<div class="cell">Id</div>';
            } ?>
            <div class="cell">Nom d'équipe</div>
            <div class="cell">Classement</div>
            <div class="cell">Score</div>
            <div class="cell">Hub</div>
            <div class="cell">Emplacement</div>
          </div>
          <div class="row2">
            <?php if (is_admin()) {
              echo '<div class="cell">' . htmlspecialchars($team_affiche->id) . '</div>';
            } ?>
            <div class="cell"><?php echo htmlspecialchars($team_affiche->nom); ?></div>
            <div class="cell"><?php echo htmlspecialchars($team_affiche->classement); ?></div>
            <div class="cell"><?php echo htmlspecialchars($team_affiche->score); ?></div>
            <div class="cell"><?php if ($team_affiche->hub == 1) {
                                echo "Hub de l'École des Ponts";
                              } elseif ($team_affiche->hub == 2) {
                                echo "Hub du Plateau Saclay";
                              } else{
                                echo "Hub distanciel (Discord)";
                              }?></div>
            <div class="cell"><?php echo htmlspecialchars($team_affiche->numero_emplacement); ?></div>
          </div>
        </div>
      </div>
      <div class="wrap-table100" style="margin-top: 5vh;">
        <div class="table">

          <div class="row2 header">
            <div class="cell">Prénom</div>
            <div class="cell">Nom</div>
            <div class="cell">École</div>
            <?php if (is_admin()) { //Si affichage admin
            ?>
              <div class="cell">Numéro de téléphone</div>
              <div class="cell">Mail</div>
              <div class="cell">Id</div>
            <?php } ?>
          </div>
          <div class="row2">
            <div class="cell"><?php echo htmlspecialchars($membre_1->prenom); ?></div>
            <div class="cell"><?php echo htmlspecialchars($membre_1->nom); ?></div>
            <div class="cell"><?php echo htmlspecialchars($membre_1->ecole); ?></div>
            <?php if (is_admin()) {
              echo '<div class="cell">' . htmlspecialchars($membre_1->tel) . '</div>';
            } ?>
            <?php if (is_admin()) {
              echo '<div class="cell">' . htmlspecialchars($membre_1->mail) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<div class="cell"><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_1->id) . '">' . htmlspecialchars($membre_1->id) . '</a></div>';
            } ?>
          </div>

          <div class="row2">
            <div class="cell"><?php echo htmlspecialchars($membre_2->prenom); ?></div>
            <div class="cell"><?php echo htmlspecialchars($membre_2->nom); ?></div>
            <div class="cell"><?php echo htmlspecialchars($membre_2->ecole); ?></div>
            <?php if (is_admin()) {
              echo '<div class="cell">' . htmlspecialchars($membre_2->tel) . '</div>';
            } ?>
            <?php if (is_admin()) {
              echo '<div class="cell">' . htmlspecialchars($membre_2->mail) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<div class="cell"><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_2->id) . '">' . htmlspecialchars($membre_2->id) . '</a></div>';
            } ?>
          </div>

          <div class="row2">
            <div class="cell"><?php echo htmlspecialchars($membre_2->prenom); ?></div>
            <div class="cell"><?php echo htmlspecialchars($membre_2->nom); ?></div>
            <div class="cell"><?php echo htmlspecialchars($membre_2->ecole); ?></div>
            <?php if (is_admin()) {
              echo '<div class="cell">' . htmlspecialchars($membre_2->tel) . '</div>';
            } ?>
            <?php if (is_admin()) {
              echo '<div class="cell">' . htmlspecialchars($membre_2->mail) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<div class="cell"><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_2->id) . '">' . htmlspecialchars($membre_2->id) . '</a></div>';
            } ?>
          </div>
        </div>
      </div>
    </div>

<?php
}else{//Si on n'affiche aucune team en particulier on va toutes les afficher
  if ($req2 = $conn->prepare("SELECT id FROM teams")) { //toutes les id des teams
      $req2->execute();
      $result_ids = $req2->get_result()->fetch_all(MYSQLI_ASSOC); //resulats de la requête

      $req2->close();
  }
  else{
      $erreur3 = "Erreur lors de la connexion à la base de donnée.";
      die();
  }

  ?>
  <div class="content limiter" style="min-height: 35%;">
    <div class="container">
      <div class="wrap-table100" style="margin-top: 5vh;">
        <div class="table">
          <div class="row2 header">
          <?php if (is_admin()) {?>
          <div class="cell">Id</div><?php ; } ?>
          <div class="cell">Nom d'équipe</div>
          <div class="cell">Classement</div>
          <div class="cell">Score</div>
          <div class="cell">Hub</div>
          <div class="cell">Type</div>

        </div>

  <?php
  foreach($result_ids as $id_team){
      $id_team = $id_team["id"];
      $team_affiche = new team($id_team);
      ?>
      <div class="row2">
        <?php if (is_admin()) { ?>
      <div class="cell"><?php echo htmlspecialchars($team_affiche->id) ?></div> <?php  } ?>
        <div class="cell"><?php echo htmlspecialchars($team_affiche->nom); ?></div>
        <div class="cell"><?php echo htmlspecialchars($team_affiche->classement); ?></div>
        <div class="cell"><?php echo htmlspecialchars($team_affiche->score); ?></div>
        <div class="cell"><?php if ($team_affiche->hub == 1) {
                            echo "Hub de l'École des Ponts";
                          } elseif ($team_affiche->hub == 2) {
                            echo "Hub du Plateau Saclay";
                          } else{
                            echo "Hub distanciel (Discord)";
                          }?></div>
        <div class="cell"><?php if ($team_affiche->type_equipe == 1) {
                                              echo "1A";
                                } elseif ($team_affiche->type_equipe == 2) {
                                              echo "Étudiante";
                                } else{
                                              echo "Autre";
                                  }?></div>
      </div>


  <?php
  }
  echo "</div></div></div></div>";
}

 ?>
  <?php

include("footer.php");
  ?>
