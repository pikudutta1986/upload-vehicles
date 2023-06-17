<?php
  class Database {
    
      private $host  = 'uploadvehicles.com';
      private $username  = 'uploadvehicles_rfeder5434';
      private $password   = "{.f-I-?0A{2(";
      private $database_name  = "uploadvehicles_Dtbss9";
      public $conn;

      public function getConnection() {		

        try{
          $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
          $this->conn->exec("set names utf8");
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            echo "Database could not be connected: " . $exception->getMessage();
        }
        return $this->conn;       
      }
  }
?>
