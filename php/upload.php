<?php
include("config.php");

if (!isset($_SESSION["user"])) {
  header('Location: index.php?not_connected');
  exit();
}

include("date_protection.php");
$dateconcours = new DateTime('2021-05-06 11:30:00');
protect_before($dateconcours);

include("header.php");
include("navbar.php");

?>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="styletest.css" />
    <script src="scripts/display_errors.js"></script>
</head>

<header class="masthead" >
        <div class="container-fluid">
            <div class="row">
                <?php include("menuconcours.php");?>
                <div class="col-lg-8">
                  <div class="container">
                      <div class="box-concours" style="padding-top:2rem;">
<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user'])) {
      if (isset($_POST['submit'])) {
          include("instances.php");
        // On loop sur les instances envoyees
        // On execute le programme qui permet d'evaluer si ca fonctionne bien
        // $result_array = [];
        // exec("python checker", $result_array);
        // First line is the score
        // Next lines are the errors

        // Update DB if score is valid
        // Send new score if OK
        $team = new team($_SESSION["user"]->id_team);
        $user = new user($_SESSION["user"]->id);
        
        echo '<h3 style="color:black;">Résultat de l\'Upload :</h3>
              <p style="color:#2f2f2f; font-size:large;">Vous trouverez ci-dessous le résultat de l\'upload :</p>';
        
        foreach ($_FILES["solutions"]["error"] as $key => $error) {
          if ($error == UPLOAD_ERR_OK) {

            $solution_id = create_solution($team->id, $user->id, $key);
            $tmp_name = $_FILES["solutions"]["tmp_name"][$key];
            $file_path = get_solution_filepath($key, $team->id, $solution_id);


            move_uploaded_file($tmp_name, $file_path);
            $command = 'python3 /var/www/html/solution_checker/main.py -s "%s" -i "%s"';

            $old_score = $team->get_instance_best_score($key);


            $command_format = sprintf($command, $file_path, INSTANCE_FILES[$key]);

              $results = [];
            exec($command_format, $results);

            $score = intval($results[0]);

            $errors_string = "";
            for ($i = 1; $i < sizeof($results); $i++) {
              $errors_string .= $results[$i] . PHP_EOL;
            }
            $errors_string = substr($errors_string, 0, -1);

            update_solution($solution_id, $score, $errors_string);


  ?>
  <div>
    <?php
            echo "<h4 style='color:black; font-size:3rem; font-weight:500; text-align:center; margin:2rem;'>".INSTANCE_NAMES[$key]."</h4>";
            // echo "<span style='font-size: 1.7em;margin-left:350px;'>".INSTANCE_NAMES[$key]." : </span>";
            if ($score < 0) {
              display_errors_button($errors_string);
            } else {
                $color = ($old_score > $score) ?"green" : "red";
              echo "<h4 style='color: {$color}; font-size:3rem; font-weight:500; text-align:center; margin:2rem;'>" . $score . "</h4>";
            }
    ?>
  </div>
  <?php


          } else {
  ?>
  <div>
    <?php
            // echo "<span style='font-size: 1.7em;margin-left:350px;'>".INSTANCE_NAMES[$key]."</span>";
            // echo "<span style='font-size: 1.7em;'> : Pas de solution fournie.</span>";
    ?>
  </div>

<?php
    echo '<br />';
          }
        }
        $team->update_score();
        http_response_code(200);
        echo '<p style="color:#2f2f2f; text-align:left; font-size:large; margin-bottom:3rem;">
                  <em style="color:red;">Rouge : </em> La solution que vous avez proposé est moins bonne que la précédente </br>
                  <em style="color:green;">Vert : </em>La solution que vous avez proposé est meilleure que la précédente </br>
              </p>';
      } else {
        http_response_code(400);
      }
    } else {
      http_response_code(401);
    }
  }
?>
                        <h3 style="color:black;">Upload des instances :</h3>
                        <p style="color:#2f2f2f; font-size:large;">Vous pouvez envoyer un ou plusieurs fichiers à la fois :</p>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <h4 style="color:black; font-weight:500; text-align:left; margin-top:2rem;">Première instance :</h4>
                                <input class="form-control" type="file" name="solutions[]" style="border: 1px solid #0dcaf0;">
                            </div>
                            <div class="form-group">
                                <h4 style="color:black; font-weight:500; text-align:left; margin-top:2rem;">Seconde instance :</h4>
                                <input class="form-control" type="file" name="solutions[]" style="border: 1px solid #0dcaf0;">
                            </div>
                            <div class="form-group">
                                <h4 style="color:black; font-weight:500; text-align:left; margin-top:2rem;">Troisième instance :</h4>
                                <input class="form-control" type="file" name="solutions[]" style="border: 1px solid #0dcaf0;">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-info" type="submit" value="Envoyer" name="submit" style="border: 1px solid #0dcaf0;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php
include("footer.php");
?>
