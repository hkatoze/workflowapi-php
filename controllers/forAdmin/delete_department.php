<?php

function deleteDepartment()
{


    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Origin: *");
 






    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $databases = new Database();
        $connexion = $databases->connectToWorkflowDB();
        $departmentInstance = new Department($connexion);

        //Reccupération des infos envoyées

        $inputData = json_decode(file_get_contents("php://input"));



        if (!empty($inputData->id)) {



            //Hydratation des données

            $departmentInstance->id = intval($inputData->id);

            $response = $departmentInstance->deleteDepartment();


            if ($response) {
                sendJSON(
                    array(
                        "status" => "success",
                        "message" => "Le département a été supprimé"
                    )
                );
            } else {
                sendJSON(
                    array(
                        "status" => "error",
                        "message" => "Echec de suppression du département",

                    )
                );
            }


        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement le id du département à supprimer"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }






}






?>