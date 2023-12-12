<?php

class Mysql{

    private $server = "localhost";
    private $username = "root";
    private $password = "Nanda@1324";
    private $database =  "rohit";
    private $conn = null;

    public function getConnection(){

        try {

            $dbConn = "mysql:host={$this->server};dbname={$this->database}";
            $this->conn = new PDO($dbConn,$this->username,$this->password); 
        } catch (PDOException $th) {
            echo "Connection Error : ".$th->getMessage();
        }

        return $this->conn;
    }

}

?>