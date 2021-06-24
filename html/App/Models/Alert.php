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
                // os atributos da instância são atualizados
                $this->id = $actualValues['id'];
                $this->app_name = $actualValues['app_name'];
                $this->title = $actualValues['title'];
                $this->description = $actualValues['description'];
                $this->metric = $actualValues['metric'];
                $this->condition = $actualValues['condition'];
                $this->threshold = $actualValues['threshold'];

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


        /* 
            PATCH /alert/1/enabled/1 -> Atualiza parcialmente o alerta 1, campo enabled para o valor 1
            0 => string 'api'       - para acessar a api
            1 => string 'alert'     - para acessar interface com alert
            2 => string '1'         - id de alert a ser atualizado
            3 => string 'enabled'   - campo a ser atualizado
            4 => string '1'         - valor do campo a ser alterado
            */
        public function patch($data)
        {

            /* Verificar se o id existe, caso positivo realizar update com os demais registros
             * Realizar primeiro o get($id), depois definir os valores dos atributos da classe 
             * com os valores atuais. 
             */
            // verifica se os parmetros passados na url sao para alert e se o id do registro
            // que deseja ser alterado é numérico
            
            if( is_numeric($data[2]) ) {
                // exemplo de entrada válida:  $data[2] => string '1' 
                // representa o id da tabela alerts a ser alterado
                
                if ( isset($data[3]) ) {
                    // exemplo de entrada válida:  $data[3] => string 'enabled' 
                    // representa o campo da tabela a ser alterado
                    
                    if( isset($data[4]) ){

                        // exemplo de entrada válida: $data[4] => string '1'
                        // representa o novo valor a ser alterado

                        // verificar se o id existe na tabela
                        $actualValues = $this->select( intval($data[2]) );
                        
                        // id existe ?
                        if ( $actualValues ) {
                            
                            $this->id = $actualValues['id'];
                            $this->app_name = $actualValues['app_name'];
                            $this->title = $actualValues['title'];
                            $this->description = $actualValues['description'];
                            $this->enabled = $actualValues['enabled'];
                            $this->metric = $actualValues['metric'];
                            $this->condition = $actualValues['condition'];
                            $this->threshold = $actualValues['threshold'];

                            if($this->enabled == $data[4]){
                                return array('message' => "Não foi necessário atualizar o valor. Valor fornecido igual ao existente.");
                            }
                            
                        }
                        else {
                            throw new Exception("Falha ao atualizar alerta! Id não existe na tabela alert.");
                        }                        

                        switch ($data[3]) {
                            
                            case 'enabled':
                                
                                if( is_numeric( $data[4] ) and ( $data[4] == 1 or $data[4] == 0 ) ){
                                    
                                    // update do campo enable para o id $data[2] com o valor $data[4])
                                    
                                    $sql = 'UPDATE ' . self::$table . ' SET enabled = :en WHERE id = :id';
                                    
                                    $stmt = $this->conn->prepare($sql);
                                    $stmt->bindValue(':id', (int)$data[2]); 
                                    $stmt->bindValue(':en', (int)$data[4]); 
                                    
                                    $stmt->execute();


                                    if ($stmt->rowCount() > 0) {
                                        return 'Alerta atualizado com sucesso!';
                                    } else {
                                        throw new Exception("Falha ao atualizar alerta!");
                                    }
                                }
                                else {
                                    throw new Exception("Falha ao atualizar alerta! Favor informar ou 0 ou 1 para o campo enable.");
                                }

                                break;
                            
                            default:
                                // Implementado por enquanto apenas enabled
                                break;
                        }
                        
                    }
                    else{
                        throw new Exception("Falha ao atualizar alerta!. Favor informar o novo valor do campo a ser alterado.");
                    }
                }
                else{
                    throw new Exception("Falha ao atualizar alerta!. Favor informar o campo a ser atualizado.");
                }
            }
            else{
                throw new Exception("Falha ao atualizar alerta!. Favor informar o id do alerta a ser atualizado.");
            }


        }



        /*
            Deleta uma configuração de alerta de acordo com o id informado
            Exemplo:
            DELETE /alert/1 -> Remove o alerta com id = 1

            Array esperado:
            0 => string 'api'       - para acessar a api
            1 => string 'alert'     - para acessar interface com alert
            2 => string '1'         - id de alert a ser deletado
        */
        public  function delete($data)
        {

            // verifica se os parmetros passados na url estão em conformidade
            
            if( is_numeric($data[2]) ) {
                // exemplo de entrada válida:  $data[2] => string '1' 
                // representa o id da tabela alerts a ser deletado
                
               
                // verificar se o id existe na tabela
                $actualValues = $this->select( intval($data[2]) );
                
                // id existe ?
                if ( $actualValues ) {
                    
                    //DELETE FROM `db_app`.`alerts` WHERE  `id`=1;
                    $sql = 'DELETE FROM ' . self::$table . ' WHERE id = :id';
                            
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(':id', (int)$data[2]); 
                    
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return 'Alerta deletado com sucesso!';
                    } else {
                        throw new Exception("Falha ao deletar alerta!");
                    }
                            
                }
                else {
                    throw new Exception("Falha ao deletar alerta! Id não existe na tabela alert.");
                }                        
               
            }
            else{
                throw new Exception("Falha ao deletar alerta!. Favor informar o id do alerta a ser atualizado.");
            }
            
        }
    }