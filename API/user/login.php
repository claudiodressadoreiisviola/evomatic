<?php
require '../../DB/getUser.php';

//'localhost/EVOMATIC/API/user/login.php/id/email/password'
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new user;

if (($result = $user->login($parts[5], $parts[6], $parts[7]) == 0))
    echo "login effettuato";
