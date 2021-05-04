<?php
const INSTANCE_NAMES = [
    "A",
    "NS",
    "PE",
    "PMP"
];

const INSTANCE_FILES = [
    "/var/www/html/solution_checker/instances/instance_A.json",
    "/var/www/html/solution_checker/instances/instance_NS.json",
    "/var/www/html/solution_checker/instances/instance_PE.json",
    "/var/www/html/solution_checker/instances/instance_PMP.json"
];

const INSTANCE_SCORES = [
    930000,
    21483000,
    18834240,
    17584000,
];

function display_errors_button($errors) {
    ?>
    <button onclick="let x = <?php echo "'Hello'"; ?>;popUp(x)">Click me</button>
<?php
}