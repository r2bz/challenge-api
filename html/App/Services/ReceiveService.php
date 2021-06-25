<?php
    //namespace App\Services;
    //use App\Models\Metric;

    require_once "./App/Models/Metric.php";
    require_once "./App/Models/Incident.php";


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
            $data = $_POST;

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
