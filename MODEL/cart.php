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

    public function addCart($id_user)
    {
        //aggiungo riga per quell'utente alla tabella cart (andrebbe fatto solo una volta all'aggiunta del primo prodotto al carello quindi manca un if), altro problema è come reperire l'user
        //che sta facendo l'acquisto, forse con la getuser, ma per adesso lo passo al metodo.
        $sql = "INSERT INTO cart (user, total) 
        VALUES(:id_user, 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function addCartProduct($id_cart, $id_product, $quantity)
    {
        $sql = "INSERT into cart_product (cart, product, quantity)
        values(:id_cart, :id_product, :quantity)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_cart', $id_cart, PDO::PARAM_INT);
        $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateQuantity($quantity)
    {
        $sql = "UPDATE product 
        set quantity=(quantity-:quantity)
        where quantity>0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updatePrice()
    {
        $sql = "UPDATE cart
         inner join cart_product
         on cart.id=cart_product.cart
         inner join product
         on cart_product.product=product.id
         set total=SUM(cart_product.quantity*product.price)";
        return $this->conn->query($sql);
    }
}
