<?php
include("config.php");
include("header.php");
include("navbar.php");
?>
    <!-- Masthead-->
    <header class="masthead" >
        <div class="container-fluid">
            <div class="row">
                <?php include("menuconcours.php");?>
                <div class="col-lg-8" id="main">
                    <div class="container">
                        <div class="box-concours" style="padding-top:2rem;">
                            <h3 style="color:black;">Leaderboard :</h3>
                            <p style="color:#2f2f2f; font-size:large;">Vous trouverez ci-dessous le classement en temps réel :</p>
                            <table class="box-tableau table table-hover text-white">
                                <thead>
                                <tr class="table-dark">
                                    <th scope="col">Type</th>
                                    <th scope="col">Column heading</th>
                                    <th scope="col">Column heading</th>
                                    <th scope="col">Column heading</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">Default</th>
                                    <td>Column content</td>
                                    <td>Column content</td>
                                    <td>Column content</td>
                                </tr>
                                <tr>
                                    <th scope="row">Default</th>
                                    <td>Column content</td>
                                    <td>Column content</td>
                                    <td>Column content</td>
                                </tr>
                                <tr>
                                    <th scope="row">Default</th>
                                    <td>Column content</td>
                                    <td>Column content</td>
                                    <td>Column content</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php
include("footer.php");
?>
<!--<script src="scripts/runtime-main.7f94bd84.js"></script>
<script src="scripts/2.1477e5ea.chunk.js"></script>
<script src="scripts/main.a424d41f.chunk.js"></script>-->