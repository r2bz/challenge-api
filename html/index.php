<?php
    header('Access-Control-Allow-Origin: *');
    // header('Content-Type: application/json');

    include_once "App/Services/AlertService.php";
    include_once "App/Services/MetricService.php";

    /* GET /alert -> Retorna uma lista de alertas
     * GET /alert/1 -> Retorna as configurações do alerta com id = 1
     * POST /alert -> Cria um alerta
     * PUT /alert/1 -> Atualiza completamente o alerta 1
     * PATCH /alert/1/enabled -> Atualiza parcialmente o alerta 1, campo enabled
     * DELETE /alert/1 -> Remove o alerta 1
     */

    // para a entrada api/alert/1 será formado um vetor com 3 posições após explodir $_GET['url']

    if ($_GET['url']) {
        $url = explode('/', $_GET['url']);

        // verifica se a url tem a string /api depois do endereço hospedado
        if ($url[0] === 'api' and sizeof($url)>1 ) {

            //$service = 'App\Services\\'.ucfirst($url[1]).'Service';
            $service = ucfirst($url[1]).'Service';

            $method = strtolower($_SERVER['REQUEST_METHOD']);



            // Remove o primeiro elment do array
            array_shift($url);
            array_shift($url);
            try {

                $response = call_user_func_array(array(new $service, $method), $url);
                
                http_response_code(200);
                echo json_encode(array('status' => 'sucess', 'data' => $response),JSON_UNESCAPED_UNICODE);

                exit;
            } catch (Exception $e) {
                http_response_code(404);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        
        }
    }
    ?>
