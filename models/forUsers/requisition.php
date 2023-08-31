<?php


class Requisition
{

    //Workflow Database credentials
    private $workflow_host = "www.vbs-solutions.com";
    private $workflow_dbname = "u833159023_workflow_admin";
    private $workflow_username = "u833159023_kinda";
    private $workflow_password = "Kind@1404";
    private $headerTable = "PORQNH1";
    private $lineTable = "PORQNL";
    private $connexion = null;

    private $RQNHSEQ;
    public $RQNNUMBER;
    private $VDNAME;
    private $REQUESTBY;
    private $EXPARRIVAL;
    public $ONHOLD;
    private $DESCRIPTIO;
    private $REFERENCE;
    private $RequisitionLine;
    private $TOTALAMOUNT;
    public $department;
    public $companyName;
    public $maxAmount;


    public function __construct($databases)
    {
        if ($this->connexion == null) {
            $this->connexion = $databases;
        }
    }


    private function getAdminConnexion()
    {

        return $pdo = new PDO("mysql:host=$this->workflow_host;dbname=$this->workflow_dbname", $this->workflow_username, $this->workflow_password);
    }

    private function getStartAmount($grades, $amountMax)
    {
        $startAmount = 0;

        foreach ($grades as $grade) {
            $maxAmount = (double) $grade['maxAmount'];
            if ($maxAmount < $amountMax && $maxAmount > $startAmount) {
                $startAmount = $maxAmount;
            }
        }

        return $startAmount;
    }

    private function calculateTotalAmount($requisitionLines)
    {
        $totalAmount = 0;

        foreach ($requisitionLines as $line) {
            $extended = $line['EXTENDED'];
            $totalAmount += $extended;
        }

        return $totalAmount;
    }

    public function getRequisitions()
    {

        $pdoAdmin = $this->getAdminConnexion();

        $reqForHeader = "SELECT RQNHSEQ,RQNNUMBER, VDNAME, REQUESTBY, EXPARRIVAL, ONHOLD, DESCRIPTIO, REFERENCE FROM $this->headerTable WHERE ONHOLD = 1";
        $stmtForHeader = $this->connexion->prepare($reqForHeader);
        $stmtForHeader->execute();
        $requisitionsHeader = $stmtForHeader->fetchAll(PDO::FETCH_ASSOC);

        $reqForLine = "SELECT RQNHSEQ, RQNLREV, OQORDERED, ORDERUNIT, ITEMNO, EXPARRIVAL, ITEMDESC, MANITEMNO, OEONUMBER, EXTENDED FROM $this->lineTable";
        $stmtForLine = $this->connexion->prepare($reqForLine);
        $stmtForLine->execute();
        $requisitionsLines = $stmtForLine->fetchAll(PDO::FETCH_ASSOC);

        $stmtForHeader->closeCursor();
        $stmtForLine->closeCursor();

        $requisitions = array();

        // Récupérer les RQNNUMBER correspondants au département dans la table "Mapping"
        $mappingQuery = "SELECT RQNNUMBER FROM MAPPING WHERE DEPART = :department";
        $mappingStatement = $this->connexion->prepare($mappingQuery);
        $mappingStatement->bindParam(':department', $this->department);
        $mappingStatement->execute();
        $mappedRqNumbers = $mappingStatement->fetchAll(PDO::FETCH_COLUMN);

        // Filtrer les requêtes en fonction des RQNNUMBER correspondants dans la table "Mapping"
        foreach ($requisitionsHeader as $header) {
            if (in_array($header['RQNNUMBER'], $mappedRqNumbers)) {
                $rqnhseq = $header['RQNHSEQ'];
                $header['RequisitionLine'] = array();

                foreach ($requisitionsLines as $line) {
                    if ($line['RQNHSEQ'] === $rqnhseq) {
                        $header['RequisitionLine'][] = $line;
                    }
                }

                $requisitions[] = $header;
            }
        }

        // Obtenir la plus grande valeur des grades inférieure à maxAmount
        $gradeQuery = "SELECT * FROM Grade G JOIN Company C ON G.companyId = C.id WHERE C.companyName = :companyName";
        $gradeStatement = $pdoAdmin->prepare($gradeQuery);
        $gradeStatement->bindParam(':companyName', $this->companyName);
        $gradeStatement->execute();
        $gradeResult = $gradeStatement->fetchAll(PDO::FETCH_ASSOC);


        $minTotalAmount = $this->getStartAmount($gradeResult, $this->maxAmount);
        $maxTotalAmount = $this->maxAmount;

        // Filtrer les requêtes en fonction de la plage de totalAmount
        $filteredRequisitions = array();

        foreach ($requisitions as $requisition) {
            $totalAmount = $this->calculateTotalAmount($requisition['RequisitionLine']);
            $requisition['TOTALAMOUNT'] = (string) $totalAmount;
            if ($totalAmount >= $minTotalAmount && $totalAmount <= $maxTotalAmount) {
                $filteredRequisitions[] = $requisition;

            }
        }

        $requisitions = array_values($filteredRequisitions);


        return $requisitions;

    }



    public function validateRequisition()
    {
            // Requête de mise à jour
            $updateQuery = "UPDATE $this->headerTable SET ONHOLD = :onHoldStatus WHERE RQNNUMBER = :reqNumber";
            $stmt = $this->connexion->prepare($updateQuery);
            $stmt->bindParam(':onHoldStatus', $this->ONHOLD);
            $stmt->bindParam(':reqNumber', $this->RQNNUMBER);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
            $response = 2;
            // Vérifier si la mise à jour a été effectuée avec succès
            if ($rowCount > 0) {
                // Mise à jour réussie
                $response = 1;
            } else {
                // Aucune requisition correspondante trouvée
                $response = 0;
            }

           
            return $response;
        
    }
}