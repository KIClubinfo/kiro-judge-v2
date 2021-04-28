<?php
include("config.php");


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
//                    $is_better = false;
//
//                    if ($score >= $team->score) {
//                        // On enregistre le score dans la BDD.
//                        $team->update_score($score);
////                        $is_better = true;
////                        global $conn;
////                        if ($request = $conn->prepare("UPDATE teams SET score = ? WHERE id = ?")) {
////                            $request.bind_param("ii", $score, $team->id);
////                            $request.execute();
////                            $request.close();
////                        }
//                        echo "New score: ";
//                        echo $score;
//
//                    }
                } else {
                    echo $phpFileUploadErrors[$error] ;
                    echo "<br />";
                    error_log("File not found.");
                }
            }
            $team->update_score();
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    } else {
        http_response_code(401);
    }
} else {
    // On revoie un formulaire d'envoi de fichier :
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <p>Solutions:
            <input type="file" name="solutions[]" />
            <input type="file" name="solutions[]" />
            <input type="file" name="solutions[]" />
            <input type="file" name="solutions[]" />
            <input type="submit" value="submit" name="submit"/>
        </p>
    </form>
    <?php
}
