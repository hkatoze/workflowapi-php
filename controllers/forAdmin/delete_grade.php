<?php

function deleteGrade()
{

    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Origin: *");
 






    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $databases = new Database();
        $connexion = $databases->connectToWorkflowDB();
        $gradeInstance = new Grade($connexion);

        //Reccupération des infos envoyées

        $inputData = json_decode(file_get_contents("php://input"));



        if (!empty($inputData->id)) {



            //Hydratation des données

            $gradeInstance->id = intval($inputData->id);

            $response = $gradeInstance->deleteGrade();


            if ($response) {
                sendJSON(
                    array(
                        "status" => "success",
                        "message" => "Le grade a été supprimé"
                    )
                );
            } else {
                sendJSON(
                    array(
                        "status" => "error",
                        "message" => "Echec de suppression du grade",

                    )
                );
            }


        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement le id du grade à supprimer"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }



}









?>