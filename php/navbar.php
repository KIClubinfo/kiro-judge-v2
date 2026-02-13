<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="index.php"><img src="assets/img/kiro.svg" alt="..." /></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars ms-1"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="teams.php">Équipes</a></li>
                <?php
                if (!isset($_SESSION['user'])) {
                    echo '
                    <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                    <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                ';
                } else {
                    echo '
                    <li class="nav-item"><a class="nav-link" href="concours.php">Concours</a></li>
                    <li class="nav-item"><a class="nav-link" href="deconnexion.php">Deconnexion</a></li>';
                }
                if (is_admin()) {
                    echo '
                    <li class="nav-item"><a class="nav-link" href="edit_info_admin.php">Admin</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
