<?php
const INSTANCE_NAMES = [
    "KIRO-tiny",
    "KIRO-small",
    "KIRO-medium",
];

const INSTANCE_PATHES = [
    "/var/www/html/solution_checker/instances/tiny",
    "/var/www/html/solution_checker/instances/small",
    "/var/www/html/solution_checker/instances/medium",
    
];

const INSTANCE_SCORES = [
    1000000,
    5000000,
    1000000000,
    5000000000,
    300000000000
];

const WORST_SCORE = 306006000000;

function display_errors_button($errors) {
    $errors_format = str_replace(PHP_EOL, "<br/>", $errors);
    $errors_format = str_replace("'", "\\'", $errors_format);
    ?>
    <button class="error-button" onclick="let x = <?php echo "'" . $errors_format . "'"; ?>;popUp(x)">Errors</button>
<?php
}
