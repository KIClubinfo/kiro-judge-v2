<?php
include("config.php");

if (!isset($_SESSION["user"])){
    header('Location: index.php?not_connected');
    exit();
}
  
include("date_protection.php");
$dateconcours = new DateTime('2021-05-06 11:30:00');
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
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styletest.css" />
</head>

<div class="content" style="padding-top: 15vh;">

    <div class="content limiter"> 
        <?php
        include("menuconcours.php");
        ?>
        <section class="concours">
            <div class="title">
                <h2 style="margin-top:20px; text-align:center; font-size: 2.7em; margin-bottom:30px;">EQUIPE <?php echo($team->nom)?></h2>
            </div>
            <div class="title">
                <span class="byline"> Score actuel:</span>
                <h2 style="margin-top:20px; text-align:center; font-size: 3.7em; margin-bottom:30px;"><?php echo(number_format($team->score))?></h2>
            </div>
            <div class="title">
                <span class="byline"> Meilleures solutions:</span>
            </div>
            <div class="wrap-table100" style="text-align: center">
                <div class="table">
                    <div class="row2 header">
                        <?php foreach($Best_Sol as $sol){ if ($sol[$score] >= 0) {?>
                            <div class="cell" align="center">Instance <?php echo(INSTANCE_NAMES[$sol[$instance_id]])?></div>
                        <?php }}?>
                    </div>
                    <div class="row2">
                        <?php foreach($Best_Sol as $sol){ if ($sol[$score] >= 0) {?>
                        <div class="cell" style="margin: auto" ><?php echo(number_format($sol[$score]))?> </div>
                        <?php }}?>
                    </div>
                    <div class="row2">
                        <?php foreach($Best_Sol as $sol){ if ($sol[$score] >= 0) {?>
                            <div class="cell"><?php echo '<a href="download_instance.php?path=';
                                            echo get_solution_filepath($sol[$instance_id],$teamid,$sol[$solution_id]);
                                            echo '">Télécharger cette instance</a>';?></div>
                        <?php }}?>
                    </div>
                </div>
            </div>
            <br/>
            <div class="title">
                <span class="byline"> Dernières solutions:</span>
            </div>
            <div class="wrap-table100" style="text-align: center; align-content: center">
                <div class="table">
                    <div class="row2 header">
                        <?php foreach($Last_Sol as $sol){?>
                            <div class="cell" align="center">Instance <?php echo(INSTANCE_NAMES[$sol[$instance_id]])?></div>
                        <?php }?>
                    </div>
                    <div class="row2">
                        <?php foreach($Last_Sol as $sol){
                            if ($sol[$score] >= 0) {?>
                            <div class="cell"><?php echo(number_format($sol[$score]))?> </div>
                        <?php } else {?>
                                <div class="cell" style="max-width: 20em"><?php display_errors_button($sol[$errors])?> </div>

                            <?php }}?>
                    </div>
                    <div class="row2">
                        <?php foreach($Last_Sol as $sol){ ?>
                            <div class="cell"><?php echo '<a href="download_instance.php?path=';
                                            echo get_solution_filepath($sol[$instance_id],$teamid,$sol[$solution_id]);
                                            echo '">Télécharger cette instance</a>';?></div>
                        <?php }?>
                    </div>
                </div>
            </div>
            <br />
            <div class="title">
                <span class="byline"> Historique des solutions:</span>
            </div>
<?php
if (sizeof($All_Sol) > 0) {
    ?>
            <div class="wrap-table100" style="text-align: center">
                <div class="table">

                    <div class="row2 header">
                        <div class="cell" align="center">Instance</div>
                        <div class="cell" align="center">Score/Erreurs</div>
                        <div class="cell" align="center">Chemin</div>
                    </div>

                    <?php foreach ($All_Sol as $Sol){ ?>
                        <div class="row2">
                            <div class="cell" align="center" style ="vertical-align: middle"><?php echo(INSTANCE_NAMES[$Sol[$instance_id]])?></div>
                            <?php
                            if ($Sol[$score] >= 0) {
                            ?>
                                <div class="cell" align="center" style ="vertical-align: middle"><?php echo(number_format($Sol[$score]))?></div>
                                <?php
                            } else {
                                ?>
                                <div class="cell" style="width: 35em"><?php display_errors_button($Sol[$errors])?></div>

                                <?php
                            }
                            ?>
                            <div class="cell" style ="vertical-align: middle"><?php echo '<a href="download_instance.php?path=';
                                            echo get_solution_filepath($Sol[$instance_id],$teamid,$Sol[$solution_id]);
                                            echo '">Télécharger cette instance</a>';?>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
    <?php
}
    ?>
        </section>
    </div>
</div>
</div>

<?php
include("footer.php")


    
?>