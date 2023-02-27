<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$user = new User();

$id = $user->login($data->email, $data->password);

if ($id === false) {
    http_response_code(400);
    echo json_encode(["id" => "-1", "message" => "Nessun utente trovato con le credenziali fornite"]);
}
else
{
    echo json_encode($id);
}
