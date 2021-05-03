<?php
include("config.php");
include("header.php");
include("navbar.php");
?>

<?php
function Trouve_instance_max($champ, $teamid){
    global $conn;
    if ($req = $conn->prepare("SELECT MAX({$champ}), instance_id FROM solutions WHERE team_id=? GROUP BY instance_id")) {
        $req->bind_param('i', $teamid);
        $req->execute();
        $L_max = $req->get_result()->fetch_all(MYSQLI_ASSOC);
        $req->close();
        return($L_max);
    }
}
?>


<?php
//var_dump($_SESSION['team']);
$teamid = $_SESSION['team']->id;
//var_dump($test);
$TotalScore = 0;
$Best_Sol = array();
$Last_Sol = array();
$Best_Sol = Trouve_instance_max('score',$teamid);
$Last_Sol = Trouve_instance_max('upload_time', $teamid);
foreach ($Best_Sol as $best_instance){
    $TotalScore += $best_instance['MAX(score)'];
}
var_dump($Best_Sol);
var_dump($Last_Sol);
var_dump($TotalScore)
?>




<?php
include("footer.php")
?>
