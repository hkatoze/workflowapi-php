<?php

function login()
{
    header("Access-Control-Allow-Origin: *");
    
    header("Access-Control-Allow-Methods: POST");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Reccupération des infos envoyées
        $inputData = json_decode(file_get_contents("php://input"));
        $databases = new Database();

        if (!empty($inputData->companyName) && !empty($inputData->emailAdd) && !empty($inputData->password)) {

            $connexion = $databases->connectToCompanyDB($inputData->companyName);
            $userInstance = new User($connexion);


            //Hydratation des données

            $userInstance->emailAdd = htmlspecialchars($inputData->emailAdd);
            $userInstance->password = htmlspecialchars($inputData->password);


            $response = $userInstance->login();
            if ($response['code'] == 0) {


                sendJSON(
                    array(
                        "user_exists" => false,
                        "message" => "L'utilisateur n'existe pas."
                    )
                );
            } else if ($response['code'] == 1) {
                sendJSON(
                    array(
                        "user_exists" => true,
                        "message" => "Authentification réussie.",
                        "user"=> $response['user']

                    )
                    );
            } else if ($response['code'] == 2) {
                sendJSON( array(
                    "user_exists" => true,
                    "message" => "Mot de passe incorrect."

                ));
            } else {
                sendJSON(
                    array(

                        "message" => "Erreur de connexion à base de donnée"

                    )
                );
            }



        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement les informations de connexion (companyName,emailAdd,password)"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }

}








?>