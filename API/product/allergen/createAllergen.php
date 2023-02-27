<?php
// ERRATO -> API solo paninara

require __DIR__ . '/../../../MODEL/allergen.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$allergen = new Allergen;

$result = $allergen->createAllergen($data->name);

echo json_encode($result);
?>
