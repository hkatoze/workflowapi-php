<?php
class Department
{
    private $table = "Department";
    private $connexion = null;
    public $id;
    public $companyId;
    public $departmentName;

    public function __construct($databases)
    {
        if ($this->connexion == null) {
            $this->connexion = $databases;
        }
    }

    public function createDepartment()
    {


        try {


            // Requête d'insertion d'un nouveau grade
            $query = "INSERT INTO $this->table (companyId, departmentName) VALUES (:companyId, :departmentName)";

            // Préparation de la requête
            $statement = $this->connexion->prepare($query);
            $statement->bindParam(':companyId', $this->companyId);
            $statement->bindParam(':departmentName', $this->departmentName);
            $exe = $statement->execute();

            return $exe;

        } catch (PDOException $e) {

            return false;
        }
    }


    public function deleteDepartment()
    {


        try {


            // Requête de suppression du grade
            $query = "DELETE FROM Department WHERE id = :departmentId";

            // Préparation de la requête
            $statement = $this->connexion->prepare($query);
            $statement->bindParam(':departmentId', $this->id);


            $exe = $statement->execute();

            return $exe;
        } catch (PDOException $e) {
            return false;
        }
    }


    



}




?>