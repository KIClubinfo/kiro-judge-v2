<?php
include("config.php");
include("header.php");
include("navbar.php");

if ($req2 = $conn->prepare("SELECT id FROM teams ORDER BY score ASC")) { //toutes les id des teams
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
    <div class="container-fluid">
        <div class="row">
            <?php include("menuconcours.php"); ?>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="box-tableau table table-hover text-white">
                        <thead>
                          <tr class="table-dark">
                            <th scope="col">Nom d'équipe</th>
                            <th scope="col">Score</th>
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
                            <td><?php echo htmlspecialchars(number_format($team_affiche->public_score)); ?></td>
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