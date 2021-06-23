<?php
    //namespace App\Models;

    include_once "../Config/Database.php";
    

    class Alert
    {
        // Atributos com relação a base
        private $conn;
        private static $db_name = 'db_app';
        private static $table = 'alerts';

        // Propriedades dos alertas
        public $id;
        public $app_name;
        public $title;
        public $description;
        public $enabled;
        public $metric;
        public $condition;
        public $threshold;

        public function __construct(){
            // retorna uma conexão da da instância da classe DB
            $this->conn = (new Database())->connect();
        }

        public function select(int $id) {

            $sql = 'SELECT 
                     `id`,
                     `app_name`, 
                     `title`,
                     `description`,
                     `enabled`,
                     `metric`,
                     `condition`,
                     `threshold` 
                     FROM `'
                    . self::$db_name . '`.`' . self::$table . '` WHERE `id` = :id';
                
            //Prepara a query    
            $stmt=$this->conn->prepare($sql);
                
            // substitui os valores na query
            $stmt->bindValue(':id', $id);

            // executa a consulta
            $stmt->execute();
        
            // Verifica se a consulta retornou algum registro
            if ($stmt->rowCount() > 0) {
                // retorna o primeiro registro
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("Nenhum registro encontrado!");
            }
        }

        public function selectAll() {

            $sql = 'SELECT 
            `id`,  
            `app_name`,  
            `title`, 
            `description`,  
            `enabled`,  
            `metric`,  
            `condition`,  
            `threshold` 
            FROM `'
            . self::$db_name . '`.`' . self::$table . "`" ;
     
            //Prepara a query    
            $stmt=$this->conn->prepare($sql);

            // executa a consulta
            $stmt->execute();

            // Verifica se a consulta retornou algum registro
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("Nenhum registro encontrado!");
            }
        }

        public function insert($data)
        {
            // echo "<br/>funcao insert \$data:<br/>";
            // var_dump($data);
            // echo "<br/>";


            $ql = 'INSERT INTO 
            `db_app`.`alerts` 
            (
                `id`, 
                `app_name`, 
                `title`, 
                `description`, 
                `enabled`, 
                `metric`, 
                `condition`, 
                `threshold`
            )
            VALUES (DEFAULT,
                :app_name,
                :title, 
                :description, 
                :enabled, 
                :metric, 
                :condition, 
                :threshold
            );';


            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindValue(':app_name', $data['app_name']);   
            $stmt->bindValue(':title', $data['title']);  
            $stmt->bindValue(':description', $data['description']);  
            $stmt->bindValue(':enabled', $data['enabled']);  
            $stmt->bindValue(':metric', $data['metric']);  
            $stmt->bindValue(':condition', $data['condition']);  
            $stmt->bindValue(':threshold', $data['threshold']);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Usuário(a) inserido com sucesso!';
            } else {
                throw new Exception("Falha ao inserir usuário(a)!");
            }
        }




        public static function deleteAlert($data)
        {
            try {
                $connPdo = new PDO(strval(DBDRIVE.':host='.DBHOST.';dbname='.DBNAME),strval(DBUSER), strval(DBPASS));

                //DELETE FROM `db_app`.`alerts` WHERE  `alert_id`=1;

            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

            
        }
    }