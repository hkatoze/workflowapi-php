<?php







function deleteCompany()
{
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Origin: *");
  
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $databases = new Database();
        $connexion = $databases->connectToWorkflowDB();
        $companyInstance = new Company($connexion);

        //Reccupération des infos envoyées

        $inputData = json_decode(file_get_contents("php://input"));



        if (!empty($inputData->id)) {



            //Hydratation des données

            $companyInstance->id = intval($inputData->id);

            $response = $companyInstance->deleteCompany();


            if ($response) {
                sendJSON(
                    array(
                        "status" => "success",
                        "message" => "La compagnie a été supprimée"
                    )
                );
            } else {
                sendJSON(
                    array(
                        "status" => "error",
                        "message" => "Echec de suppression de la compagnie",

                    )
                );
            }


        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement le id de la compagnie à supprimer"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }

}











?>