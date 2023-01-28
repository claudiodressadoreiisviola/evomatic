<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Ingredient
{
    private PDO $conn;
    private Connect $db;

    public function __construct() //Si connette al DB.
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getArchiveIngredient() //Ritorna tutti gli ingredienti.
    {
        $query = "SELECT * 
        FROM ingredient i 
        WHERE 1=1 
        ORDER BY i.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createIngredient($name, $description) //Inserisce un nuovo ingrediente.
    {
        $query = "INSERT INTO ingredient i (name, description)
        VALUES (':name', ':description')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':name', $name, PDO::PARAM_INT);
        $stmt->bindValue(':description', $description, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ModifyIngredient($id, $name, $description)
    {
        $query_name = "UPDATE ingredient i
        SET i.name = :name
        WHERE i.id = :id";

        $stmt = $this->conn->prepare($query_name);
        $stmt->bindValue(':name', $name, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $query_description = "UPDATE ingredient i
        SET i.description = :description
        WHERE i.id = :id";

        $stmt = $this->conn->prepare($query_description);
        $stmt->bindValue(':description', $description, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $query_return = "SELECT ingredient.name, ingredient.description
        FROM ingredient";

        $stmt = $this->conn->prepare($query_return);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*public function getIngredient($id) //Ritorna l'ingrediente in base al suo id.
    {
        $query = "SELECT `name`, `description` FROM ingredient WHERE ingredient.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }*/

    // public function getIngredientAllergens($id) //Ritorna gli allergeni di un ingrediente.
    // {
    //     $query = 'SELECT a.id, a.name FROM ' . $this->table_name . ' i INNER JOIN ingredient_allergen ia ON i.id = ia.ingredient INNER JOIN allergen a ON ia.allergen = a.id WHERE i.id = ' . $id . ' ORDER BY a.name';

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();

    //     return $stmt;
    // }

    // public function deleteIngredientFromAllAllergens($id) //Cancella l'ingrediente nella tabella molti a molti con gli allergeni.
    // {
    //     $query = 'DELETE FROM ingredient_allergen WHERE ingredient = ' . $id;

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    // }

    // public function deleteIngredientFromAllProducts($id) //Cancella l'ingrediente nella tabella molti a molti con gli ingredienti.
    // {
    //     $query = 'DELETE FROM product_ingredient WHERE ingredient = ' . $id;

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    // }

    // public function deleteIngredient($id) //Cancella l'ingrediente dalla tabella ingredient.
    // {
    //     $this->deleteIngredientFromAllAllergens($id); //Richiama il metodo per rimuovere l'ingrediente dalla tabella molti a molti (per permettermi poi di eliminarlo dalla tabella ingredient).
    //     $this->deleteIngredientFromAllProducts($id); //Richiama il metodo per rimuovere l'ingrediente dalla tabella molti a molti (per permettermi poi di eliminarlo dalla tabella ingredient).

    //     //$query = 'DELETE i, ia FROM' . $this-> table_name . ' i INNER JOIN ingredients_allergens ia ON i.id = ia.allergens_id WHERE i.id = ' . $id;
    //     $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = ' . $id;

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    // }

    // public function setIngredientAllergen($ingredient_id, $allergen_id) //Inserisce valori nella tabella ingredient_allergen.
    // {
    //     $query = 'INSERT INTO ingredient_allergen (ingredient, allergen) VALUES(' . $ingredient_id . ', ' . $allergen_id . ')';

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    // }

    // public function createIngredient($name, $description, $price, $quantity, $allergens_ids) //Inserisce un nuovo ingrediente.
    // {
    //     $query = 'INSERT INTO ' . $this->table_name . '(name, description, price, quantity) VALUES(\'' . $name . '\', \'' . $description . '\', ' . $price . ', ' . $quantity . ')';

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();

    //     if (count($allergens_ids) > 0); {
    //         $query1 = 'SELECT DISTINCT id FROM ' . $this->table_name . ' WHERE name = \'' . $name . '\''; //Query per ritornarmi l'id dell'allergene.
    //         $stmt1 = $this->conn->prepare($query1);
    //         $stmt1->execute();
    //         $res = $stmt1->fetch(PDO::FETCH_ASSOC); //why

    //         for ($i = 0; $i < count($allergens_ids); $i++) {
    //             $this->setIngredientAllergen($res, $allergens_ids[$i]);
    //         }
    //     }
    // }

    // public function modifyIngredientName($id, $name) //Modifica il nome di un ingrediente.
    // {
    //     $query = 'UPDATE ' . $this->table_name . ' i SET i.name = \'' . $name . '\' WHERE i.id = ' . $id;

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    // }

    // public function modifyIngredientDescription($id, $description) //Modifica la descrizione di un ingrediente.
    // {
    //     $query = 'UPDATE ' . $this->table_name . ' i SET i.description = \'' . $description . '\' WHERE i.id = ' . $id;

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    // }

    // public function modifyIngredientQuantity($id, $quantity) //Modifica la quantità in magazzino di un ingrediente.
    // {
    //     $query = 'UPDATE ' . $this->table_name . ' i SET i.quantity = ' . $quantity . ' WHERE i.id = ' . $id;

    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    // }
}
