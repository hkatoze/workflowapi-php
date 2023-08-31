<?php







function loginAsAdmin()
{
    header("Access-Control-Allow-Origin: *");
    
    header("Access-Control-Allow-Methods: POST");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $databases = new Database();
        $connexion = $databases->connectToWorkflowDB();
        $administratorInstance = new Administrator($connexion);
        $administrators = $administratorInstance->getAll();

        //Reccupération des infos envoyées

        $inputData = json_decode(file_get_contents("php://input"));

        if (!empty($inputData->email) && !empty($inputData->password)) {
            // Aucun administrateur ne correspond aux informations d'identification fournies
            $response = array(
                "status" => "error",
                "message" => "Les identifiants sont incorrects."
            );

            foreach ($administrators as $administrator) {
                if ($administrator['email'] === htmlspecialchars($inputData->email) && $administrator['password'] === htmlspecialchars($inputData->password)) {
                    // L'adresse e-mail et le mot de passe correspondent à un administrateur existant
                    $response = array(
                        "status" => "success",
                        "message" => "Vous etes connectées."
                    );
                }
            }

            sendJSON($response);

        } else {

            sendJSON(array("message" => "Veuillez renseigner correctement les informations de connexion (email,password)"));
        }


    } else {

        sendJSON(array("message" => "La methode n'est pas autorisée"));
    }




}








?>