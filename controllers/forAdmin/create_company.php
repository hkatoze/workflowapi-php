<?php








function newCompany()
{
    header("Access-Control-Allow-Origin: *");
 
    header("Access-Control-Allow-Methods: POST");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $databases = new Database();
        $connexion = $databases->connectToWorkflowDB();
        $companyInstance = new Company($connexion);

        //Reccupération des infos envoyées

        $inputData = json_decode(file_get_contents("php://input"));



        if (!empty($inputData->backgroundColor) && !empty($inputData->companyName) && !empty($inputData->username) && !empty($inputData->databaseName) && !empty($inputData->password) && !empty($inputData->servername)) {



            //Hydratation des données
            $companyInstance->backgroundColor = htmlspecialchars($inputData->backgroundColor);
            $companyInstance->companyName = htmlspecialchars($inputData->companyName);
            $companyInstance->username = htmlspecialchars($inputData->username);
            $companyInstance->databaseName = htmlspecialchars($inputData->databaseName);
            $companyInstance->password = htmlspecialchars($inputData->password);
            $companyInstance->servername = htmlspecialchars($inputData->servername);

            $response = $companyInstance->createCompany();


            if ($response) {
                sendJSON(
                    array(
                        "status" => "success",
                        "message" => "Nouvelle compagnie ajoutée"
                    )
                );
            } else {
                sendJSON(
                    array(
                        "status" => "error",
                        "message" => "Echec lors de la création d'une nouvelle compagnie",

                    )
                );
            }


        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement les informations de la compagnie à crée (backgroundColor,companyName,username,databaseName,password,servername)"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }
}












?>