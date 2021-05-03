<?php
include("config.php");
include("header.php");
include("navbar.php");
?>
<?php
global $conn;
//var_dump($_SESSION['team']);
$teamid = $_SESSION['team']->id;
//var_dump($test);
$TotalScore = 0;
$Best_Sol = array();
$Last_Sol = array();
if ($req = $conn->prepare("SELECT * FROM solutions WHERE team_id=$teamid AND instance_id=? ORDER BY ? DESC")) {
    for($i=0; $i<4; $i++) {
        $score = 'score';
        $req-> bind_param('is',$i, $score);
        $req->execute();
        $Best_Sol[] = $req->get_result()->fetch_array(MYSQLI_ASSOC);

        $TotalScore += $Best_Sol[$i]['score'];

        $date = 'upload_time';
        $req-> bind_param('is',$i, $date);
        $req->execute();
        $Last_Sol[] = $req->get_result()->fetch_array(MYSQLI_ASSOC);
    }
    $req->close();
}
var_dump($Best_Sol);
var_dump($Last_Sol);
var_dump($TotalScore)
?>

<div class="content limiter" style="min-height: 35%">
    <div class="container">
        <p> TEst du test testé </p>
        <div class="wrap-table100">
            <div class="row2 header">
                <div class="cell">Instance 0
                </div>
                <div class="cell">Instance 1
                </div>
                <div class="cell">Instance 2
                </div>
                <div class="cell">Instance 3
                </div>
            </div>
            <div class="row2 header">
                <div class="cell">score
                </div>
                <div class="cell">erreurs
                </div>
                <div class="cell">score
                </div>
                <div class="cell">erreurs
                </div>
                <div class="cell">score
                </div>
                <div class="cell">erreurs
                </div>
                <div class="cell">score
                </div>
                <div class="cell">erreurs
                </div>
            </div>
            <div class="row2">
                <div class="cell"><?php echo($Best_Sol[0])?>
                </div>
                <div class="cell"><?php echo($Best_Sol[1])?>
                </div>
                <div class="cell"><?php echo($Best_Sol[2])?>
                </div>
                <div class="cell"><?php echo($Best_Sol[3])?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include("footer.php")
?>
