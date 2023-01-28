<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Product
{
    private PDO $conn;
    private Connect $db;

    public function __construct() //Si connette al DB.
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getArchiveProduct() //Ritorna tutti i prodotti.
    {
        $query = "SELECT p.id as pid, p.name as pname, p.price as price, p.description as `description`, p.quantity as quantity, c.name as category
        FROM product p 
        inner join category c on c.id = p.category
        WHERE 1=1 
        ORDER BY p.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNutritionalValue($id)
    {
        $query = "SELECT kcal, fats, saturated_fats, carbohydrates, sugars, proteins, fiber, salt 
        FROM nutritional_value 
        INNER JOIN product on product.nutritional_value = nutritional_value.id 
        WHERE product.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProduct($id) //Ritorna il prodotto in base al suo id.
    {
        $query = "SELECT `name` , price, `description`, quantity
        FROM product p 
        WHERE p.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductAllergens($id) //Ritorna gli allergeni di un prodotto.
    {
        $query = "SELECT DISTINCT a.id, a.name 
        FROM product p 
        INNER JOIN product_allergen pa ON p.id = pa.product 
        INNER JOIN allergen a ON a.id = pa.allergen 
        WHERE p.id = :id ORDER BY a.name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductIngredients($id) //Ritorna gli ingredienti di un prodotto.
    {
        $query = "SELECT DISTINCT ingredient.id, ingredient.name 
        FROM product p 
        INNER JOIN product_ingredient ON p.id = product_ingredient.product 
        INNER JOIN ingredient ON ingredient.id = product_ingredient.ingredient
        WHERE p.id = :id 
        ORDER BY ingredient.name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductTags($id) //Ritorna i tag di un prodotto.
    {
        $query = "SELECT t.id, t.name 
        FROM product p 
        INNER JOIN product_tag pt ON p.id = pt.product 
        INNER JOIN tag t ON pt.tag = t.id 
        WHERE p.id = :id 
        ORDER BY t.name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductCategory($id) //Ritorna la categoria di un prodotto.
    {
        $query = "SELECT c.id, c.name 
        FROM product p 
        inner join category c on c.id = p.category
        where p.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeProductActive($product_id, $active)
    {
        $sql = "UPDATE product
        SET active = :active
        where id = :product_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindValue(':active', $active, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    // Note: 
    //      Nutritional values deve essere un array di double in questo ordine: 
    //       [kcal, fats, saturated_fats, carbohydrates, sugars, proteins, fiber, salt].
    //
    //      Gli ingredienti devono già esistere, nel caso si voglia creare un prodotto con nuovi ingredienti
    //       bisogna prima crearli per poi creare il prodotto.
    //
    //      $ingredients_ids => array di interi contenente gli id
    //      $tags_ids => array di interi contenente gli id
    public function createProduct($name, $price, $description, $quantity, $ingredients_ids, $tags_ids, $category, $nutritional_values) //Inserisce un nuovo prodotto.
    {
        // Creazione di nuovi valori nutrizionali
        $query = "INSERT INTO nutritional_value (kcal, fats, saturated_fats, carbohydrates, sugars, proteins, fiber, salt) 
                VALUES (:kcal, :fats, :saturated_fats, :carbohydrates, :sugars, :proteins, :fiber, :salt)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":kcal", $nutritional_values[0], PDO::PARAM_STR);
        $stmt->bindValue(":fats", $nutritional_values[1], PDO::PARAM_STR);
        $stmt->bindValue(":saturated_fats", $nutritional_values[2], PDO::PARAM_STR);
        $stmt->bindValue(":carbohydrates", $nutritional_values[3], PDO::PARAM_STR);
        $stmt->bindValue(":sugars", $nutritional_values[4], PDO::PARAM_STR);
        $stmt->bindValue(":proteins", $nutritional_values[5], PDO::PARAM_STR);
        $stmt->bindValue(":fiber", $nutritional_values[6], PDO::PARAM_STR);
        $stmt->bindValue(":salt", $nutritional_values[7], PDO::PARAM_STR);

        $stmt->execute();

        // L'ID dei valori nutrizionali appena inseriti
        $lastId = $this->conn->lastInsertId();

        // Creazione del prodotto
        $query = "INSERT INTO product (name, price, description, quantity, nutritional_value, category) 
                VALUES(:name, :price, :description, :quantity, :nutritional, :category)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":price", $price, PDO::PARAM_STR);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindValue(":nutritional", $lastId, PDO::PARAM_INT);
        $stmt->bindValue(":category", $category, PDO::PARAM_INT);

        $stmt->execute();

        $productId = $this->conn->lastInsertId();

        // Collegamento tra prodotto e tag
        $query = "INSERT INTO product_tag (product, tag)
                VALUES (:product, :tag)";

        for ($i = 0; $i < sizeof($tags_ids); $i++) {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":product", $productId, PDO::PARAM_INT);
            $stmt->bindValue(":tag", $tags_ids[$i], PDO::PARAM_INT);

            $stmt->execute();
        }

        // Collegamento tra prodotto e ingredient
        $query = "INSERT INTO product_ingredient (product, ingredient)
                VALUES (:product, :ingredient)";

        for ($i = 0; $i < sizeof($ingredients_ids); $i++) {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":product", $productId, PDO::PARAM_INT);
            $stmt->bindValue(":ingredient", $ingredients_ids[$i], PDO::PARAM_INT);

            $stmt->execute();
        }

        // Query sul prodotto appena creato
        $query = "SELECT *
                FROM product
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifyProductName($id, $name) //Modifica il nome di un prodotto.
    {
        $query = "UPDATE product
                SET name = :name
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();

        $query = "SELECT *
                FROM product
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifyProductPrice($id, $price) //Modifica il prezzo di un prodotto.
    {
        $query = "UPDATE product
                SET price = :price
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":price", $price, PDO::PARAM_STR);
        $stmt->execute();

        $query = "SELECT *
                FROM product
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifyProductDescription($id, $description) //Modifica la descrizione di un prodotto.
    {
        $query = "UPDATE product
                SET description = :description
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->execute();

        $query = "SELECT *
                FROM product
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifyProductQuantity($id, $quantity) //Modifica la quantità in magazzino di un prodotto.
    {
        $query = "UPDATE product
                SET quantity = :quantity
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->execute();

        $query = "SELECT *
                FROM product
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifyProductCategory($id, $category) //Modifica lo stato di un prodotto.
    {
        $query = "UPDATE product
                SET category = :category
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":category", $category, PDO::PARAM_INT);
        $stmt->execute();

        $query = "SELECT *
                FROM product
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* API solo paninara
    public function deleteProductFromAllIngredients($id) //Cancella il prodotto nella tabella molti a molti con gli ingredienti.
    {
        $query = 'DELETE FROM product_ingredient WHERE product = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }*/

    /* API solo paninara
    public function deleteProductFromAllTags($id) //Cancella il prodotto nella tabella molti a molti con i tag.
    {
        $query = 'DELETE FROM product_tag WHERE product = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }*/

    /* API solo paninara
    public function deleteProductFromAllOrders($id) //Cancella il prodotto nella tabella molti a molti con gli ordini.
    {
        $query = 'DELETE FROM product_order WHERE product = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }*/

    /* API solo paninara
    public function deleteProductFromAllCarts($id) //Cancella il prodotto nella tabella molti a molti con i carrelli.
    {
        $query = 'DELETE FROM product_cart WHERE product = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }*/

    /* API solo paninara
    public function deleteProduct($id) //Cancella il prodotto dalla tabella product.
    {
        $this->deleteProductFromAllIngredients($id); //Richiama il metodo per rimuovere il prodotto dalla tabella molti a molti (per permettermi poi di eliminarlo dalla tabella product).
        $this->deleteProductFromAllTags($id); //Richiama il metodo per rimuovere il prodotto dalla tabella molti a molti (per permettermi poi di eliminarlo dalla tabella product).
        $this->deleteProductFromAllOrders($id); //Richiama il metodo per rimuovere il prodotto dalla tabella molti a molti (per permettermi poi di eliminarlo dalla tabella product).
        $this->deleteProductFromAllCarts($id); //Richiama il metodo per rimuovere il prodotto dalla tabella molti a molti (per permettermi poi di eliminarlo dalla tabella product).

        $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }*/
}
