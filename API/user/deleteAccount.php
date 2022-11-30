<?php
require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new User;

echo json_encode($user->deleteUser($parts[5]));
