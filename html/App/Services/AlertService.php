<?php
    //namespace App\Services;
    //use App\Models\User;

    include_once "./App/Models/Alert.php";



    class AlertService
    {   
        
        public function get($id = null) 
        {   

            
            if ($id) {
    
                /* Instancia um objeto da classe Alert 
                 * chama a função select do objeto instanciado
                 * passando como parametro um id da tabela alert
                 * retorna todos os registros em que o id for igual
                 */
                return (new Alert())->select($id); 
                
            } else {
                //Retorna todos os registros da tabela alerts
                return (new Alert())->selectAll();
            }
        }


        public function post() 
        {

            //DEBUGING
            return $_POST;
            /* TODO: Utilizar o raw Post data 
             * $data = json_decode(file_get_contents("php://input"));
             */
            // usando a variável global post
            // $data = $_POST;

            
            // return (new Alert())->insert($data);
        }

        public function update() 
        {
            
        }

        public function delete() 
        {
            
        }
    }