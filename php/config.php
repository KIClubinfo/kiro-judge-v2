<?php
session_start();

$db_password = $_ENV["mysql_password"];

setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
$date_limite_inscription = strtotime("11.4.2020");

$conn = new mysqli('db', 'kiro_user', $db_password,'kiro');

if($conn->connect_error){
  die('Erreur lors de la connection à la base de donnée: ' .$conn->connect_error);
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
  public function __construct($id){ //il faut vérfier en amont la donnée ici !
    global $conn;
    $this->id = $id; // id
    if ($req = $conn->prepare("SELECT * FROM users WHERE id=?")) { //requete préparée
        $req->bind_param("i", $id);
        $req->execute();
        $result = $req->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
        $req->close();
        $this->prenom = htmlspecialchars($result['prenom']);
        $this->nom = htmlspecialchars($result['nom']);
        $this->id_team = htmlspecialchars($result['id_team']);
        $this->mail = htmlspecialchars($result['mail']);
        $this->tel = htmlspecialchars($result['tel']);
        $this->ecole = htmlspecialchars($result['ecole']);
    }
    else{
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
  public $numero_emplacement; //le numéro de sa table dans le hub
  public function __construct($id){ //il faut vérfier en amont la donnée ici !
    global $conn;
    $this->id = $id; // id
    if ($req = $conn->prepare("SELECT * FROM teams WHERE id=?")) { //requete préparée
        $req->bind_param("i", $id);
        $req->execute();
        $result = $req->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
        $req->close();
        $this->score = htmlspecialchars($result['score']);
        $this->classement = htmlspecialchars($result['classement']);
        $this->hub = htmlspecialchars($result['hub']);
        $this->$numero_emplacement = htmlspecialchars($result['$numero_emplacement']);
    }
    else{
      echo "Erreur lors de la création de la classe team";
    }
  }
  public function update_score($score){ //Pour mettre à jour le score de l'équipe
      global $conn;
      if ($req = $conn->prepare("SELECT score FROM teams WHERE id=?")) { //requete préparée
          $req->bind_param("i", $this->id);
          $req->execute();
          $result = $req->get_result()->fetch_array(MYSQLI_ASSOC); //resulats de la requête
          $req->close();
          $score_ancien = $result['score'];
          if ($score>$score_ancien){ //si le score est meilleur
            if ($req3 = $conn->prepare("UPDATE teams SET score =? WHERE id=?")) {
              $req3->bind_param("si", $score,$this->id);
              $req3->execute();
              $req3->close();
              $this->score = $score;
            }
            else{
            $erreur1 = "Erreur lors de la mise à jour du score dans le update de la base de donnée.";
            }
          }
      }
      else{
        echo "Erreur lors de la mise à jour du score";
      }
  }
}

function sanitize_string($str) {
  global $conn;
	$sanitize = mysqli_real_escape_string($conn,$str);
	return $sanitize;
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
      <!-- Analyse du traffic-->

  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172250080-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-172250080-1');
  </script>

    <title>Kiro - École des Ponts</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="images/logo_kiro.png" />
    <link rel="shortcut icon" type="image/x-icon" href="images/logo_kiro.png" />
    <meta name="description" content="Le Kiro est un concours ouvert aux étudiants intéressés par la résolution effective d'un problème de recherche opérationnelle. Il est sous le format d'un hackaton de 6h.">
    <meta name="keywords" content="ponts et chaussées, ponts, chaussees, enpc, ponts parisTech, enseignement, ingénieur, bde, partenaires, clubs, activité, WEI, admissibles, alpha, plaquette, plaquette alpha, contact, cutlure, sport, ingénieur, finance, transport, élèves-ingénieurs, ingénieurs, conseil, prépa, classe prépa, cauchy, science, MMC, mecanique, finance, recherche opérationnelle, recherche, opérationnelle, renault, industrie, génie industriel, programmation, python, c++, code, hackaton" />
    <meta name="author" content="Antonin Parrot, Jean-Loup Raymond, Pierre Glandon">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link href="scripts/style.css" rel="stylesheet" type="text/css" media="all">
</head>
<body>
