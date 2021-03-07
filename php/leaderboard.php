<?php
include("config.php");
include("header.php");
include("navbar.php");
?>


<div id="leaderboard" style="min-height: 70%;"></div>

<!-- Load React. -->
<!-- Note: when deploying, replace "development.js" with "production.min.js". -->
<script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>

<!-- Load Babel (JSX), not suitable for production!
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script> -->

<script src="scripts/leaderboard.js"></script>

<?php
include("footer.php")
?>