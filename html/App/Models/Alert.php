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


        // Adiciona um novo alerta à tabela de alertas
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


        /* Tamnha do array (size=5)
            0 => string 'api' (length=3)
            1 => string 'alert' (length=5)
            2 => string '1' (length=1)
            3 => string 'enabled' (length=7)
            4 => string '1' (length=1)
            */
        public function patch($data)
        {
            /* Verificar se o id existe, caso positivo realizar update com os demais registros
             * Realizar primeiro o get($id), depois definir os valores dos atributos da classe 
             * com os valores atuais. 
             */
            if(isset($data['id']) and ($data['id'] != ""))
            {
                $actualValues = $this->select( intval($data['id']) );
            }
            else{
                // Retornar erro solitando id
                throw new Exception("Falha ao atualizar alerta!. Favor informar id");
            }

            // Percorrer os valores de $actualValues


            // Definir atributos com os valores atuais para posteriormente compará-los
            // $this->title;
            // $this->description;
            // $this->enabled;
            // $this->metric;
            // $this->condition;
            // $this->threshold;

            // Query com todos os campos necessário para update
            $sql = 'UPDATE 
            `' . self::$db_name . '`.`' . self::$table . '
                SET
                app_name = :app
                title = :title
                description = :de
                enabled = :en
                metric = :me
                condition = :cond
                threshold = :th
                WHERE id = :id';
            
            $stmt = $this->conn->prepare($sql);

            // Apenas os campos informados no update (Diferente de vazio serão atualizados)
            (isset($data['app_name']) and ($data['app_name'] != "" ) ) ? $this->app_name=$data['app_name']:$this->app_name;
            

            $stmt->bindValue(':app', $data['app_name']);   
            $stmt->bindValue(':title', $data['title']);  
            $stmt->bindValue(':de', $data['description']);  
            $stmt->bindValue(':en', $data['enabled']);  
            $stmt->bindValue(':me', $data['metric']);  
            $stmt->bindValue(':cond', $data['condition']);  
            $stmt->bindValue(':th', $data['threshold']);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Alerta atualizado com sucesso!';
            } else {
                throw new Exception("Falha ao atualizar alerta!");
            }




        }


        // Deleta uma configuração de alerta de acordo com o id informado
        public  function delete($data)
        {
            
            //DELETE FROM `db_app`.`alerts` WHERE  `id`=1;

            if ($stmt->rowCount() > 0) {
                return 'Alerta inserido com sucesso!';
            } else {
                throw new Exception("Falha ao inserir alerta!");
            }

            
        }
    }