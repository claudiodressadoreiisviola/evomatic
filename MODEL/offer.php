<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Offer
{
    private Connect $db;
    private PDO $conn;

    public function __construct()
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getOffer($id) //ritorna nome del prodotto, prezzo, data di expiry, descrizione offerta 
    {
        $sql = "SELECT p.id, p.name,o.price,o.expiry,o.description 
            FROM offer o
            INNER JOIN product_offer po ON po.offer = o.id
            INNER JOIN product p ON p.id = po.product
            WHERE o.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getArchiveOffer() //ritorna nome del prodotto, prezzo, data di expiry, descrizione offerta
    {
        $sql = "SELECT p.id, p.name, o.price, o.expiry, o.description 
            FROM offer o
            INNER JOIN product_offer po ON po.offer = o.id
            INNER JOIN product p ON p.id = po.product
            WHERE 1=1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createOffer($price,$start,$expiry,$description,$products)
    {
        $sql = "INSERT INTO offer (price,`start`,`expiry`,`description`)
        VALUES(:price,:start,:expiry,:description)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':expiry', $expiry, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);

        $stmt->execute();

        $offer_id = $this->conn->lastInsertId();

        $query = "INSERT INTO product_offer(product, offer)
                VALUES (:product, :offer)";

        for ($i = 0; $i < sizeof($products); $i++) {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":product", $products[$i], PDO::PARAM_INT);
            $stmt->bindValue(":offer", $offer_id, PDO::PARAM_INT);

            $stmt->execute();
        }

        return $stmt->rowCount();
    }
}
