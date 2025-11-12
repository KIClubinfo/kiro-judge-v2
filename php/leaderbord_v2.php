<?php
include("config.php");
include("header.php");
include("navbar.php");

$date = new DateTime(null, new DateTimeZone('Europe/Paris'));

if ($date < $datelast30concours) {

    $datelast30concours_str = $datelast30concours->format('Y-m-d H:i:s');

    $sql = "
        SELECT team_id, MIN(score) AS best_score
        FROM solutions
        WHERE upload_time < ? AND score > 0
        GROUP BY team_id
        ORDER BY best_score ASC
    ";

    if ($req2 = $conn->prepare($sql)) {

        $req2->bind_param('s', $datelast30concours_str);
        $req2->execute();

        $result_ids = $req2->get_result()->fetch_all(MYSQLI_ASSOC);
        $req2->close();

    } else {
        $erreur3 = "Erreur lors de la préparation de la requête SQL.";
        die($erreur3);
    }
}
else{
  if ($req2 = $conn->prepare("SELECT id FROM teams ORDER BY score ASC")) { //toutes les id des teams
    $req2->execute();
    $result_ids = $req2->get_result()->fetch_all(MYSQLI_ASSOC); //resulats de la requête

    $req2->close();
}
else{
    $erreur3 = "Erreur lors de la connexion à la base de données.";
    die();
}
}


?>
<header class="masthead min-vh-80">
    <div class="container-fluid">
        <div class="row">
            <?php include("menuconcours.php"); ?>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="box-tableau table table-hover text-white">
                        <thead>
                          <tr class="table-dark">
                            <th scope="col">Nom d'équipe</th>
                            <th scope="col">Score (lower is better)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          foreach($result_ids as $id_team){
                          $id_team = $id_team["id"];
                          $team_affiche = new team($id_team);
                          ?>
                          <tr>
                            <th scope="row"><a href="teams.php?id_team=<?php echo htmlspecialchars($team_affiche->id) ?>"><?php echo htmlspecialchars($team_affiche->nom); ?></a></th>
                            <td><?php echo htmlspecialchars(number_format($team_affiche->score)); ?></td>
                          </tr>
                          <?php
                          }
                          ?>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
      </div>
  </header>
  <?php
  include("footer.php");
  ?>
