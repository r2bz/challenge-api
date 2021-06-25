<?php
    namespace Api\App\Models;

    use Api\Config\Database;
    
    // include_once "../Config/Database.php";

    class Incident
    {
        // Atributos com relação a base
        private $conn;
        private static $db_name = 'db_app';
        private static $table = 'incidents';

        // Propriedades da Classe Incident mapeando campos da tabela incident
        public $id;
        public $timestamp;
        public $alert_id;
        
        
        public function __construct(){
            // retorna uma conexão da instância da classe DB
            $this->conn = (new Database())->connect();
        }


        public function select(int $id) {
        }

        
        public function selectAll() {
        }

        public function insert($data){

            
            $sql = 'INSERT INTO 
            `' . self::$db_name . '`.`' . self::$table . 
            '` ( 
                `id`, 
                `timestamp`, 
                `alert_id`
                ) 
            VALUES ( DEFAULT, NOW(), :alert_id );';
            
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':alert_id', $data);  
            
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                //'Incidente inserido com sucesso!';
                return true;
            } else {
                throw new Exception("Falha ao inserir Incidente!");
            }
        
        }
      
    }