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
            foreach ($_FILES["solutions"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $user = new user($_SESSION["user"]->id);
                    var_dump($_SESSION["user"]);
                    var_dump($user);
                    $team = new team($_SESSION["user"]->id_team);
                    $tmp_name = $_FILES["solutions"]["tmp_name"][$key];
                    // basename() peut empêcher les attaques "filesystem traversal";
                    // une autre validation/néttoyage du nom de fichier peux être appropriée
                    $name = basename($_FILES["solutions"]["name"][$key]);
                    move_uploaded_file($tmp_name, "/var/www/html/uploads/$name" . "_" . $team->nom);
                    $command = "python3 /var/www/html/solution_checker/main.py -s %s -i %s";

                    $instances = [
                        "/var/www/html/solution_checker/instances/Asmall_fixed.json",
                        "/var/www/html/solution_checker/instances/inst_A_fixed.json",
                        "/var/www/html/solution_checker/instances/inst_NS_fixed.json",
                        "/var/www/html/solution_checker/instances/inst_PMP_fixed.json"
                    ];

                    $command_format = sprintf($command, $tmp_name, $instances[$key]);
                    $results = [];
                    var_dump($command_format);
                    exec($command_format, $results);

                    $score = intval($results[0]);
                    $is_better = false;

                    if ($score >= $team->score) {
                        // On enregistre le score dans la BDD.
                        $is_better = true;
                        global $conn;
                        if ($request = $conn->prepare("UPDATE teams SET score = ? WHERE id = ?")) {
                            $request.bind_param("ii", $score, $team->id);
                            $request.execute();
                            $request.close();
                        }

                    }
                } else {
                    echo $phpFileUploadErrors[$error] ;
                    echo "<br />";
                    error_log("File not found.");
                }
            }
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
