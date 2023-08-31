<?php
class Administrator
{
    private $table = "administrateurs";
    private $connexion = null;
    public $id;
    public $email;
    public $password;

    public function __construct($databases)
    {

        if ($this->connexion == null) {
            $this->connexion = $databases;
        }

    }

    public function getAll()
    {
        $sql = "SELECT * FROM $this->table";
        $statement = $this->connexion->query($sql);
        $administrators = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $administrators;
    }


}








?>