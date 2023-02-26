<?php
require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

// Se l'account da creare è di tipo utente
if (empty($data->type) && $data->type == 1)
{
    // Se i parametri nel corpo della POST sono sbagliati per un utente del tipo studente
    if (empty($data->name) || empty($data->surname) || empty($data->email) || empty($data->password) || empty($data->year) || empty($data->section) || empty($data->schoolYear) || empty($data->active)) {
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti per utente di tipo studente"]);
        die();
    }
    // Se i dati sono sufficienti per un utente di tipo studente
    else
    {
        // Prova a registrare lo studente
        try
        {
            $result = $user->registerStudent($data->name, $data->surname, $data->email, $data->password, $data->year, $data->section, $data->schoolYear, $data->type, $data->active);
        }
        // Se non riesce a registrare notifico l'errore al client
        catch (\Throwable $th)
        {
            http_response_code(500);
            echo json_encode(["message" => "Errore nella registrazione dell'utente"]);
            die();
        }
        // Se l'operazione riesce mando notifico il risultato al client
        echo json_decode($result);
    }
}
else if (empty($data->type) && $data->type != 1)
{
    // Se i parametri nel corpo della POST sono sbagliati per un utente del tipo backoffice
    if (empty($data->name) || empty($data->surname) || empty($data->email) || empty($data->password) || empty($data->active)) {
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti per utente di tipo backoffice"]);
        die();
    }
    // Se i dati sono sufficienti per un utente di tipo backoffice
    else
    {
        // Prova a registrare l'utente
        try
        {
            $result = $user->registerBackofficeUser($data->name, $data->surname, $data->email, $data->password, $data->type, $data->active);
        }
        // Se non riesce a registrare notifico l'errore al client
        catch (\Throwable $th)
        {
            http_response_code(500);
            echo json_encode(["message" => "Errore nella registrazione dell'utente"]);
            die();
        }
        // Se l'operazione riesce mando notifico il risultato al client
        echo json_decode($result);
        die();
    }
}
else
{
    http_response_code(400);
    echo json_encode(["message" => "Formato del JSON della richiesta sbagliato"]);
    die();
}
?>
