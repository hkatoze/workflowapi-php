<?php
class User
{
    private $table = "WORKFLOWUSERS";
    private $connexion = null;
    public $companyName;
    public $emailAdd;
    public $username;
    public $department;
    public $grade;
    public $password;
    public $maxAmount;

    public function __construct($databases)
    {

        if ($this->connexion == null) {
            $this->connexion = $databases;
        }

    }

    public function signup()
    {

        // Vérifier si la table existe déjà

        $checkTableQuery = "SELECT 1 FROM information_schema.tables WHERE table_name = ?";
        $stmt = $this->connexion->prepare($checkTableQuery);

        $stmt->execute([$this->table]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!(($result !== false))) {
            // Créer la table workflowusers si elle n'existe pas
            $createTableQuery = "CREATE TABLE $this->table (
                COMPANYID VARCHAR(255),
                EMAILADD VARCHAR(255) UNIQUE,
                USERNAME VARCHAR(255),
                DEPARTMENT VARCHAR(255),
                GRADE VARCHAR(255),
                PASSWORD VARCHAR(255),
                MAXAMOUNT VARCHAR(255)
            )";
            if ($this->connexion->exec($createTableQuery) == false) {

                return false;
            }
        }

        // Insérer les informations dans la table workflowusers
        $insertQuery = "INSERT INTO $this->table (COMPANYID, EMAILADD, USERNAME, DEPARTMENT, GRADE, PASSWORD, MAXAMOUNT)
                        VALUES (:companyId, :emailAdd, :username, :department, :grade, :password, :maxAmount)";
        $stmt = $this->connexion->prepare($insertQuery);
        $stmt->bindParam(':companyId', $this->companyName);
        $stmt->bindParam(':emailAdd', $this->emailAdd);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':department', $this->department);
        $stmt->bindParam(':grade', $this->grade);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':maxAmount', $this->maxAmount);

        if ($stmt->execute()) {
            return true;

        } else {
            return false;

        }





    }

    public function login()
    {

        $response = 3;
        // Vérifier si l'utilisateur existe

        $selectQuery = "SELECT * FROM $this->table WHERE EMAILADD = :email";
        $stmt = $this->connexion->prepare($selectQuery);
        $stmt->bindParam(':email', $this->emailAdd);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $response = array();
        if (!$user) {
            // L'utilisateur n'existe pas

         
        } else {
            // Vérifier le mot de passe
            if ($this->password === $user['PASSWORD']) {
                // Les informations d'identification sont correctes
                $response['code'] = 1;
                $response['user']=$user;
            } else {
                // Le mot de passe est incorrect
                $response['code'] = 2;
            }
        }


        return $response;
    }


    public function getAllUsers()
    {
        try {
            // Requête pour récupérer les utilisateurs de la compagnie
            $selectQuery = "SELECT * FROM $this->table";
            $stmt = $this->connexion->prepare($selectQuery);

            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            

            // Retourner les résultats au format JSON
            return $users;
        } catch (PDOException $e) {
            // Retourner un message d'erreur en cas d'échec de la requête
            return sendJSON(
                array(
                    "status" => "error",
                    "message" => "Erreur lors de la récupération des utilisateurs de la compagnie : " . $e->getMessage()
                )
            );
        }
    }
}








?>