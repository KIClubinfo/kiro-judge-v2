<?php
// Route kiro.enpc.org/download.php
// Pour télécharger le sujet et les instances

include("config.php");

if (!isset($_SESSION["user"])){
  header('Location: index.php?not_connected');
  exit();
}

$dateconcours = new DateTime('2021-05-06 12:00:00');

include("date_protection.php");
protect_before($dateconcours,$datefinconcours);

    //telechargement du fichier
    if(isset($_GET['path'])){
        //Read the filename
        $filename = $_GET['path'];
        $regex='~^(/var/www/html/uploads/)(\d)*_(\d)*_(\d)*\.(json)$~';
        if (!preg_match($regex, $filename)){
            header('Location: index.php');
            exit();
        }
        $chaine=explode("/", $filename);
        $chaine=$chaine[5];
        $chaine=explode("_", $chaine);
        $id=$chaine[0];
        if($id!=$_SESSION['team']->id and !is_admin()){
            header('Location: index.php');
            exit();
        }
        //Check the file exists or not
        if(file_exists($filename)) {
            //Define header informations
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: 0");
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Content-Length: ' . filesize($filename));
            header('Pragma: public');
    
            //Clear system output buffer
            flush();
    
            //Read the size of the file
            readfile($filename);
    
            //Terminate from the script
            die();
        }
        else{
            include("header.php");
            include("navbar.php");
            echo '
            <header class="masthead">
                <div class="container" style="max-width:45rem;">
                    <div class="box">
                        <p class="byline">Le fichier n\'existe pas</p>
                        <p class="byline"><a href="myteam.php">Retour à mon équipe</a></p>
                    </div>
                </div>
            </header>
            ';
            include("footer.php");
        }
    }
    else{
        include("header.php");
        include("navbar.php");
        echo '
        <header class="masthead">
            <div class="container" style="max-width:45rem;">
                <div class="box">
                    <p class="byline">Le nom du fichier n\'est pas définit</p>
                    <p class="byline"><a href="myteam.php">Retour à mon équipe</a></p>
                </div>
            </div>
        </header>
        ';
        include("footer.php");
    } 
?>
