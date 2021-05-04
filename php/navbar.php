<nav class="navbar navbar-expand-lg perso">
  <a class="navbar-brand logomargin" href="index.php"><img src="images/kiro.svg" width="80vw" id="logo"></a>

  <div class="navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Accueil <span class="sr-only"></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="teams.php">Équipes</a>
      </li>
      <?php
            if (!isset($_SESSION['user'])) {
                echo '
                <li class="nav-item">
                <a class="nav-link" href="inscription.php">Inscription <span class="sr-only"></span></a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="connexion.php">Connexion <span class="sr-only"></span></a>
                </li>
            ';
            } else {
                echo '
                <li class="nav-item">
                <a class="nav-link" href="concourstest.php">Concours <span class="sr-only"></span></a>
                </li>';
                echo '
                <li class="nav-item">
                <a class="nav-link" href="deconnexion.php">Déconnexion <span class="sr-only"></span></a>
                </li>';
            }
            if (is_admin()) {
                echo '
                <li class="nav-item">
                <a class="nav-link" href="edit_info_admin.php">Admin <span class="sr-only"></span></a>
                </li>';
            }
            ?>
    </ul>
  </div>
</nav>
