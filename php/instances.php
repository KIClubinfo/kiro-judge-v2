<?php
const INSTANCE_NAMES = [
    "A",
    "NS",
    "PE",
    "PMP"
];

const INSTANCE_FILES = [
    "/var/www/html/solution_checker/instances/A.json",
    "/var/www/html/solution_checker/instances/NS.json",
    "/var/www/html/solution_checker/instances/PE.json",
    "/var/www/html/solution_checker/instances/PMP.json"
];

const INSTANCE_SCORES = [
    930000,
    21483000,
    18834240,
    17584000,
];

const WORST_SCORE = 58831240;

function display_errors_button($errors) {
    $errors_format = str_replace(PHP_EOL, "<br/>", $errors);
    $errors_format = str_replace("'", "\\'", $errors_format);
    ?>
    <button class="error-button" onclick="let x = <?php echo "'" . $errors_format . "'"; ?>;popUp(x)">Errors</button>
<?php
}