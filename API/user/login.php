<?php
require __DIR__ . '/../../MODEL/user.php';

//'localhost/evomatic/API/user/login.php/id/email/password'
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new User;

if ($user->login($parts[5], $parts[6], $parts[7]) == 1)
    echo json_encode([
        "message" => "Logged in successfully"
    ]);
