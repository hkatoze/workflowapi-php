<?php

function getRequisitions()
{
    header("Access-Control-Allow-Origin: *");
   
    header("Access-Control-Allow-Methods: POST");
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Reccupération des infos envoyées
        $inputData = json_decode(file_get_contents("php://input"));
        $databases = new Database();

        if (!empty($inputData->companyName) && !empty($inputData->department) && !empty($inputData->maxAmount)) {

            $connexion = $databases->connectToCompanyDB($inputData->companyName);
            $requisitionInstance = new Requisition($connexion);


            //Hydratation des données

            $requisitionInstance->companyName = htmlspecialchars($inputData->companyName);
            $requisitionInstance->department = htmlspecialchars($inputData->department);
            $requisitionInstance->maxAmount = htmlspecialchars($inputData->maxAmount);


            $response = $requisitionInstance->getRequisitions();
             

                sendJSON(
                    $response
                );
        



        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement les paramètres de reccupérations des réquisitions (companyName,department,maxAmount)"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }

}








?>