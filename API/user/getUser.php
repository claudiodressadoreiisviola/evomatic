<?php
require './MODEL/user.php';

//'localhost/EVOMATIC/API/user/getUser.php/id'
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$user = new User;

json_encode($user->getUser($parts[5]));
