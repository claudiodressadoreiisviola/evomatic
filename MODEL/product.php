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
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
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

    /* API solo paninara
    public function setNutritionalValue($kcal, $fats, $saturated_fats, $carbohydrates, $sugar, $proteins, $fiber, $salt)
    {
        $query = 'INSERT INTO nutritional_value (kcal, fats, saturated_fats, carbohydrates, sugar, proteins, fiber, salt) VALUES (:kcal, :fats, :saturated_fats, :carbohydrates, :sugar, :proteins, :fiber, :salt)';

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':kcal', $kcal, PDO::PARAM_INT);
        $stmt->bindValue(':fats', $fats, PDO::PARAM_STR);
        $stmt->bindValue(':saturated_fats', $saturated_fats, PDO::PARAM_STR);
        $stmt->bindValue(':carbohydrates', $carbohydrates, PDO::PARAM_STR);
        $stmt->bindValue(':sugar', $sugar, PDO::PARAM_STR);
        $stmt->bindValue(':proteins', $proteins, PDO::PARAM_STR);
        $stmt->bindValue(':fiber', $fiber, PDO::PARAM_STR);
        $stmt->bindValue(':salt', $salt, PDO::PARAM_STR);

        $stmt->execute();
    }*/

    /* API solo paninara
    public function setProductIngredient($product_id, $ingredient_id) //Inserisce valori nella tabella product_ingredient.
    {
        $query = 'INSERT INTO product_ingredient (product, ingredient) VALUES(' . $product_id . ', ' . $ingredient_id . ')';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }*/

    /* API solo paninara
    public function setProductTag($product_id, $tag_id) //Inserisce valori nella tabella product_tag.
    {
        $query = 'INSERT INTO product_tag (product, tag) VALUES(' . $product_id . ', ' . $tag_id . ')';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }*/

    /* API solo paninara
    public function createProduct($name, $price, $description, $quantity, $active, $ingredients_ids, $tags_ids, $nutritional_values) //Inserisce un nuovo prodotto.
    {
        $query = 'INSERT INTO ' . $this->table_name . '(name, price, descpription, quantity, active) VALUES(\'' . $name . '\', ' . $price . ', \'' . $description . '\', ' . $quantity . ', ' . $active . ')';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if (empty($ingredients_ids)) {
            $query1 = 'SELECT DISTINCT id FROM ' . $this->table_name . ' WHERE name = \'' . $name . '\''; //Query per ritornarmi l'id dell'ingrediente.
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            $res = $stmt1->fetch(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($ingredients_ids); $i++) {
                $this->setProductIngredient($res, $ingredients_ids[$i]);
            }
        }

        if (empty($tags_ids)) {
            $query1 = 'SELECT DISTINCT id FROM ' . $this->table_name . ' WHERE name = \'' . $name . '\''; //Query per ritornarmi l'id del tag.
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            $res = $stmt1->fetch(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($tags_ids); $i++) {
                $this->setProductTag($res, $tags_ids[$i]);
            }
        }

        if (empty($nutritional_values)) {
            $query1 = 'SELECT DISTINCT id FROM ' . $this->table_name . ' WHERE name = \'' . $name . '\'';
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            $res = $stmt1->fetch(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($nutritional_values); $i++) {
                $this->setNutritionalValue($res, $tags_ids[$i]);
            }
        }
    }*/

    /* API solo paninara
    public function modifyProductName($id, $name) //Modifica il nome di un prodotto.
    {
        $query = 'UPDATE ' . $this->table_name . ' p SET p.name = \'' . $name . '\' WHERE p.id = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }*/

    /* API solo paninara
    public function modifyProductPrice($id, $price) //Modifica il prezzo di un prodotto.
    {
        $query = 'UPDATE ' . $this->table_name . ' p SET p.price = ' . $price . ' WHERE p.id = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }*/

    /* API solo paninara
    public function modifyProductDescription($id, $description) //Modifica la descrizione di un prodotto.
    {
        $query = 'UPDATE ' . $this->table_name . ' p SET p.description = \'' . $description . '\' WHERE p.id = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }*/

    /* API solo paninara
    public function modifyProductQuantity($id, $quantity) //Modifica la quantità in magazzino di un prodotto.
    {
        $query = 'UPDATE ' . $this->table_name . ' p SET p.quantity = ' . $quantity . ' WHERE p.id = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }*/

    /* API solo paninara
    public function modifyProductActive($id, $active) //Modifica lo stato di un prodotto.
    {
        $query = 'UPDATE ' . $this->table_name . ' p SET p.quantity = ' . $active . ' WHERE p.id = ' . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }*/
}
