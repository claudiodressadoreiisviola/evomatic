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
        $sql = "SELECT p.name,o.price,o.expiry,o.description 
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
        $sql = "SELECT p.name,o.price,o.expiry,o.description 
            FROM offer o
            INNER JOIN product_offer po ON po.offer = o.id
            INNER JOIN product p ON p.id = po.product
            WHERE 1=1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentOffers()
    {
        $sql= " select product.name, product.id , offer.price , offer.description 
        from product 
        inner join product_offer on product.id = product_offer.product 
        inner join offer on offer.id = product_offer.offer 
        where (offer.`start` < CURRENT_TIMESTAMP()) and (offer.expiry > CURRENT_TIMESTAMP())  ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
