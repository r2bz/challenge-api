<?php
    namespace Api\App\Services;
    use Api\App\Models\Health;

    // require_once "./App/Models/Health.php";


    class HealthService
    {
        public function get($id = null) 
        {
            
                return (new Health())->selectAll();
            
        }

        public function post() 
        {

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
