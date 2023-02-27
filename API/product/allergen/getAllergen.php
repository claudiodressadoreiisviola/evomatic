<?php
// ERRATO -> API solo paninara
require __DIR__ . '/../../../MODEL/allergen.php';
header("Content-type: application/json; charset=UTF-8");
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$allergen = new Allergen;

$result = $allergen->getAllergen($parts[6]);

echo json_encode($result);
?>
