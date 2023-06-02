<?php

class Db
{
    private $host = "localhost";
    private $dbName = "futboldb";
    private $user = "root";
    private $pass = "";
    private $PDO;

    public function __construct()
    {
        try {
            $this->PDO = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->user, $this->pass);
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo"<br />Verbinding NIET gemaakt";
        }
    }

    public function getPDO()
    {
        return $this->PDO;
    }
}

?>
