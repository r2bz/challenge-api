<?php
//Multiline error log class
//For break use "\n" instead '\n'

Class Log {
  
  public static $log_file =  "./Logs/noincidents.log";

  /*
   Escreve no log métrica passada para a API mas não gerou incidente
   • A API deve gerar os logs no formato json_inline (onde cada linha representa um objeto JSON)
    e deverá ser gerado uma mensagem de log todas as vezes que este endpoint de recebimento de métricas
     for chamado
   Inserção da métrica não gerou incidente
  */
    public function logGestaoDeAlertas($msg)
    {   
        $logNoIncident = [
            'date' => strval(date('d.m.Y h:i:s')),
            'msg' => 'Inserção da métrica não gerou incidente',
            'code' => '01',
            'url' => $_SERVER['REQUEST_URI'],
            'data' => $msg,
            
            ];
        //open file to append line 
        $file = fopen(self::$log_file,'a'); 
        if ($file == false) die('Não foi possível criar o arquivo.');
        echo json_encode(array('date' => strval(date('d.m.Y h:i:s')) , 'status' => 'sucess', 'data' => $msg ) )."\n";
        $textjson = json_encode($msg)."\n";
        fwrite($file, $textjson);
        fclose($file);


        $date = date('d.m.Y h:i:s');
        $log = $msg."   |  Date:  ".$date."  |  User:  ".$username."\n";
        error_log($log, 3, self::USER_ERROR_DIR);

        // Armazenar em arquivo como json inline
        $connPdo = new PDO($dsn,$dbuser, $dbpass);
        $sql = 'SELECT * FROM `db_app`.`alerts`;';
        $stmt = $connPdo->prepare($sql);
        $stmt->execute();
        $rows=$stmt->rowCount();
        echo ("<br>RETORNOU ".$rows. " registros <BR>");
        //$retorno = array();
        $retorno = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($retorno as $row => $column) {
            echo  'linha: ['. $row."]=> {coluna id => ". $column['id'].'}, {coluna app_Name: ".' . $column['app_name']. '}</br>';
            }
        $stmt=null;
        $sql=null;
        $connPdo=null;
        $retorno=null;

    }
    /*
   General Errors...
  */
    public function general($msg)
    {
    $date = date('d.m.Y h:i:s');
    $log = $msg."   |  Date:  ".$date."\n";
    error_log($msg."   |  Tarih:  ".$date, 3, self::GENERAL_ERROR_DIR);
    }

  
 

}

$log = new log();
$log->user($msg,$username); //use for user errors
//$log->general($msg); //use for general errors
?>