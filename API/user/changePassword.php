<?php
require '../../DB/getUser.php';

//'localhost/EVOMATIC/API/user/changePassword.php/id/email/password'
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new user;

$result = $user->changePassword($parts[5], $parts[6], $parts[7], $parts[8]);

echo "password cambiata correttamente";
