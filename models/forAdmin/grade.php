<?php
class Grade
{

    private $table = "Grade";
    private $connexion = null;
    public $id;
    public $companyId;
    public $word;
    public $maxAmount;

    public function __construct($databases)
    {
        if ($this->connexion == null) {
            $this->connexion = $databases;
        }
    }


    public function createGrade()
    {


        try {

            // Requête d'insertion d'un nouveau grade
            $query = "INSERT INTO $this->table (companyId, word, maxAmount) VALUES (:companyId, :gradeName, :amountMax)";

            // Préparation de la requête
            $statement = $this->connexion->prepare($query);
            $statement->bindParam(':companyId', $this->companyId);
            $statement->bindParam(':gradeName', $this->word);
            $statement->bindParam(':amountMax', $this->maxAmount);

            // Exécution de la requête
            $exe = $statement->execute();

            return $exe;



        } catch (PDOException $e) {
            return $exe;
        }
    }


    public function deleteGrade()
    {


        try {

            // Requête de suppression du grade
            $query = "DELETE FROM $this->table WHERE id = :gradeId";

            // Préparation de la requête
            $statement = $this->connexion->prepare($query);
            $statement->bindParam(':gradeId', $this->id);

            // Exécution de la requête
            $exe = $statement->execute();

            return $exe;



        } catch (PDOException $e) {
            return false;
        }
    }


}





?>