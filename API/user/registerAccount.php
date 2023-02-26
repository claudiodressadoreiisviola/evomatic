<?php
require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

$user = new User;

// Se i dati sono sufficienti per un utente di base
if (empty($data->type) || empty($data->name) || empty($data->surname) || empty($data->email) || empty($data->password) || empty($data->active))
{
    // Se l'utente è di tipo studente
    if ($data->type == 1)
    {
        if (empty($data->year) || empty($data->section) || empty($data->schoolYear))
        {
            http_response_code(400);
            return json_encode(["message" => "Dati insufficienti per utente di tipo studente"]);
            die();
        }
        // Se i dati sono sufficienti
        else
        {
            // Provo a registrare lo studente
            try
            {
                $result = $user->registerStudent($data->name, $data->surname, $data->email, $data->password, $data->year, $data->section, $data->schoolYear, $data->type, $data->active);
            }
            // Se non ci riesco notifico il client dell'errore
            catch (\Throwable $th)
            {
                http_response_code(500);
                return json_encode(["message" => "Errore durante la registrazione dello studente"]);
                die();
            }
            // Se ci riesco ritorno il messaggio inviato dalla funzione registerStudent()
            return json_encode($result);
            die();
        }
    }
    // Se l'utente non è di tipo studente lo dovrò registrare come backoffice
    else
    {
        // Provo a registrare l'utente backoffice
        try
        {
            $result = $user->registerBackofficeUser($data->name, $data->surname, $data->email, $data->password, $data->type, $data->active);
        }
        // Se non ci riesco notifico il client dell'errore
        catch (\Throwable $th)
        {
            http_response_code(500);
            return json_encode(["message" => "Errore durante la registrazione dell'utente"]);
            die();
        }
        // Se ci riesco ritorno il messaggio inviato dalla funzione registerBackofficeUser()
        return json_encode($result);
        die();
    }

}
// Se i dati do base non sono sufficienti
else
{
    http_response_code(400);
    return json_encode(["message" => "Dati insufficienti o non corretti per la creazione di un utente"]);
    die();
}
?>
