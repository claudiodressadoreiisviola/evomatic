<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Tag
{
    private PDO $conn;
    private Connect $db;

    public function __construct() //Si connette al DB.
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getArchiveTag() 
    {
        $sql = "SELECT *
        FROM tag
        WHERE 1=1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTag($id) 
    {
        $sql = "SELECT `name`
        FROM tag
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createTag($name) {
        $sql = "INSERT INTO tag (`name`)
        VALUES (:tag_name)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":tag_name", $name, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
