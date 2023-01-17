<?php
// ERRATO -> API solo paninara

require __DIR__ . '/../../../MODEL/allergen.php';

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$allergen = new Allergen;

$result = $allergen->getAllergen($parts[5]);

echo json_encode($result);
?>