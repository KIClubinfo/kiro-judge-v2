<?php
include("config.php");
include("header.php");
include("navbar.php");

$date = new DateTime(null, new DateTimeZone('Europe/Paris'));

if ($date > $datelast30concours) {

    // Conversion de la date en string SQL
    $datelast30concours_str = $datelast30concours->format('Y-m-d H:i:s');

    // Sélectionne le meilleur score (le plus petit) pour chaque équipe
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

} else {

    // Sinon on prend simplement toutes les équipes triées par score
    $sql = "SELECT id, score FROM teams ORDER BY score ASC";

    if ($req2 = $conn->prepare($sql)) {
        $req2->execute();
        $result_ids = $req2->get_result()->fetch_all(MYSQLI_ASSOC);
        $req2->close();
    } else {
        $erreur3 = "Erreur lors de la connexion à la base de données.";
        die($erreur3);
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
                          foreach ($result_ids as $row) {
                              // Compatibilité : la colonne peut s’appeler "team_id" ou "id"
                              $id_team = isset($row["team_id"]) ? $row["team_id"] : $row["id"];
                              $team_affiche = new team($id_team);

                              // Récupération du score
                              $score = isset($row["best_score"]) ? $row["best_score"] : $team_affiche->score;
                              ?>
                              <tr>
                                <th scope="row">
                                  <a href="teams.php?id_team=<?php echo htmlspecialchars($team_affiche->id); ?>">
                                    <?php echo htmlspecialchars($team_affiche->nom); ?>
                                  </a>
                                </th>
                                <td>
                                  <?php
                                  if (is_numeric($score)) {
                                      echo number_format((float)$score, 2, '.', ' ');
                                  } else {
                                      echo '—';
                                  }
                                  ?>
                                </td>
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