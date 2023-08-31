<?php


function newGrade(){

     
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: *");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $databases = new Database();
    $connexion = $databases->connectToWorkflowDB();
    $gradeInstance = new Grade($connexion);

    //Reccupération des infos envoyées

    $inputData = json_decode(file_get_contents("php://input"));
    
    

    if (!empty($inputData->companyId) && !empty($inputData->gradeName) && !empty($inputData->maxAmount) ) {



        //Hydratation des données
        $gradeInstance->companyId = intval($inputData->companyId);
        $gradeInstance->word = htmlspecialchars($inputData->gradeName);
        $gradeInstance->maxAmount = htmlspecialchars($inputData->maxAmount);
      

        $response = $gradeInstance->createGrade();


        if ($response) {
            sendJSON(
                array(
                    "status" => "success",
                    "message" => "Nouveau grade crée"
                )
            );
        } else {
            sendJSON(
                array(
                    "status" => "error",
                    "message" => "Echec lors de la création d'un nouveau grade",

                )
            );
        }


    } else {

        sendJSON(array("message" => "Veuillez renseigner correctement les informations du grade à crée (companyId,gradeName,maxAmount)"));
    }


} else {

    sendJSON(array("message" => "La methode n'est pas autorisée"));
}


}










?>