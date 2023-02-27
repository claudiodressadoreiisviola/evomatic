<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

class ErrorHandler
{
    private PDO $conn;
    private Connect $db; 

    public function __construct()
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public static function handleException(Throwable $e): void
    {
        $sql = "INSERT INTO monitored_exceptions me ( code, message, file, line )
        VALUES ( :code, :message, :file, :line )";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':code', $e->getCode(), PDO::PARAM_INT);
        $stmt->bindValue(':message', $e->getMessage(), PDO::PARAM_STR);
        $stmt->bindValue(':file', $e->getFile(), PDO::PARAM_STR);
        $stmt->bindValue(':line', $e->getLine(), PDO::PARAM_INT);

        $stmt->execute();

        $errorid = $this->conn->lastInsertId();

        http_response_code(500);
        echo json_encode(["message" => "Si è verificata un'eccezione nel codice, riferire l'ErrorID all'amministratore", "ErrorID" => $errorid]);
        die();
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
?>