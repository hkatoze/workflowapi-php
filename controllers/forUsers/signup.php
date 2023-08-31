<?php



function signUp()
{
    header("Access-Control-Allow-Origin: *");
 
    header("Access-Control-Allow-Methods: POST");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          //Reccupération des infos envoyées
        $inputData = json_decode(file_get_contents("php://input"));
        $databases = new Database();
        
         

       
      

        if (!empty($inputData->companyName) && !empty($inputData->emailAdd)&&!empty($inputData->username) && !empty($inputData->department)&&!empty($inputData->grade) && !empty($inputData->password)&&!empty($inputData->maxAmount)) {

            $connexion = $databases->connectToCompanyDB($inputData->companyName);
            $userInstance = new User($connexion);
            

            //Hydratation des données
            $userInstance->companyName = htmlspecialchars($inputData->companyName);
            $userInstance->emailAdd = htmlspecialchars($inputData->emailAdd);
            $userInstance->username = htmlspecialchars($inputData->username);
            $userInstance->department = htmlspecialchars($inputData->department);
            $userInstance->grade = htmlspecialchars($inputData->grade);
            $userInstance->password = htmlspecialchars($inputData->password);
            $userInstance->maxAmount = htmlspecialchars($inputData->maxAmount);

            $response = $userInstance->signup();
            if ($response) {
                sendJSON(
                    array(
                        "data_inserted" => true,
                        "message" => "success"
                    )
                );
            } else {
                sendJSON(
                    array(
                        "data_inserted" => false,
                        "message" => "already registered."

                    )
                );
            }

 

        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement les informations de création de compte (companyName,emailAdd,username,department,grade,password,maxAmount)"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }




}








?>