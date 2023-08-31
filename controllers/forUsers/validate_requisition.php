<?php

function validateRequisition()
{
    header("Access-Control-Allow-Origin: *");
 
    header("Access-Control-Allow-Methods: POST");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Reccupération des infos envoyées
        $inputData = json_decode(file_get_contents("php://input"));
        $databases = new Database();

        if (!empty($inputData->companyName) && !empty($inputData->onhold) && !empty($inputData->reqNumber)) {

            $connexion = $databases->connectToCompanyDB($inputData->companyName);
            $requisitionInstance = new Requisition($connexion);


            //Hydratation des données

            $requisitionInstance->ONHOLD = htmlspecialchars($inputData->onhold);
            $requisitionInstance->RQNNUMBER = $inputData->reqNumber;
            $response = $requisitionInstance->validateRequisition();
            if ($response == 0) {
                sendJSON(
                    array(
                        "status" => "error",
                        "message" => "Aucune requisition correspondante n'a été trouvée."
                    )
                );
            } else if ($response == 1) {
                sendJSON(
                    array(
                        "status" => "success",
                        "message" => "Le statut de la requisition a été modifié avec succès."
                    )
                    );
            }else {
                sendJSON(
                    array(
                        "status" => "error",
                        "message" => "Erreur lors de la mise à jour de la requisition"
                    )
                );
            }



        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement les paramètres (companyName,onhold,reqNumber)"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }

}








?>