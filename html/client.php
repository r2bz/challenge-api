<?php

    $url = 'http://localhost/api';

    $class = '/alert';
    $param = '';

    $response = file_get_contents($url.$class.$param);

    echo $response;
    
    // $response = json_decode($response, 1);
    // var_dump($response['data'][1]['email']);