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
</head>

<div class="content" style="margin-top: 15vh">

  <?php
  include("menuconcours.php");

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user'])) {
      if (isset($_POST['submit'])) {
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

        echo '<section class="concours">';
        foreach ($_FILES["solutions"]["error"] as $key => $error) {
          if ($error == UPLOAD_ERR_OK) {

            $solution_id = create_solution($team->id, $user->id, $key);
            $tmp_name = $_FILES["solutions"]["tmp_name"][$key];
            $file_path = get_solution_filepath($key, $team->id, $solution_id);


            move_uploaded_file($tmp_name, $file_path);
            $command = 'python3 /var/www/html/solution_checker/main.py -s "%s" -i "%s"';

            $instances = [
              "/var/www/html/solution_checker/instances/Asmall_fixed.json",
              "/var/www/html/solution_checker/instances/inst_A_fixed.json",
              "/var/www/html/solution_checker/instances/inst_NS_fixed.json",
              "/var/www/html/solution_checker/instances/inst_PMP_fixed.json"
            ];


            $command_format = sprintf($command, $file_path, $instances[$key]);
            $results = [];
            exec($command_format, $results);

            $score = intval($results[0]);
            $errors_string = "";
            for ($i = 1; $i < sizeof($results); $i++) {
              $errors_string .= $results[$i] . PHP_EOL;
            }

            update_solution($solution_id, $score, $errors_string);

            $instances_name = [
              "A",
              "NS",
              "NP",
              "PMP"
            ];

  ?>
  <div>
    <?php
            echo $instances_name[$key];
            echo " ";
            if ($score < 0) {
              echo $errors_string;
            } else {
              echo $score;
            }
    ?>
  </div>
  <?php


          } else {
  ?>
  <div>
    <?php
            echo $instances_name[$key];
            echo " File not uploaded";
    ?>
  </div>
<?php

          }
        }
        $team->update_score();
        http_response_code(200);
        echo "</section>";
      } else {
        http_response_code(400);
      }
    } else {
      http_response_code(401);
    }
  } else {

?>

<section class="concours">
  <form action="" method="post" enctype="multipart/form-data">
    <p>Solutions (Vous pouvez envoyer un ou plusieurs fichiers a la fois):
      <br />
      <input type="file" name="solutions[]" />
      <br />
      <input type="file" name="solutions[]" />
      <br />
      <input type="file" name="solutions[]" />
      <br />
      <input type="file" name="solutions[]" />
      <br />
      <input type="submit" value="submit" name="submit" />
    </p>
  </form>
</section>

<?php
  }
?>
</div>
<?php


include("footer.php");
?>
