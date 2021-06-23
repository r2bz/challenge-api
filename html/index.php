<?php
    header('Content-Type: application/json');

    //require_once "App/Services/UserService.php";
    require_once "App/Services/AlertService.php";
    require_once "App/Services/MetricService.php";
    

    // api/users/1
    if ($_GET['url']) {
        $url = explode('/', $_GET['url']);

        if ($url[0] === 'api' and sizeof($url)>1 ) {

            //$service = 'App\Services\\'.ucfirst($url[1]).'Service';
            $service = ucfirst($url[1]).'Service';

            $method = strtolower($_SERVER['REQUEST_METHOD']);
            
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
