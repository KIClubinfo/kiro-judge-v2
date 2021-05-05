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

<div class="content" style="margin-top: 15vh">

  <?php
  include("menuconcours.php");

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

        echo '<section class="concours">';
        echo '<div class="title" style="margin-top:20px; text-align:center;"><h2 style="font-size: 2.7em; margin-bottom:30px;">Résultat de l\'Upload</h2></div>';
        echo '<div class="title" style="text-align:left;">';
        echo '<span class="byline" style="margin-left: 210px; color:black;">Vous trouverez ci-dessous le résultat de l\'upload :</span>';
        echo '</div>';
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
            echo "<span style='font-size: 1.7em;margin-left:350px;'>".INSTANCE_NAMES[$key]." : </span>";
            if ($score < 0) {
              display_errors_button($errors_string);
            } else {
                $color = ($old_score < $score) ?"green" : "red";
              echo "<span style='color: {$color}; font-size: 1.7em;'>" . $score . "<span />";
            }
    ?>
  </div>
  <?php


          } else {
  ?>
  <div>
    <?php
            echo "<span style='font-size: 1.7em;margin-left:350px;'>".INSTANCE_NAMES[$key]."</span>";
            echo "<span style='font-size: 1.7em;'> : Pas de solution fournie.</span>";
    ?>
  </div>

<?php
    echo '<br />';
          }
        }
        echo '<div />';
        $team->update_score();
        http_response_code(200);
        echo '<div class="title" style="text-align:left;">';
        echo '<span class="byline"><br/> <span style="color:red;">Rouge</span> : La solution est moins bonne que la précédente</span>';
        echo '<span class="byline"><br/> <span style="color:green;">Vert</span> : La solution est meilleure que la précédente</span>';
        echo '</div>';
        echo "</section>";
      } else {
        http_response_code(400);
      }
    } else {
      http_response_code(401);
    }
      ?>

      <section class="concours">
          <div class="title" style="margin-top:20px; text-align:center;">
              <h2 style="font-size: 2.7em; margin-bottom:30px; text-align:center;">Upload des solutions</h2>
              <form action="" method="post" enctype="multipart/form-data">
                  <span class="byline">Vous pouvez envoyer un ou plusieurs fichiers a la fois :</span>
                  <span class="byline" style="color:black; font-size:x-large;">
          <br /><br />
            A :
          <input type="file" name="solutions[]" style="margin-left:64px"/>
          <br /><br />
            NS :
          <input type="file" name="solutions[]" style="margin-left:48px"/>
          <br /><br />
            PE :
          <input type="file" name="solutions[]" style="margin-left:53px"/>
          <br /><br />
            PMP :
          <input type="file" name="solutions[]" style="margin-left:30px"/>
          <br /><br />
          <input type="submit" value="Envoyer" name="submit" />
        </span>
              </form>
          </div>
      </section>

      <?php
  } else {

?>

  <section class="concours">
    <div class="title" style="margin-top:20px; text-align:center;">
      <h2 style="font-size: 2.7em; margin-bottom:30px; text-align:center;">Upload des solutions</h2>
      <form action="" method="post" enctype="multipart/form-data">
        <span class="byline">Vous pouvez envoyer un ou plusieurs fichiers a la fois :</span>
        <span class="byline" style="color:black; font-size:x-large;">
          <br /><br />
            A :
          <input type="file" name="solutions[]" style="margin-left:64px"/>
          <br /><br />
            NS :
          <input type="file" name="solutions[]" style="margin-left:48px"/>
          <br /><br />
            PE :
          <input type="file" name="solutions[]" style="margin-left:53px"/>
          <br /><br />
            PMP :
          <input type="file" name="solutions[]" style="margin-left:30px"/>
          <br /><br />
          <input type="submit" value="Envoyer" name="submit" />
        </span>
      </form>
    </div>
  </section>

<?php
  }
?>
</div>
<?php


include("footer.php");
?>
