<?php

require __DIR__ . '/../../MODEL/user.php';

//'localhost/EVOMATIC/API/user/deleteAccount.php/id'
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new User;

echo json_encode($user->deleteUser($parts[5]));
