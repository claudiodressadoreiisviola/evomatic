<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

$user = new User();

// Se i dati sono insufficienti per un utente di base
if (empty($data->type) || empty($data->name) || empty($data->surname) || empty($data->email) || empty($data->password) || empty($data->active))
{
    http_response_code(400);
    echo json_encode(["message" => "Dati insufficienti o non corretti per la creazione di un utente"]);
    die();
}
// Se i dati di base sono sufficienti
else
{
    // Se l'utente è di tipo studente
    if ($data->type == 1)
    {
        // Se i dati per un utente studente sono insufficienti
        if (empty($data->year) || empty($data->section) || empty($data->schoolYear))
        {
            http_response_code(400);
            echo json_encode(["message" => "Dati insufficienti per utente di tipo studente"]);
            die();
        }
        // Se i dati sono sufficienti
        else
        {
            // Provo a registrare lo studente
            $result = $user->registerStudent($data->name, $data->surname, $data->email, $data->password, $data->year, $data->section, $data->schoolYear, $data->type, $data->active);

            // Se ci riesco ritorno il messaggio inviato dalla funzione registerStudent()
            echo json_encode($result);
            die();
        }
    }
    // Se l'utente non è di tipo studente lo dovrò registrare come backoffice
    else
    {
        // Provo a registrare l'utente backoffice
        $result = $user->registerBackofficeUser($data->name, $data->surname, $data->email, $data->password, $data->type, $data->active);

        // Se ci riesco ritorno il messaggio inviato dalla funzione registerBackofficeUser()
        echo json_encode($result);
        die();
    }
}
?>
