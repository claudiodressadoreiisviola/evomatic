<?php

require __DIR__ . '/../../../MODEL/ingredient.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));


if (empty($data->name) || empty($data->description) || empty($data->price) || empty($data->quantity)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$ingredient = new Ingredient;

$result = $ingredient->createIngredient($data->name, $data->description, $data->price, $data->quantity);

echo json_encode($result);
