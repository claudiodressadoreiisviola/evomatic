<?php
// ERRATO -> API solo paninara

require __DIR__ . '/../../MODEL/ingredient.php';

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$ingredient = new Ingredient;

$result = $ingredient->getArchiveIngredient();

echo json_encode($result);
?>
