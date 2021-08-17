<?php

    class Database
    {

        private $dsn = "mysql:host=localhost;dbname=rest-api";
        private $db_username = "root";
        private $db_password = "";
        public $conn;

        public function __construct()
        {
            try{

                $this->conn = new PDO($this->dsn, $this->db_username,$this->db_password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }catch(PDOException $e){

                echo "Connection Error: ".$e->getMessage();
            }
        }
    }
