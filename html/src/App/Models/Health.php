<?php
    namespace Api\App\Models;

    use Api\Config\Database;

    class Health
    {

        // public function __construct(){
        //     // retorna uma conexão da da instância da classe DB
        //     $this->conn = (new Database())->connect();
        // }


        public function selectAll() {
            
            $db = new Database;
            echo ($db->get_health_mysql());

        }





    
    
    
    }
    