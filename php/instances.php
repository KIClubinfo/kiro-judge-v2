<?php
const INSTANCE_NAMES = [
    "KIRO-01",
    "KIRO-02",
    "KIRO-03",
    "KIRO-04",
    "KIRO-05",
    "KIRO-06",
    "KIRO-07",
    "KIRO-08",
    "KIRO-09",
    "KIRO-10",
];

const INSTANCE_PATHES = [
    ["/var/www/html/solution_checker/instances", "instance_01"],
    ["/var/www/html/solution_checker/instances", "instance_02"],
    ["/var/www/html/solution_checker/instances", "instance_03"],
    ["/var/www/html/solution_checker/instances", "instance_04"],
    ["/var/www/html/solution_checker/instances", "instance_05"],
    ["/var/www/html/solution_checker/instances", "instance_06"],
    ["/var/www/html/solution_checker/instances", "instance_07"],
    ["/var/www/html/solution_checker/instances", "instance_08"],
    ["/var/www/html/solution_checker/instances", "instance_09"],
    ["/var/www/html/solution_checker/instances", "instance_10"],
    
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
