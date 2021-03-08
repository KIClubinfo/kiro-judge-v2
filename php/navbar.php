<div id="header-wrapper">
    <div id="header" class="container">
        <div id="menu">
            <ul class="menu">
                <li>
                    <img src="images/kiro.svg" width="100vw" id="logo">
                </li>
                <li><a href="index.php#concours" title="">Le concours</a></li>
                <li><a href="index.php#reglement" title="">Le règlement</a></li>
                <li><a href="index.php#old" title="">Anciens sujets</a></li>
                <li><a href="index.php#participer" title="">Participer</a></li>
                <li><a href="teams.php?id_team=11" title="">Équipes</a></li>
                <?php
                if (!isset($_SESSION['user'])) {
                    echo '
                <li><a href="inscription.php" title="">Inscription</a></li>
                <li><a href="connexion.php" title="">Connexion</a></li>
                ';
                } else {
                    echo '<li><a href="concours/index.php">Concours</a></li>';
                    echo '<li><a href="deconnexion.php" title="">Déconnexion</a></li>';
                }
                if (is_admin()) {
                    echo '<li><a href="edit_info_admin.php" title="">Admin</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>