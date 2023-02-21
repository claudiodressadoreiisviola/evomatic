<?php
require __DIR__ . '/../../../MODEL/tag.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if(empty($data->name)){
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$tag = new Tag;

$result = $tag->createTag($data->name);

echo $result;
?>