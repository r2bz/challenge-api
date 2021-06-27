<?php
    namespace Api\App\Services;
    use Api\App\Models\Metric;
    use Api\App\Models\Incident;

    // require_once "./App/Models/Metric.php";
    // require_once "./App/Models/Incident.php";


    class ReceiveService
    {
        public function get($id = null) 
        {
            if ($id) {

                return (new Metric())->select($id); 
                
            } else {
                
                return (new Metric())->selectAll();
                
            }
        }

        public function post() 
        {
            // Forma antiga
            // $data = $_POST;

            // Exemplo de json esperado
            // {"metricName":"response_time","appName":"ms-system-01","value":"2"}

            

            // Obtando dados brutos postados
            $data = file_get_contents("php://input");


            return (new Metric())->insert($data);
            
        }





        // Atualiza um campo de uma métrica específica
        public function patch() 
        {   
            // Não foi solicitada a implementação
        }

        // Deleta uma métrica
        public function delete() 
        {
            // Não foi solicitada a implementação

        }


    }
