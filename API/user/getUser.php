<?php
require __DIR__ . '/../../MODEL/user.php';

//'localhost/evomatic/API/user/getUser.php/id'
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new User;

$result = $user->getUser($parts[5]);

echo json_encode($result);
