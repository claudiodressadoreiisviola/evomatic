<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Cart
{
    private Connect $db;
    private PDO $conn;

    public function __construct()
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }


    // Da rivedere per l'aggiunta di prodotti gia' presenti nel carrello (se gia' presente devi updatare la quantita')
    public function addCart($id_user, $id_product, $quantity)
    {
        //aggiungo riga per quell'utente alla tabella cart (andrebbe fatto solo una volta all'aggiunta del primo prodotto al carello quindi manca un if), altro problema è come reperire l'user
        //che sta facendo l'acquisto, forse con la getuser, ma per adesso lo passo al metodo.
        $sql = "INSERT INTO cart (`user`, product, quantity) 
        VALUES(:id_user, :id_product, :quantity)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function addCartProduct($id_user, $id_product, $quantity)
    {
        $cart = $this->getCart($id_user);
        for($i = 0; $i < count($cart); $i++)
        {
            if($cart[$i]["id"] == $id_product){
                $statement = $this->updateQuantity($id_user, $id_product, $quantity);
                return $statement;
            }
        }
        $sql = "INSERT into cart (`user`, product, quantity)
        values(:id_user, :id_product, :quantity)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateQuantity($id_user, $id_product, $quantity)
    {
        $sql = "UPDATE cart 
        set quantity = quantity + :quantity
        where `user` = :id_user AND product = :id_product";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getCart($id) //ritorna l'id dei prodotti
    {
        $sql = "SELECT product.id AS id, product.`name` AS `name`, cart.quantity AS quantity
        FROM product 
        INNER JOIN cart ON product.id = cart.product
        INNER JOIN `user` ON `user`.id = cart.`user`
        WHERE `user`.id = :id AND `user`.active = 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeQuantity($id_user, $id_product, $new_quantity)
    {
        $sql = "UPDATE cart
            SET quantity = :new_quantity
            WHERE `user` = :id_user AND product = :id_product";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->bindValue(':new_quantity', $new_quantity, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function removeProduct($id_user, $id_product)
    {
        $sql = "DELETE FROM cart
        WHERE `user` = :id_user AND product = :id_product";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    // Rimuovi dalla tabella cart_product
    public function removeAllProducts($id_user)
    {
        $sql = "DELETE FROM cart
        WHERE `user` = :id_user";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Rimuovi dalla tabella cart
    public function removeCart($id_user)
    {
        $statement = $this->removeAllProducts($id_user);
        if (!$statement)
            return 0;
        $sql = "DELETE FROM cart WHERE `user` = :id_user";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }
}
