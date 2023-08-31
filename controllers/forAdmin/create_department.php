<?php
 


 function newDepartment(){

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
 



 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $databases = new Database();
    $connexion = $databases->connectToWorkflowDB();
    $departmentInstance = new Department($connexion);

    //Reccupération des infos envoyées

    $inputData = json_decode(file_get_contents("php://input"));

    

    if (!empty($inputData->companyId) && !empty($inputData->departmentName) ) {



        //Hydratation des données
        $departmentInstance->companyId = intval($inputData->companyId);
        $departmentInstance->departmentName = htmlspecialchars($inputData->departmentName);
      

        $response = $departmentInstance->createDepartment();


        if ($response) {
            sendJSON(
                array(
                    "status" => "success",
                    "message" => "Nouveau département crée"
                )
            );
        } else {
            sendJSON(
                array(
                    "status" => "error",
                    "message" => "Echec lors de la création d'un nouveau départment",

                )
            );
        }


    } else {

        sendJSON(array("message" => "Veuillez renseigner correctement les informations du département à crée (companyId,departmentName)"));
    }


} else {

    sendJSON(array("message" => "La methode n'est pas autorisée"));
}





 }







?>