<?php
require __DIR__ . '/../../MODEL/user.php';

//'localhost/EVOMATIC/API/user/changePassword.php/id/email/password/newPassword'
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new User;

$result = $user->changePassword($parts[5], $parts[6], $parts[7], $parts[8]);

echo json_encode([
    "message" => "Password changed successfully"
]);
