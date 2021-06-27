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
        public function query_incident_by_metric() {

            $sql = 'SELECT  
                a.metric, 
                i.alert_id, 
                COUNT(*) as `count`
                FROM ' . self::$db_name . '.' . self::$tb_incidents . ' i 
                INNER JOIN ' . self::$db_name . '.' . self::$tb_alerts . ' a 
                    ON i.alert_id = a.id
                GROUP BY a.metric, i.alert_id 
                ORDER BY a.metric';
                   
            //Prepara a query    
            $stmt = $this->conn->prepare($sql);
            // executa a consulta
            $stmt->execute();

            // retorna o objeto stmt
            return $stmt;

            // // Verifica se a consulta retornou algum registro
            // if ($stmt->rowCount() > 0) {
 
                
            //     // return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            // } else {
            //     throw new \Exception("Nenhum registro encontrado!");
            // }
        }



        /**
         * Retorna a quantidade de alertas habilitados e desabilitados 
         */
        public function query_alert_enabled(){
            $sql = 'SELECT  
                `enabled` ,  
                COUNT(*) as `count`
                FROM ' . self::$db_name . '.' . self::$tb_alerts . ' a 
                GROUP BY enabled
                ORDER BY enabled desc';


            //Prepara a query    
            $stmt = $this->conn->prepare($sql);
            // executa a consulta
            $stmt->execute();



            // retorna o objeto stmt
            return $stmt;
        }



        /**
         * quantidade de incidentes gerados agrupados por APP_NAME
         */
        public function query_incident_by_app_name(){
            
            $sql = 'SELECT  
                a.app_name, 
                a.enabled, 
                COUNT(*) as `count`
                FROM ' . self::$db_name . '.' . self::$tb_incidents . ' i 
                INNER JOIN ' . self::$db_name . '.' . self::$tb_alerts . ' a 
                    ON i.alert_id = a.id
                GROUP BY a.app_name, a.enabled 
                ORDER BY a.app_name';

            //Prepara a query    
            $stmt = $this->conn->prepare($sql);
            // executa a consulta
            $stmt->execute();

            // retorna o objeto stmt
            return $stmt;
        }

        /**
         * Esta função vai concentrar as chamadas dos demais reports
         */
        public function report() {
            // quantidade de registros da tabela de incidentes, agregado pelo nome da propria métrica
            $arr_incident_by_metric = $this->stmt_to_array_incident_by_metric($this->query_incident_by_metric() ) ;
            if ( sizeof($arr_incident_by_metric) > 0 ) {
                // Se retornar um array com pelo menos 1 elemento
                $this->print_array_incident_by_metric ($arr_incident_by_metric);

            } else {
                // Não foi encontrado nenhum incidente para agrupar por nome de métrica
                echo "Não foi encontrado nenhum incidente para agrupar por nome de métrica.";
            }


            $arr_alert_enabled = $this->stmt_to_array_alert_enabled( $this->query_alert_enabled() );
            $this->print_array_alert_enabled($arr_alert_enabled);


            $arr_incident_by_app_name = $this->stmt_to_array_incident_by_app_name($this->query_incident_by_app_name());
            $this->print_array_incident_by_app_name($arr_incident_by_app_name);

            return true;
        }


        public function stmt_to_array_incident_by_metric($stmt) {
            // retorna o número de linhas afetadas da execução do select
            $num = $stmt->rowCount();
            // Estrutura de Array para receber o retorno da consulta
            $report_arr = array();
            $report_arr['data'] = array();
            // Se o número de linhas afetadas da consulta for maior que 0
            if($num > 0) {
                    // enquanto houver linhas retornadas da consulta extraia uma e insira no array
                    while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                        extract($row);
                        $report_item = array(
                            'metric' => $metric,
                            'alert_id' => $alert_id,
                            'count' => $count
                        );
                        // Adiciona o array report_item ao final do array report_arr "data"
                        array_push($report_arr['data'], $report_item);
                    }
            } 
            return $report_arr['data'];
        }
        

        /**
         * Formatação de acordo como solicitado
         */
        public function print_array_incident_by_metric ($array) {
            $tb_th=$array[0]['metric'];
            echo "<br/><br/># ". $tb_th;
            foreach ($array as $key => $row_query) {
                echo "<br/>".$key." - ". $array[$key]['metric']."{alert_id=". $array[$key]['alert_id']."} ".$array[$key]['count'];
                if ($tb_th != $array[($key+1)]['metric']) {
                    $tb_th = $array[($key+1)]['metric'];
                    if ($array[($key+1)]['metric'] != null ) {
                        echo ("<br/><br/># ". $array[$key+1]['metric']);
                     }else{
                         echo ("<br/><br/>"); 
                     }
                }
            }
        } 



        public function stmt_to_array_alert_enabled($stmt) {
            // retorna o número de linhas afetadas da execução do select
            $num = $stmt->rowCount();

            // Estrutura de Array para receber o retorno da consulta
            $report_arr = array();
            $report_arr['data'] = array();

            // Se o número de linhas afetadas da consulta for maior que 0
            if($num > 0) {

                    // enquanto houver linhas retornadas da consulta extraia uma e insira no array
                    while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                        extract($row);

                        $report_item = array(
                            'enabled' => $enabled,
                            'count' => $count
                        );

                        // Adiciona o array report_item ao final do array report_arr "data"
                        array_push($report_arr['data'], $report_item);
                    }
            } 
            return $report_arr['data'];
        }


         /**
         * Formatação de acordo com o solicitado
         */
        public function print_array_alert_enabled ($array) {
            echo "<br/><br/># alerts-enabled";

            foreach ($array as $key => $row_query) {
                $str1 = "<br/>alerts{enabled=";
                if($array[$key]['enabled']==="0"){ $str2 = "false";} else{ $str2 = "true"; }
                $str3 = "} ". $array[$key]['count'];
                echo $str1.$str2.$str3;
            }
            echo "<br/>";
        } 




        public function stmt_to_array_incident_by_app_name ($stmt) {
            // retorna o número de linhas afetadas da execução do select
            $num = $stmt->rowCount();
            // Estrutura de Array para receber o retorno da consulta
            $report_arr = array();
            $report_arr['data'] = array();
            // Se o número de linhas afetadas da consulta for maior que 0
            if($num > 0) {
                    // enquanto houver linhas retornadas da consulta extraia uma e insira no array
                    while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                        extract($row);
                        $report_item = array(
                            'app_ame' => $app_name,
                            'enabled' => $enabled,
                            'count' => $count
                        );
                        // Adiciona o array report_item ao final do array report_arr "data"
                        array_push($report_arr['data'], $report_item);
                    }
            } 
            return $report_arr['data'];
        }


         /**
         * Formatação de acordo com o solicitado
         */
        public function print_array_incident_by_app_name ($array) {
            echo "<br/><br/># app-name-incidents-qtd";
            foreach ($array as $key => $row_query) {
                $str1 = '<br/>app-name-incidents-qtd{app-name="';
                $str2 = $array[$key]['app_ame'];
                $str3 = ', enabled=';
                if($array[$key]['enabled']==="0"){ $str4 = "false";} else{ $str4 = "true"; }
                $str5 = '"} '. $array[$key]['count'];
                echo $str1.$str2.$str3.$str4.$str5;
            }
            echo "<br/>";
        } 



    }
    

 
?>