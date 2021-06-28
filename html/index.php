<?php
    header('Access-Control-Allow-Origin: *');
    // header('Content-Type: application/json');

    use Api\App\Services\AlertService;
    use Api\App\Services\ReceiveService;
    use Api\App\Services\MetricsService;
    use Api\App\Services\HealthService;
    

    require_once realpath("vendor/autoload.php");



    /** ENDPOINT api/alert
     *  GET /alert -> Retorna uma lista de alertas
     * GET /alert/1 -> Retorna as configurações do alerta com id = 1
     * POST /alert -> Cria um alerta
     * PUT /alert/1 -> Atualiza completamente o alerta 1 [Não implementado]
     * PATCH /alert/1/enabled -> Atualiza parcialmente o alerta 1, campo enabled
     * DELETE /alert/1 -> Remove o alerta 1
     */
    
     /** ENDPOINT api/receive
     *  GET /receive -> Retorna todas as métricas
     * GET /receive/1 -> Retorna a métrica com id = 1
     * POST /receive -> Recebe uma métrica e insere na base
     * PUT /receive/1 ->            Não implementado
     * PATCH /receive/1/appName ->  Não implementado
     * DELETE /receive/1 ->         Não implementado
     */

     /** ENDPOINT api/metrics
      * Report com o resumos sobre métricas e incidentes
      *  GET /receive -> Retorna todas as métricas
      * GET /receive/1 -> Retorna a métrica com id = 1
      * POST /receive -> Recebe uma métrica e insere na base
      * PUT /receive/1 ->            Não implementado
      * PATCH /receive/1/appName ->  Não implementado
      * DELETE /receive/1 ->         Não implementado
      */
    
     /** ENDPOINT api/health
     *  GET /receive -> Retorna todas as métricas
     * GET /receive/1 -> Retorna a métrica com id = 1
     * POST /receive -> Recebe uma métrica e insere na base
     * PUT /receive/1 ->            Não implementado
     * PATCH /receive/1/appName ->  Não implementado
     * DELETE /receive/1 ->         Não implementado
     */

    
    // será formado um vetor com 3 posições após explodir $_GET['url']
    if ($_GET['url']) {
        $url = explode('/', $_GET['url']);

        // verifica se a url tem a string /api depois do endereço hospedado
        if ($url[0] === 'api' and sizeof($url)>1 ) {

            $service = 'Api\App\Services\\'.ucfirst($url[1]).'Service';
            // $service = ucfirst($url[1]).'Service';

            $method = strtolower($_SERVER['REQUEST_METHOD']);

            // Remove o primeiro elmento do array
            array_shift($url);
            // Remove o segundo elmento do array
            array_shift($url);

            try {

                $response = call_user_func_array(array(new $service, $method), $url);
                
                http_response_code(200);
                echo json_encode(array('status' => 'sucess', 'data' => $response),JSON_UNESCAPED_UNICODE);

                exit;
            } catch (\Exception $e) {
                http_response_code(404);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        
        }
    }
    ?>
