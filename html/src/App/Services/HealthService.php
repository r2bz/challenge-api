<?php
    namespace Api\App\Services;
    use Api\App\Models\Health;

    // require_once "./App/Models/Health.php";


    class HealthService
    {
        public function get($id = null) 
        {
            if ($id) {
                return (new Health())->select($id); 
                
            } else {
                return (new Health())->selectAll();
                
            }
        }

        public function post() 
        {
            $data = $_POST;
            return (new Health())->insert($data);
            
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
