<?php
include("config.php");

if (!isset($_SESSION["user"])){
    header('Location: index.php?not_connected');
    exit();
}
  
include("date_protection.php");
protect_before($dateconcours);

include("header.php");
include("navbar.php");
include("instances.php");
?>

<?php
function Trouve_instance_min($champ, $teamid, $instance){ //Donne la ligne qui maximise le champ "champ" pour l'instance donnée en argument
    global $conn;
    if ($req = $conn->prepare("SELECT * FROM solutions  WHERE team_id=? AND instance_id={$instance} AND score >= 0 ORDER BY {$champ} ASC LIMIT 1")) {
        $req->bind_param('i', $teamid);
        $req->execute();
        $L_max = $req->get_result()->fetch_all(MYSQLI_ASSOC);
        $req->close();
        return($L_max);
    }
}

function Trouve_instance_max($champ, $teamid, $instance){ //Donne la ligne qui maximise le champ "champ" pour l'instance donnée en argument
    global $conn;
    if ($req = $conn->prepare("SELECT * FROM solutions  WHERE team_id=? AND instance_id={$instance} ORDER BY {$champ} DESC LIMIT 1")) {
        $req->bind_param('i', $teamid);
        $req->execute();
        $L_max = $req->get_result()->fetch_all(MYSQLI_ASSOC);
        $req->close();
        return($L_max);
    }
}
?>
<head>
    <meta charset="utf-8" />
    <script src="scripts/display_errors.js"></script>
</head>

<?php
//var_dump($_SESSION['team']);
//Définition des textes des champs
$score = 'score';
$date = 'upload_time';
$errors = 'errors';
$team = 'team';
$instance_id = 'instance_id';
$solution_id = 'solution_id';
$teamid = $_SESSION['team']->id;
$team = new team($teamid);

//var_dump($test);
$Best_Sol = array();
$Last_Sol = array();
for($i=0;$i<4;$i++) {
    $sc_max = Trouve_instance_min($score, $teamid, $i);
    if($sc_max != NULL){
        $Best_Sol[] = $sc_max[0];
    }
    $lst_sol = Trouve_instance_max($date, $teamid, $i);
    if($lst_sol != NULL){
        $Last_Sol[] = $lst_sol[0];
    }
}
//var_dump($Best_Sol);
//var_dump($Last_Sol);
global $conn;
if ($req = $conn->prepare("SELECT * FROM solutions  WHERE team_id=? ORDER BY $date DESC")) {
    $req->bind_param('i', $teamid);
    $req->execute();
    $All_Sol = $req->get_result()->fetch_all(MYSQLI_ASSOC);
    $req->close();
}
//var_dump($All_Sol)
?>

<header class="masthead" >
    <div class="container-fluid">
        <div class="row">
            <?php include("menuconcours.php");?>
            <div class="col-lg-8">
                <div class="container">
                    <div class="box-concours" style="padding-top:2rem;">
                        <h3 style="color:black;">Mon équipe : <?php echo($team->nom)?></h3>
                        <p style="color:#2f2f2f; font-size:large;">Vous trouverez ci-dessous le score et les instances soumises :</p>
                        <h4 style="color:black; font-weight:500; text-align:left; margin:2rem;">Score actuel : <?php echo(number_format($team->score))?></h4>
                        <h4 style="color:black; font-weight:500; text-align:left; margin:2rem;">Meilleures solutions :</h4>
                        <div class="table-responsive">
                            <table class="box-tableau table table-hover text-white">
                                <thead>
                                <tr class="table-dark">
                                    <th scope="col">Type d'instance</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Télécharger</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($Best_Sol as $sol){ if ($sol[$score] >= 0) {?>
                                <tr>
                                    <th scope="row">Instance <?php echo(INSTANCE_NAMES[$sol[$instance_id]])?></th>
                                    <td><?php echo(number_format($sol[$score]))?></td>
                                    <td><?php echo '<a href="download_instance.php?path=';
                                            echo get_solution_filepath($sol[$instance_id],$teamid,$sol[$solution_id]);
                                            echo '">Télécharger cette instance</a>';?></td>
                                </tr>
                                <?php }}?>
                                </tbody>
                            </table>
                        </div>
                        <h4 style="color:black; font-weight:500; text-align:left; margin:2rem;">Dernières solutions :</h4>
                        <div class="table-responsive">
                            <table class="box-tableau table table-hover text-white">
                                <thead>
                                <tr class="table-dark">
                                    <th scope="col">Type d'instance</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Télécharger</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($Last_Sol as $sol){?>
                                <tr>
                                    <th scope="row">Instance <?php echo(INSTANCE_NAMES[$sol[$instance_id]])?></th>
                                    <?php if ($sol[$score] >= 0) {?>
                                        <td><?php echo(number_format($sol[$score]))?> </td>
                                    <?php } else {?>
                                        <td><?php display_errors_button($sol[$errors])?> </td>
                                    <?php }?>
                                    <td><?php echo '<a href="download_instance.php?path=';
                                                echo get_solution_filepath($sol[$instance_id],$teamid,$sol[$solution_id]);
                                                echo '">Télécharger cette instance</a>';?></td>
                                </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <h4 style="color:black; font-weight:500; text-align:left; margin:2rem;">Historique des solutions :</h4>
                        <div class="table-responsive">
                            <table class="box-tableau table table-hover text-white">
                                <thead>
                                <tr class="table-dark">
                                    <th scope="col">Type d'instance</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Télécharger</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($All_Sol as $Sol){ ?>
                                <tr>
                                    <th scope="row">Instance <?php echo(INSTANCE_NAMES[$Sol[$instance_id]])?></th>
                                    <?php if ($Sol[$score] >= 0) { ?>
                                        <td><?php echo(number_format($Sol[$score]))?></td>
                                    <?php } else { ?>
                                        <td><?php display_errors_button($Sol[$errors])?></td>
                                    <?php } ?>
                                    <td><?php echo '<a href="download_instance.php?path=';
                                                    echo get_solution_filepath($Sol[$instance_id],$teamid,$Sol[$solution_id]);
                                                    echo '">Télécharger cette instance</a>';?></td>
                                </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php
include("footer.php")  
?>