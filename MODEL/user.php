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

    public function resetPassword($id,$date)
    {
        // Generazione della password randomica
        $password = bin2hex(openssl_random_pseudo_bytes(4));

        $sql = "UPDATE user
        SET password = :password
        WHERE id = :id AND active = 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        // Aggiunta alla tabella reset l'utente
        $sql = "INSERT INTO reset
            (user, password, requested, expires, completed)
            VALUES (:user, :password, :requested, :expires, :completed)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':requested', $date, PDO::PARAM_STR);
        $stmt->bindValue(':expires', date("Y-m-d", strtotime($date . '+ 5 Days')), PDO::PARAM_STR);
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

    public function activateUser($id)
    {
        $user = $this->getUser($id);

        if ($user == null)
            return false;

        $sql = "UPDATE user
        SET active = 1
        WHERE  id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function registerUser($name, $surname, $email, $password, $year, $section, $schoolYear, $active)
    {
        // Aggiungo l'utente nella tabella user
        $sql = "INSERT INTO `user`
        (name, surname, email, password, active)
        VALUES (:name, :surname, :email, :password, :active)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':active', $active, PDO::PARAM_STR);

        $stmt->execute();
        $user = $stmt->lastInsertId();

        // Chiamo una funzione per assegnare l'utente ad una classe
        assignToClass($user, $year, $section, $schoolYear);
    }

    public function assignToClass($user, $year, $section, $schoolYear)
    {
        // Ottengo le classi a cui un determinato utente è iscritto in un determinato anno
        $sql = "SELECT `user`.name
        FROM user_class
        INNER JOIN `user` ON user_class.`user` = `user`.id
        WHERE `user`.id = :user AND user_class.`year` = :schoolYear";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user', $user, PDO::PARAM_INT);
        $stmt->bindValue(':schoolYear', $schoolYear, PDO::PARAM_STR);

        $stmt->execute();

        // Se l'utente non è iscritto ad alcuna classe
        if ($stmt->rowCount() == 0)
        {
            // Controllo che la classe a cui iscrivere l'utente esista
            $sql = "SELECT class.id
            FROM class
            WHERE class.year = :year AND class.`section` = :section"
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            $stmt->bindValue(':section', $section, PDO::PARAM_STR);

            $stmt->execute();

            // Se la classe non esiste la creo
            if ($stmt->rowCount() == 0)
            {
                $sql = "INSERT INTO class ( `year`, `section` )
                VALUES ( :year, :section )";
            
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
                $stmt->bindValue(':section', $section, PDO::PARAM_STR);

                $stmt->execute();
            }

            // Associo la classe all'utente aggiungendo anche l'anno scolastico
            $sql = "INSERT INTO user_class ( `user`, class, `year` )
            VALUES ( :user, (SELECT class.id FROM class WHERE class.`year` = :year AND class.`section` = :section), :schoolYear )"
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user', $user, PDO::PARAM_INT);
            $stmt->bindValue(':schoolYear', $schoolYear, PDO::PARAM_STR);
            $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            $stmt->bindValue(':section', $section, PDO::PARAM_STR);

            $stmt->execute();

            return 0;
        }
        else
        {
            return 0;
        }
    }
}
?>