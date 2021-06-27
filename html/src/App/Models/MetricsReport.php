<?php
    namespace Api\App\Models;

    use Api\Config\Database;

    // ENDPOINT metrics/
    
    /**
     * Endpoint para Resumos sobre incidentes e alertas
     * 
     * Este endpoint exibirá
     * quantidade de registros da tabela de incidentes, agregado pelo nome da propria métrica
     * quantidade de alertas habilitados e desabilitados 
     * quantidade de incidentes gerados agrupados por APP_NAME
     */
    class MetricsReport 
    {
        // Atributos com relação a base
        private $conn;
        private static $db_name = 'db_app';
        private static $tb_incidents = 'incidents';
        private static $tb_alerts = 'alerts';
        private static $tb_metrics = 'metrics';


        
        
        public function __construct(){
            // retorna uma conexão através de uma instância da classe Database
            $this->conn = (new Database())->connect();
        }


        /**
         ** Esta função retorna a 
         * quantidade de registros da tabela de incidentes, agregado pelo nome da propria métrica
         */
        public function incident_by_metric() {

            $sql = 'SELECT  
                a.metric, 
                i.alert_id, 
                COUNT(*)
                FROM ' 
                . self::$db_name . '.' . self::$tb_incidents . ' i
                INNER JOIN ' . $self::$db_name . '.' . self::$tb_alerts . ' a 
                    ON i.alert_id = a.id
                GROUP BY a.metric, i.alert_id';
                   
            //Prepara a query    
            $stmt = $this->conn->prepare($sql);
            // executa a consulta
            $stmt->execute();

            // Verifica se a consulta retornou algum registro
            if ($stmt->rowCount() > 0) {
 
                // retorna o primeiro registro
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum registro encontrado!");
            }
        }

        public function alert_enabled(){

        }

        public function list_array($return_fetchall) {
            // Exibir em HTML ou JSON?

        }


        public function report() {
            // quantidade de registros da tabela de incidentes, agregado pelo nome da propria métrica
            list_array( $this->incidents_by_metric() );

            list_array($this->alert_enabled());
        }
        
    }
    

 
?>