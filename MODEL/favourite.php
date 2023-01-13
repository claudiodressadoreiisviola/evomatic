<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Favourite
{
    private Connect $db;
    private PDO $conn;

    public function __construct()
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getArchiveFavourite($id)
    {
        $sql = "SELECT product.name as pname, product.id as id, user.id as `uid`
                FROM favourite
                INNER JOIN product ON product.id = favourite.product
                INNER JOIN user ON user.id = favourite.`user`
                WHERE user.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setFavourite($product, $user_id)
    {
        $date = date("Y-m-d h:i:s");

        $favourite = $this->getArchiveFavourite($user_id);

        $archiveFavourites = array();
        for ($i = 0; $i < (count($favourite)); $i++) {
            $archiveFavourite = array(
                "product" => $favourite[$i]["id"],
                "user" => $favourite[$i]["uid"]
            );
            array_push($archiveFavourites, $archiveFavourite);
        }

        for ($i = 0; $i < count($archiveFavourites); $i++) {
            if ($archiveFavourites[$i]["product"] == $product) {
                return -1;
            }
        }

        $sql = "INSERT INTO favourite (user, product, created)
                VALUES (:user, :product, :created)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':product', $product, PDO::PARAM_INT);
        $stmt->bindValue(':created', $date, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function removeFavourite($product_id, $user_id)
    {
        $sql = "DELETE FROM favourite
                WHERE product = :product AND user = :user";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':product', $product_id, PDO::PARAM_INT);
        $stmt->bindValue(':user', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
