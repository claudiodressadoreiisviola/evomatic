<?php
// ERRATO -> API solo paninara
header("Content-type: application/json; charset=UTF-8");
require __DIR__ . '/../../../MODEL/allergen.php';

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$allergen = new Allergen;

$result = $allergen->getArchiveAllergen();

echo json_encode($result);
?>
