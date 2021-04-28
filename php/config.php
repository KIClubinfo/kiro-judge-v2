<?php
session_start();
include("popup.php");

##########NOT FOR PROD
error_reporting(E_ALL);
ini_set('display_errors', 'On');


$db_password = $_ENV["mysql_password"];

setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
$date_limite_inscription = date_create('2021-05-05');
$aujourdhui = new DateTime("now");

$phpFileUploadErrors = array(
    0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
);

$conn = new mysqli('db', 'kiro_user', $db_password, 'kiro');

if ($conn->connect_error) {
    die('Erreur lors de la connection à la base de donnée: ' . $conn->connect_error);
}


class user
{
    public $id;
    public $prenom;
    public $nom;
    public $id_team;
    public $mail;
    public $ecole;
    public $tel;
    public $admin;

    public function __construct($id)
    { // il faut vérfier en amont la donnée ici !
        global $conn;
        $this->id = $id; // id
        if ($req = $conn->prepare("SELECT * FROM users WHERE id=?")) { // requête préparée
            $req->bind_param("i", $id);
            $req->execute();
            $result = $req->get_result()->fetch_array(MYSQLI_ASSOC); // résulats de la requête
            $req->close();
            $this->prenom = htmlspecialchars($result['prenom']);
            $this->admin = $result['admin'];
            $this->nom = htmlspecialchars($result['nom']);
            $this->id_team = htmlspecialchars($result['id_team']);
            $this->mail = htmlspecialchars($result['mail']);
            $this->tel = htmlspecialchars($result['tel']);
            $this->ecole = htmlspecialchars($result['ecole']);
        } else {
            echo "Erreur lors de la création de la classe user";
        }
    }
}

class team
{
    public $id;
    public $score;
    public $classement;
    public $hub;
    public $nom;
    public $valide;
    public $numero_emplacement; // le numéro de sa table dans le hub
    public $type_equipe;

    public function __construct($id)
    { // il faut vérfier en amont la donnée ici !
        global $conn;
        $this->id = intval($id); // id
        if ($req = $conn->prepare("SELECT * FROM teams WHERE id=?")) { // requête préparée
            $req->bind_param("i", $this->id);
            $req->execute();
            $result = $req->get_result()->fetch_array(MYSQLI_ASSOC); // résulats de la requête
            $req->close();
            $this->score = htmlspecialchars($result['score']);
            $this->valide = $result['valide'];
            $this->classement = htmlspecialchars($result['classement']);
            $this->nom = htmlspecialchars($result['nom']);
            $this->hub = htmlspecialchars($result['hub']);
            $this->type_equipe = htmlspecialchars($result['type_equipe']);
            $this->numero_emplacement = htmlspecialchars($result['numero_emplacement']);
        } else {
            echo "Erreur lors de la création de la classe team";
        }
    }


    public function update_score()
    { // Pour mettre à jour le score de l'équipe
        global $conn;
        $score = 0;

        $results = $conn->query("SELECT distinct (instance_id) , max(score) score from solutions where team_id = {$this->id} and score >= 0 group by instance_id;");
        if (!empty($result)) {
            while ($row = $results->fetch_assoc()) {
                $score += $row["score"];
            }
        }


        if ($req = $conn->prepare("SELECT score FROM teams WHERE id=?")) { // requête préparée
            $req->bind_param("i", $this->id);
            $req->execute();
            $result = $req->get_result()->fetch_array(MYSQLI_ASSOC); // résulats de la requête
            $req->close();
            $score_ancien = $result['score'];
            if ($score > $score_ancien) { // si le score est meilleur
                if ($req3 = $conn->prepare("UPDATE teams SET score =? WHERE id=?")) {
                    $req3->bind_param("si", $score, $this->id);
                    $req3->execute();
                    $req3->close();
                    $this->score = $score;
                    $contents = file_get_contents("http://node_12:8080/refresh"); // mise à jour des classements

                } else {
                    echo "Erreur lors de la mise à jour du score dans le update de la base de donnée.";
                }
            }
        } else {
            echo "Erreur lors de la mise à jour du score";
        }
    }
}

function create_solution($team_id, $user_id, $instance_id)
{
    global $conn;
    $solution_id = -1;
    if ($request = $conn->prepare("INSERT INTO solutions (instance_id, team_id, user_id) VALUES (?, ?, ?)")) {
        $request->bind_param("iii", $instance_id, $team_id, $user_id);
        $request->execute();
        $solution_id = $request->insert_id;
        $request->close();
    }

    return $solution_id;
}

function update_solution($solution_id, $score, $error)
{
    global $conn;
    if ($request = $conn->prepare("UPDATE solutions SET errors = ?, score = ? WHERE solution_id = ?")) {
        $request->bind_param("sii", $error, $score, $solution_id);
        $request->execute();
        $request->close();
    }
}

function get_solution_filepath($instance_id, $team_id, $solution_id)
{
    return sprintf("/var/www/html/uploads/%i_%i_%i.json", $team_id, $instance_id, $solution_id);
}

function sanitize_string($str)
{
    global $conn;
    $sanitize = mysqli_real_escape_string($conn, $str);
    return $sanitize;
}

function is_admin()
{ // renvoie true si l'user est un admin
    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']->admin) {
            return True;
        }
    }
    return False;
}
