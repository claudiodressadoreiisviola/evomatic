<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../COMMON/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

class Allergen
{
    private PDO $conn;
    private Connect $db; 

    public function __construct()
    {
        $this->db = new Connect;
        $this->conn = $this->db->getConnection();
    }

    public function getArchiveAllergen() //Ritorna tutti gli allergeni.
    {
        $query = "SELECT `name`, id
        FROM allergen a 
        WHERE 1=1 
        ORDER BY a.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllergen($id) //Ritorna l'allergene in base al suo id.
    {
        $query = "SELECT `name` 
        FROM allergen a 
        WHERE a.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteAllergenFromAllIngredients($id) //Cancella l'allergene nella tabella molti a molti.
    {
        $query = "DELETE FROM product_allergen 
        WHERE allergen = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteAllergen($id) //Cancella l'allergene dalla tabella allergen.
    {
        $this->deleteAllergenFromAllIngredients($id); //Richiama il metodo per rimuovere l'allergene dalla tabella molti a molti (per permettermi poi di eliminarla dalla tabella allergen).

        //$query = 'DELETE a, ia FROM' . $this-> table_name . ' a INNER JOIN ingredients_allergens ia ON a.id = ia.allergens_id WHERE a.id = ' . $id;
        $query = "DELETE FROM allergen 
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function createAllergen($name) //Inserisce nella tabella un nuovo allergene.
    {
        $query = "INSERT INTO allergen (`name`) 
        VALUES (:all_name)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':all_name', $name, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function modifyAllergenName($id, $name) //Modifica il nome di un allergene.
    {
        $query = "UPDATE allergen a 
        SET a.name = :name 
        WHERE a.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':name', $name, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
