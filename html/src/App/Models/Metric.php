<?php
    namespace Api\App\Models;

    use Api\Config\Database;
    use Api\Config\Log;


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
            
            // retorna uma conexão da instância da classe DB
            $this->conn = (new Database())->connect();
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
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum registro encontrado!");
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
    
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);

            } else {
                throw new \Exception("Nenhum registro encontrado!");
            }
            
        }

        

        // Adiciona um nova métrica/coleta à tabela de metricas
        public function insert($data)
        {
            /** A insersão só pode ser aceita se $_POST for um json válido 
             * se existe um par app_name + métrica na tabela de alertas
             * continua o fluxo, caso contrário retorna erro.
             */
            $valid_alert = $this->validation_metric($data);



            // Inserir métrica
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
            
            // count rows da inserção da métrica
            $inserted_metric = $stmt->rowCount();
            

            if ($stmt->rowCount() > 0) {
                $msg_return = 'Métrica inserida com sucesso!';
            
                // Verificar se a métrica deve gerar incidente
                if( $valid_alert['enabled'] == 1 ){
                    // Alerta ativo
                    
                    switch ($valid_alert['condition']) {
                        case '=':
                            $generate_incident = ($data['value'] == $valid_alert['threshold'])?true:false;
                            break;

                        case '>=':
                            $generate_incident = ($data['value'] >= $valid_alert['threshold'])?true:false; 
                            break;
                        
                        case '<=':
                            $generate_incident = ($data['value'] <= $valid_alert['threshold'])?true:false; 
                            break;
                        case '>':
                            $generate_incident = ($data['value'] > $valid_alert['threshold'])?true:false; 
                            break;
                        case '<':
                            $generate_incident = ($data['value'] > $valid_alert['threshold'])?true:false; 
                            break;
                        default:
                            // $generate_incident = $false;
                            break;
                    }

                    if($generate_incident){
                        // GERAR INCIDENTE
                        /**Instanciar a classe Incident e passar como parâmetro
                         * alert_id 
                         * $valid_alert['id']
                         * retorno é bolean
                         */
                        $return_gen_incident = (new Incident)->insert($valid_alert['id']);
                    }



                }else {
                    // Alerta existe, mas está desabilitado
                    
                    // GERA LOG informando que Inserção da métrica não gerou incidente
                    $log = new Log();
                    $log->logGestaoDeIncidentes(2);
                }


                if ($generate_incident and $return_gen_incident ){
                    return $msg_return . '- Registrado Incidente!';
                }
                return $msg_return;

            } else {
                throw new \Exception("Falha ao inserir métrica!");
            }
        }

        
        /**
         * Validação do array de insert
         * @param Array $data é um array com todos os campos conforme esperado 
         * @return Array retorna um array com uma linha da tabela alerts 
         * caso tenha encontrado um alerta associado à métrica passada como parâmetro 
         */
        public function validation_metric($data)
        {
        
            // se não for um json válido retorna null
            $insert_metric = json_encode($data);

            if ($insert_metric) {
                // É um json válido

                /** Verifica se existe um alerta cadastrado (par de appName + metricName) na tabela de alertas
                * o alerta do par appname+metricname da métrica em foco
                */
                $sql =  'SELECT a.id,a.app_name,a.title,a.enabled,a.metric,a.`condition`,a.threshold 
                    FROM alerts a
                    WHERE
                        a.app_name = :app
                        AND a.metric = :mn ;';

                //Prepara a query    
                $stmt=$this->conn->prepare($sql);
                $stmt->bindValue(':app', $data['appName']);
                $stmt->bindValue(':mn', $data['metricName']);

                // executa a consulta
                $stmt->execute();

                
                // Verifica se a consulta retornou algum registro
                if ($stmt->rowCount() > 0) {
                    // validar os demais dados
                    if (is_numeric($data['value']) ) {
                        return $stmt->fetch(\PDO::FETCH_ASSOC);
                    }

                    throw new \Exception("A métrica não foi registrada. Favor informar um valor numérico");


                } else {
                    $log = new \Log();
                    // GERA LOG informando que não existe correspondência de alerta para a métrica
                    $log->logGestaoDeIncidentes(1);
                    // GERA LOG informando que Inserção da métrica não gerou incidente
                    $log->logGestaoDeIncidentes(2);
                    
                    throw new \Exception("Não existe configuração de Alerta para a métrica! A métrica não foi registrada.");
                }


            }else{
                // entrada de métrica não é um json válido
                throw new \Exception("Falha ao inserir métrica! Entrada de métrica não é um json válido.");
            }

        }






        public  function delete($data)
        {
            
            // DELETE FROM `db_app`.`metrics` WHERE `id`=1;
            
        }



    }

