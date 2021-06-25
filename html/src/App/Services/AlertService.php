<?php
    namespace Api\App\Services;
    use Api\App\Models\Alert;
    use Api\Config\Database;
    use Api\Config\Log;


    /* Objetivos:
     * GET /alert -> Retorna uma lista de alertas
     * GET /alert/1 -> Retorna as configurações do alerta com id = 1
     * POST /alert -> Cria um alerta 
     * PUT /alert/1 -> Atualiza completamente o alerta 1 [TODO]
     * PATCH /alert/1/enabled/1 -> Atualiza parcialmente o alerta 1, campo enabled para o valor 1
     * DELETE /alert/1 -> Remove o alerta 1
     */

    class AlertService
    {   
        

        public function get($id = null) 
        {   
            // Log de acesso ao endpoint
            $log = new Log();
            $log->logGestaoDeAcesso("get(".$id.")","Realizada consulta ao ID: " . $id);
            
            if ($id) {
    
                /* Instancia um objeto da classe Alert 
                 * chama a função select do objeto instanciado
                 * passando como parametro um id da tabela alert
                 * retorna todos os registros em que o id for igual
                 */
                
                return (new Alert())->select($id); 
                
            } else {
                //Retorna todos os registros da tabela alerts
                
                $alerta = new Alert();
                $result = $alerta->selectAll();
                
                return $result;
                // return (new Alert())->selectAll();
            }
        }

        // Cria um alerta
        public function post() 
        {
            $log = new Log();
            $log->logGestaoDeAcesso("insert(\$_POST)","Tentativa de inserção de alerta");

            /* TODO: Utilizar o raw Post data 
             * $data = json_decode(file_get_contents("php://input"));
             */
            // usando a variável global post
            
            return (new Alert())->insert($_POST);
        }

        // Atualiza um campo de um alerta específico
        // PATCH api/alert/1/enabled/1 -> Atualiza parcialmente o alerta 1, campo enabled para o valor 1
        public function patch() 
        {   
            // Log de acesso ao endpoint
            $log = new Log();
            $log->logGestaoDeAcesso("patch(\$url)","Tentativa de update na tabela alerts.");

            $url = explode('/', $_GET['url']);
            return (new Alert())->patch($url);
        }

        
        public function delete() 
        {
            // Log de acesso ao endpoint
            $log = new Log();
            $log->logGestaoDeAcesso("delete(\$url)","Tentativa de delete na tabela alerts.");


            $url = explode('/', $_GET['url']);
            return (new Alert())->delete($url);

        }
    }

    
