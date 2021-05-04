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
protect_before($dateconcours);

    //telechargement du fichier
    if(isset($_GET['path'])){
        //Read the filename
        $filename = $_GET['path'];
        //Check the file exists or not
        $chaine=explode("/", $filename);
        $id=$chaine[4];
        if($id!=$_SESSION['team']->id){
            header('Location: index.php');
            exit();
        }
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
            <div class="content" style="margin-top: 15vh">
                <div class="container containergrey">
                    <p style="text-align: center;">Le fichier n\'existe pas.</p>
                </div>
            </div>
            ';
            include("footer.php");
        }
    }
    else{
        include("header.php");
        include("navbar.php");
        echo '
        <div class="content" style="margin-top: 15vh">
            <div class="container containergrey">
                <p style="text-align: center;">Le nom du fichier n\'est pas défini</p>
            </div>
        </div>
        ';
        include("footer.php");
    } 
?>