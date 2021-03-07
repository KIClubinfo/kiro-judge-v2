<?php
include("config.php");

$team_id_affiche = 1; //Team par défaut
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
      $erreur = "Erreur lors de la selection des teams.";
    }
  } else {
    $erreur = "Vous n'avez pas entré un chiffre.";
  }
}

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
  $erreur2 = "Erreur lors de la selection des membres de l'équipe.";
}


if (isset($erreur)) { //si erreur dans la team demandé
  include("header.php");
?>
  <div class="content">
    <div class="container">
      <div class="erreur"><?php echo $erreur; ?></div>
    </div>
  </div>
<?php
}

if (isset($erreur2)) { //Si erreur dans l'afficage de la team
  include("header.php");
?>
  <div class="content">
    <div class="container">
      <div class="erreur"><?php echo $erreur2; ?></div>
    </div>
  </div>
<?php
}

if (isset($membre_3)) { //Si tout a bien marché on affiche tout
  include("header.php");
?>
  <div class="content">
    <div class="container">
      <?php
      if (!$team_affiche->valide) { //Si la team n'est pas active
        echo '<div class="erreur">Cette équipe n\'est pas active.</div>';
      }
      ?>
      <table border="4">
        <thead>
          <tr>
            <?php if (is_admin()) {
              echo '<th>Id</th>';
            } ?>
            <th>Nom d'équipe</th>
            <th>Classement</th>
            <th>Score</th>
            <th>Hub</th>
            <th>Numéro d'emplacement</th>

          </tr>
        </thead>
        <tbody>
          <tr>
            <?php if (is_admin()) {
              echo '<td>' . htmlspecialchars($team_affiche->id) . '</td>';
            } ?>
            <td><?php echo htmlspecialchars($team_affiche->nom); ?></td>
            <td><?php echo htmlspecialchars($team_affiche->classement); ?></td>
            <td><?php echo htmlspecialchars($team_affiche->score); ?></td>
            <td><?php if ($team_affiche->hub == 1) {
                  echo "Hub de l'École des Ponts";
                } else {
                  echo "Hub du Plateau Saclay";
                } ?></td>
            <td><?php echo htmlspecialchars($team_affiche->numero_emplacement); ?></td>

          </tr>
        </tbody>
      </table><br /> <br />
      <table border="4">
        <thead>
          <tr>
            <th>Prénom</th>
            <th>Nom</th>
            <th>École</th>
            <?php if (is_admin()) { //Si affichage admin 
            ?>
              <th>Numéro de téléphone</th>
              <th>Mail</th>
              <th>Id</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo htmlspecialchars($membre_1->prenom); ?></td>
            <td><?php echo htmlspecialchars($membre_1->nom); ?></td>
            <td><?php echo htmlspecialchars($membre_1->ecole); ?></td>
            <?php if (is_admin()) {
              echo '<td>' . htmlspecialchars($membre_1->tel) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<td>' . htmlspecialchars($membre_1->mail) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<td><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_1->id) . '">' . htmlspecialchars($membre_1->id) . '</a></td>';
            } ?>
          </tr>
          <tr>
            <td><?php echo htmlspecialchars($membre_2->prenom); ?></td>
            <td><?php echo htmlspecialchars($membre_2->nom); ?></td>
            <td><?php echo htmlspecialchars($membre_2->ecole); ?></td>
            <?php if (is_admin()) {
              echo '<td>' . htmlspecialchars($membre_2->tel) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<td>' . htmlspecialchars($membre_2->mail) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<td><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_2->id) . '">' . htmlspecialchars($membre_2->id) . '</a></td>';
            } ?>
          </tr>
          <tr>
            <td><?php echo htmlspecialchars($membre_3->prenom); ?></td>
            <td><?php echo htmlspecialchars($membre_3->nom); ?></td>
            <td><?php echo htmlspecialchars($membre_3->ecole); ?></td>
            <?php if (is_admin()) {
              echo '<td>' . htmlspecialchars($membre_3->tel) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<td>' . htmlspecialchars($membre_3->mail) . '</td>';
            } ?>
            <?php if (is_admin()) {
              echo '<td><a href="edit_info_admin.php?id=' . htmlspecialchars($membre_3->id) . '">' . htmlspecialchars($membre_3->id) . '</a></td>';
            } ?>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
<?php
}
include("footer.php");
?>