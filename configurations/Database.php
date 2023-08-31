<?php
 
class Database
{


    //Workflow Database credentials
    private  $workflow_host = "www.vbs-solutions.com";
    private  $workflow_dbname = "u833159023_workflow_admin";
    private  $workflow_username = "u833159023_kinda";
    private  $workflow_password = "Kind@1404";


    public function connectToWorkflowDB()
    {

        try {
            $pdo = new PDO("mysql:host=$this->workflow_host;dbname=$this->workflow_dbname", $this->workflow_username, $this->workflow_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            echo "Erreur de connexion : " . $e->getMessage();
        }

        return $pdo;
    }



    public function connectToCompanyDB($companyName)
{
   

    try {
        $pdo = new PDO("mysql:host=$this->workflow_host;dbname=$this->workflow_dbname", $this->workflow_username, $this->workflow_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $selectQuery = "SELECT * FROM Company WHERE companyName = :company";
        $stmt = $pdo->prepare($selectQuery);
        $stmt->bindParam(':company', $companyName);
        $stmt->execute();
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdo = null;
        if (!$company) {
            
        } else {
            // Récupérer les informations de connexion de la compagnie
            $database = $company['databaseName'];
            $servername = $company['servername'];
            $username = $company['username'];
            $password = $company['password'];
            
                if($servername != "vbs-solutions.com"){
                    $pdo= new PDO("sqlsrv:Server=$servername;Database=$database",  $username, $password);
                }else{
                    $pdo= new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                }
           
        }
        
    
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }

    return $pdo;
}



}








?>