<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Order
{
    private Connect $db;
    private PDO $conn;

    public function __construct()
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getArchiveOrder()
    {
        $sql = "SELECT `order`.id as oid, user.email as ue, `order`.created as oc, pickup.name as pn, `break`.time as bt, `status`.description as sd
                FROM `order`
                INNER JOIN pickup ON order.pickup  = pickup.id
                INNER JOIN `break` ON order.break = `break`.id 
                INNER JOIN `status` ON order.status = `status`.id
                INNER JOIN user ON order.user = user.id
                WHERE 1 = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOrder($id)
    {
        $sql = "UPDATE `order`
                SET `status` = 3
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getArchiveOrderStatus($status)
    {
        $sql = "SELECT `order`.id as oid, user.email as ue, `order`.created as oc, pickup.name as pn, `break`.time as bt, `status`.description as sd
                FROM `order`
                INNER JOIN user ON user.id = `order`.user
                INNER JOIN pickup ON pickup.id = `order`.pickup
                INNER JOIN `break` ON break.id = `order`.break
                INNER JOIN `status` ON status.id = `order`.status
                WHERE `status` = :stat AND user.active = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':stat', $status, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArchiveOrderBreak($break)
    {
        $sql = "SELECT `order`.id as oid, `order`.created as oc, pickup.name as pn, user.email as ue, `status`.description as sd
                FROM `order`
                INNER JOIN pickup ON `order`.pickup  = pickup.id
                INNER JOIN `break` ON `order`.break = `break`.id 
                INNER JOIN `status` ON `order`.status = `status`.id
                INNER JOIN user ON `order`.user = user.id
                WHERE break = :brk";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':brk', $break, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function setStatusOrder($id, $new_status)
    {   // setta lo stato di un ordine a 2, pronto

        $query = "UPDATE `order` SET `status` = :new_status WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':new_status', $new_status, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    function setOrder($user, $created, $break, $status, $pickup)
    {   // Crea un ordine di vetrina

        $query = "INSERT INTO `order` (user,created,`break`,`status`,pickup)
                    VALUES (:user_id,:created_id,:break_id,:status_id,:pickup_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $user, PDO::PARAM_INT);
        $stmt->bindValue(':created_id', $created, PDO::PARAM_STR);
        $stmt->bindValue(':break_id', $break, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $status, PDO::PARAM_INT);
        $stmt->bindValue(':pickup_id', $pickup, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    function getArchiveOrderUser($id) // Ottiene tutti gli ordini
    {
        $query = "SELECT o.id, o.created, p.name, b.time, s.description FROM `order` o 
        INNER JOIN `status` s on o.`status` = s.id
        INNER JOIN `break` b on o.`break` = b.id
        INNER JOIN pickup p on o.pickup = p.id
        INNER JOIN user u on u.id = o.user
        WHERE u.id = :id AND u.active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getOrder($order_id) // Ottiene l'ordine con l'id passato alla funzione
    {
        $query = "SELECT o.user, o.created, p.name, b.time, s.description FROM `order` o
        INNER JOIN `status` s on o.status = s.id
        INNER JOIN `break` b on o.break = b.id
        INNER JOIN pickup p on o.pickup = p.id
        INNER JOIN user u on u.id = o.id
        WHERE o.id = :order_id AND u.active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getOrderProduct($order_id) // Ottiene l'ordine con l'id passato alla funzione
    {
        $query = "SELECT p.id, p.name, t.name as tag, p.description, po.quantity, p.price  FROM `order` o
        INNER JOIN product_order po on o.id = po.order
        INNER JOIN product p on po.product = p.id
        INNER JOIN product_tag pt on pt.product = p.id
        INNER JOIN tag t on t.id = pt.tag
        WHERE o.id = :order_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
