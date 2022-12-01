<?php
require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (count($parts) == 5) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
}

$user = new User;

$result = $user->getUser($parts[5]);

echo json_encode($result);
