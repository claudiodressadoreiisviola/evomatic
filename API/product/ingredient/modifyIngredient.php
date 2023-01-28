<?php
require __DIR__ . '/../../../MODEL/ingredient.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->description)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$ingredient = new Ingredient;

$result = $ingredient->modifyIngredient($data->id, $data->name, $data->description);

echo json_encode($result);