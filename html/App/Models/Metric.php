<?php
    //namespace App\Models;

    include_once "../Config/Database.php";

    class Metric
    {
        // Atributos com relação a base
        private $conn;
        private static $db_name = 'db_app';
        private static $table = 'metrics';

        // Propriedades das metricas
        public $id;
        public $sampletime;
        public $metricName;
        public $appName;
        
        
        public function __construct(){
            
            // retorna uma conexão da da instância da classe DB
            $this->conn = (new Database())->connect();
            
            // // DEBUG
            // echo "<br/>construct de metric:" ;
            // var_dump($this->conn);
            // var_dump($this->conn->getAttribute(PDO::ATTR_CONNECTION_STATUS));
            // echo "<br/>";
            // // DEBUG
        }


        public function select(int $id) {
            
            // $sql = 'SELECT `id`, `sampletime`, `metricName`, `appName`, `value` FROM `metrics` WHERE `id` = 1';

            $sql = 'SELECT * FROM `db_app`.`metrics` WHERE  `id`=:id';
            

                   
            //Prepara a query    
            $stmt = $this->conn->prepare($sql);
                
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
            
            $sql = 'SELECT * FROM '.self::$db_name.'.'.self::$table;
            
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

        // 


        

        // Adiciona um novo alerta à tabela de metricas
        public function insert($data)
        {
            /* TODO: a insersão só pode ser aceita se $_POST for um json válido
             * verificar se existe um par app_name + métrica já existente antes de inserir
             */
            

            // Retornar array com os app_names iguais
            // Verificar se algum deles já tem a metric que está sendo inserida
            // Se existir, impedir de inserir e retornar erro

            $sql = 'INSERT INTO 
            `' . self::$db_name . '`.`' . self::$table . 
            '` ( 
                `app_name`, 
                `title`, 
                `description`, 
                `enabled`, 
                `metric`, 
                `condition`, 
                `threshold`
                ) 
            VALUES ( :app, :title, :de , :en, :me, :cond, :th );';
            
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':app', $data['app_name']);   
            $stmt->bindValue(':title', $data['title']);  
            $stmt->bindValue(':de', $data['description']);  
            $stmt->bindValue(':en', $data['enabled']);  
            $stmt->bindValue(':me', $data['metric']);  
            $stmt->bindValue(':cond', $data['condition']);  
            $stmt->bindValue(':th', $data['threshold']);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Alerta inserido com sucesso!';
            } else {
                throw new Exception("Falha ao inserir alerta!");
            }
        }

        
        /**
         * Validação do array de insert
         * Se $data é um array com todos os campos conforme esperado 
         */
        public function validation($data)
        {
            
            // if (isset($data)) {
                # code...
            // }

        }






        public  function delete($data)
        {
            
            // DELETE FROM `db_app`.`metrics` WHERE `id`=1;
            
        }



    }

