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
            
            // $sql = 'SELECT `id`, `sampletime`, `metricName`, `appName`, `value` FROM ' . self::$db_name . '.' . self::$table . ' WHERE `id` = :id ;';
            $sql = 'SELECT 
                `id`, 
                `sampletime`, 
                `metricName`, 
                `appName`, 
                `value` 
                FROM ' 
                . self::$db_name . '.' . self::$table . 
                ' WHERE 
                `id` = :id ;';

                   
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
            
            $sql = 'SELECT 
                `id`, 
                `sampletime`, 
                `metricName`, 
                `appName`, 
                `value`
                FROM 
                '.self::$db_name.'.'.self::$table.';';

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

        // ENDPOINT metrics/
        //
        /**
         * select mais interno retorna apenas os alertas com o mesmo par appname e metricname
         * select mais externo filtra dos alertas retornados apenas 
         * o alerta do par appname+metricname da métrica em foco
         * 
         * SELECT tb.id,tb.app_name,tb.title,tb.enabled,tb.metric,tb.`condition`,tb.threshold FROM 
         * (SELECT a.id,a.app_name,a.title,a.enabled,a.metric,a.`condition`,a.threshold 
         *      FROM alerts a
         *      INNER JOIN metrics m 
         *  	ON a.app_name = m.appName 
         *      AND a.metric = m.metricName) tb
         *  	WHERE tb.metric = 'response_time'
         */


        

        // Adiciona um nova métrica/coleta à tabela de metricas
        public function insert($data)
        {
            /* TODO: a insersão só pode ser aceita se $_POST for um json válido
             * verificar se existe um par app_name + métrica já existente na tabela de alertas
             *  antes de inserir
             */
            

            // Retornar array com os app_names iguais
            // Verificar se algum deles já tem a metric que está sendo inserida
            // Se existir, impedir de inserir e retornar erro
            // `sampletime`,
            $sql = 'INSERT INTO 
            `' . self::$db_name . '`.`' . self::$table . '` 
            ( 
                id,
                sampletime,
                `metricName`, 
                `appName`, 
                `value`
                ) 
            VALUES ( DEFAULT, NOW(), :metricn, :appn, :val );';
            
            
            $stmt = $this->conn->prepare($sql);


            $stmt->bindValue(':metricn', $data['metricName']);   
            $stmt->bindValue(':appn', $data['appName']);  
            $stmt->bindValue(':val', $data['value']);  
            
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Métrica inserida com sucesso!';
            } else {
                throw new Exception("Falha ao inserir métrica!");
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

