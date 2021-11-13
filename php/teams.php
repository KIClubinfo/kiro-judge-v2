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
  echo 
  '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    '.$erreur.'
  </div>';
}
if (isset($erreur2)) { //Si erreur dans l'afficage de la team
  echo 
  '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    '.$erreur2.'
  </div>';
}

if (isset($membre_3)) { //Si tout a bien marché on affiche tout
  if (is_admin() and !$team_affiche->valide) { //Si la team n'est pas active
    echo 
      '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed; bottom:0; margin:1rem;">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        Cette équipe n\'est pas active.
      </div>';
  }
?>
  <header class="masthead min-vh-80">
      <div class="container">
          <div class="table-responsive">
            <table class="box-tableau table table-hover text-white">
                <thead>
                  <tr class="table-dark">
                    <?php if (is_admin()) {echo '<th scope="col">Id</th>';} ?>
                    <th scope="col">Nom d'équipe</th>
                    <th scope="col">Hub</th>
                    <th scope="col">Type</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php if (is_admin()) {
                    echo '<th scope="row">' . htmlspecialchars($team_affiche->id) . '</th>';
                    } ?>
                    <td><a href="teams.php?id_team=<?php echo htmlspecialchars($team_affiche->id) ?>"><?php echo htmlspecialchars($team_affiche->nom); ?></a></td>
                    <td><?php if ($team_affiche->hub == 1) {echo "Hub de l'École des Ponts";} else{echo "Hub distanciel (Discord)";}?></td>
                    <td><?php if ($team_affiche->type_equipe == 1) {echo "1A";} elseif ($team_affiche->type_equipe == 2) {echo "Étudiante";} else{echo "Autre";}?></td>
                  </tr>
                </tbody>
            </table>
          </div>
          <div class="table-responsive">
            <table class="box-tableau table table-hover text-white">
              <thead>
                <tr class="table-dark">
                  <th scope="col">Prénom</th>
                  <th scope="col">Nom</th>
                  <th scope="col">École</th>
                  <?php if (is_admin()) { //Si affichage admin
                  ?>
                    <th scope="col">Numéro de téléphone</th>
                    <th scope="col">Mail</th>
                    <th scope="col">Id</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row"><?php echo htmlspecialchars($membre_1->prenom); ?></th>
                  <td><?php echo htmlspecialchars($membre_1->nom); ?></td>
                  <td><?php echo htmlspecialchars($membre_1->ecole); ?></td>
                  <?php if (is_admin()) {
                    echo '<td>' . htmlspecialchars($membre_1->tel) . '</td>';
                    echo '<td>' . htmlspecialchars($membre_1->mail) . '</td>';
                    echo '<td><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_1->id) . '">' . htmlspecialchars($membre_1->id) . '</a></td>';
                  } ?>
                </tr>
                <tr>
                  <th scope="row"><?php echo htmlspecialchars($membre_2->prenom); ?></th>
                  <td><?php echo htmlspecialchars($membre_2->nom); ?></td>
                  <td><?php echo htmlspecialchars($membre_2->ecole); ?></td>
                  <?php if (is_admin()) {
                    echo '<td>' . htmlspecialchars($membre_2->tel) . '</td>';
                    echo '<td>' . htmlspecialchars($membre_2->mail) . '</td>';
                    echo '<td><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_2->id) . '">' . htmlspecialchars($membre_2->id) . '</a></td>';
                  } ?>
                </tr>
                <tr>
                  <th scope="row"><?php echo htmlspecialchars($membre_3->prenom); ?></th>
                  <td><?php echo htmlspecialchars($membre_3->nom); ?></td>
                  <td><?php echo htmlspecialchars($membre_3->ecole); ?></td>
                  <?php if (is_admin()) {
                    echo '<td>' . htmlspecialchars($membre_3->tel) . '</td>';
                    echo '<td>' . htmlspecialchars($membre_3->mail) . '</td>';
                    echo '<td><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_3->id) . '">' . htmlspecialchars($membre_3->id) . '</a></td>';
                  } ?>
                </tr>
              </tbody>
            </table>
          </div>
      </div>
  </header>
<?php
}else{//Si on n'affiche aucune team en particulier on va toutes les afficher
  if ($req2 = $conn->prepare("SELECT id FROM teams")) { //toutes les id des teams
      $req2->execute();
      $result_ids = $req2->get_result()->fetch_all(MYSQLI_ASSOC); //resulats de la requête

      $req2->close();
  }
  else{
      $erreur3 = "Erreur lors de la connexion à la base de données.";
      die();
  }

  ?>
  <header class="masthead min-vh-80">
      <div class="container">
          <div class="table-responsive">
            <table class="box-tableau table table-hover text-white">
                <thead>
                  <tr class="table-dark">
                    <?php if (is_admin()) {?>
                    <th scope="col">Id</th><?php ; } ?>
                    <th scope="col">Nom d'équipe</th>
                    <th scope="col">Hub</th>
                    <th scope="col">Type</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach($result_ids as $id_team){
                  $id_team = $id_team["id"];
                  $team_affiche = new team($id_team);
                  ?>
                  <tr>
                    <?php if (is_admin()) { ?>
                    <th scope="row"><?php echo htmlspecialchars($team_affiche->id) ?></th><?php  } ?>
                    <td><a href="teams.php?id_team=<?php echo htmlspecialchars($team_affiche->id) ?>"><?php echo htmlspecialchars($team_affiche->nom); ?></a></td>
                    <td><?php if ($team_affiche->hub == 1) {echo "Hub de l'École des Ponts";} else{echo "Hub distanciel (Discord)";}?></td>
                    <td><?php if ($team_affiche->type_equipe == 1) {echo "1A";} elseif ($team_affiche->type_equipe == 2) {echo "Étudiante";} else{echo "Autre";}?></td>
                  </tr>
                  <?php
                  }
                  }?>
                </tbody>
            </table>
          </div>
      </div>
  </header>
  <?php
  include("footer.php");
  ?>
