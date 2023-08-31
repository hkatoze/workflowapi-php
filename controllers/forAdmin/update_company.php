<?php

function updateCompany()
{

    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Allow-Origin: *");
     






    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $databases = new Database();
        $connexion = $databases->connectToWorkflowDB();
        $companyInstance = new Company($connexion);

        //Reccupération des infos envoyées

        $inputData = json_decode(file_get_contents("php://input"));



        if (!empty($inputData->username) && !empty($inputData->databaseName) && !empty($inputData->password) && !empty($inputData->servername) && !empty($inputData->id)) {



            //Hydratation des données

            
            $companyInstance->username = htmlspecialchars($inputData->username);
            $companyInstance->databaseName = htmlspecialchars($inputData->databaseName);
            $companyInstance->password = htmlspecialchars($inputData->password);
            $companyInstance->servername = htmlspecialchars($inputData->servername);
            $companyInstance->id = intval($inputData->id);

            $response = $companyInstance->updateCompany();


            if ($response) {
                sendJSON(
                    array(
                        "status" => "success",
                        "message" => "La compagnie $inputData->companyName a été modifiée"
                    )
                );
            } else {
                sendJSON(
                    array(
                        "status" => "error",
                        "message" => "Echec de modification de la compagnie $inputData->companyName ",

                    )
                );
            }


        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement les informations de la compagnie à crée (id,companyName,username,databaseName,password,servername)"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }

}











?>