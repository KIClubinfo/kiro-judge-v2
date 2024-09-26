<?php
const INSTANCE_NAMES = [
    "KIRO-toy",
    "KIRO-small",
    "KIRO-medium",
    "KIRO-large",
    "KIRO-huge"
];

const INSTANCE_FILES = [
    "/var/www/html/solution_checker/instances/input/toy.json",
    "/var/www/html/solution_checker/instances/input/small.json",
    "/var/www/html/solution_checker/instances/input/medium.json",
    "/var/www/html/solution_checker/instances/input/large.json",
    "/var/www/html/solution_checker/instances/input/huge.json",
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
