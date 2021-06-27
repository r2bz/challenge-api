<?php 
  namespace Api\Config;
  
  class Database {
    // Parâmetros para acessar a base
    private $driv = 'mysql';
    private $host = 'db';
    private $db_name = 'db_app';
    private $username = 'root';
    private $password = 'password';
    private $conn;



    // DB Connect
    public function connect() {
      $this->conn = null;

      try { 
 
        $this->conn = new \PDO( 'mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
 
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
      } catch(\PDOException $e) {
        echo 'Erro de Conexão: ' . $e->getMessage();
        throw new \PDOException ($e);
      }

      return $this->conn;
    }

    
    public function get_health_mysql(){
      try {
          $connection = new \PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
          $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      
      }catch(\PDOException $e)
      {
        return json_encode(array('status' => 'error', 'data' => "MySQL Indisponível. ". $e->getMessage()),JSON_UNESCAPED_UNICODE) ;

      }
      return json_encode(array('status' => 'sucess', 'data' => "MySQL Disponível."),JSON_UNESCAPED_UNICODE);
     
    }

  }
  ?>