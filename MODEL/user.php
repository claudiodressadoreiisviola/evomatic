<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class User
{
    private Connect $db;
    private PDO $conn;

    public function __construct()
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getUser($id)
    {
        $sql = "SELECT name, surname, email
            FROM user
            WHERE id = :id AND active = 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id)
    {
        $user = $this->getUser($id);

        if ($user == null)
            return false;

        $sql = "UPDATE user
        SET active = 0
        WHERE  id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function resetPassword($id)
    {
        $date = date("d:m:Y h:i:s");

        // Generazione della password randomica
        $password = bin2hex(openssl_random_pseudo_bytes(4));

        // Aggiunta alla tabella reset l'utente
        $sql = "INSERT INTO reset
            (user, password, requested, expires, completed)
            VALUES (:user, :password, :requested, :expires, :completed)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':requested', $date, PDO::PARAM_STR);
        $stmt->bindValue(':expires', date("d:m:Y h:i:s", strtotime($date . '+ 5 Days')), PDO::PARAM_STR);
        $stmt->bindValue(':completed', FALSE, PDO::PARAM_INT);

        $stmt->execute();

        return $password;
    }

    public function login($email, $password)
    {
        $sql = "SELECT id
        FROM `user`
        WHERE email = :email AND `password` = :password AND active = 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changePassword($email, $oldPassword, $newPassword)
    {
            $sql = "update user
            set password = :newPassword
            where email = :email AND password = :oldPassword;";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':newPassword', $newPassword, PDO::PARAM_STR);
            $stmt->bindValue(':oldPassword', $oldPassword, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->rowCount();
    }
}
