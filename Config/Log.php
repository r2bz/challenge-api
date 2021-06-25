<?php
//Multiline error log class
//For break use "\n" instead '\n'

/** A classe Log será utilizada pelos endpoints da API
 * gera o logs no formato json_inline 
 * (onde cada linha representa um objeto JSON)   
*/
Class Log {
  
  public static $log_file =  "./Logs/LogAPI.log";

  /** 
  * Função para gravar logs dos endpoints de gestao de alertas
  * gera uma mensagem de log todas as vezes 
  * que o endpoints de gestão de alertas forem chamados.
  * @access public 
  * @param String $fuctioncalled funcao chamada
  * @param String $msg retorno ou status em que a funcao chamada
  * @return void 
  */ 
  public function logGestaoDeAcesso($fuctioncalled,$msg )
  {   

    //abre o arquivo adicionando linha ao final
    try {
      $file = fopen(self::$log_file,'a'); 
    
    } catch (\Throwable $th) {
      throw new Exception('Não foi possível criar o arquivo.'. $th->getMessage());
    }
    
    $log = [
      'date' => strval(date('Y-m-d h:i:s')),
      'method' => $_SERVER['REQUEST_METHOD'],
      'url' => $_GET['url'],
      'fuctioncalled' => $fuctioncalled,
      'msg' => $msg
      ];

    
    $textencoded = json_encode($log)."\n";

    
    fwrite($file, $textencoded);
    fclose($file);

  }



  // Gestão de Incidentes
  /** Sua API deverá criar um registro na tabela de incidentes 
   * que possuí a seguinte estrutura 
   * (caso não atenda a condição ou o alerta esteja como desabilitado,
   *  deverá ignorar a criação do registro na tabela e 
   * informar nos logs):    
   * -----
   * Inserção da métrica não gerou incidente
   * ------
  */
  /** 
  * Função para gravar logs dos endpoints de gestao de Incidentes
  * @access public 
  * @return void 
  */ 
  public function logGestaoDeIncidentes($arg )
  { 
    /**
   * Escreve no log "Inserção da métrica não gerou incidente" quando a nova métrica recebida pela 
   * API não gerar incidente
   * (caso não atenda a condição ou o alerta esteja como desabilitado, 
   * deverá ignorar a criação do registro na tabela de incidentes e informar nos logs)
  */
    //abre o arquivo adicionando linha ao final
    try {
      $file = fopen(self::$log_file_gest_incidentes,'a'); 
    
    } catch (\Throwable $th) {
      throw new Exception('Não foi possível acessar o arquivo de log.'. $th->getMessage());
    }
    
    switch ($arg) {
      
      // Não existe correspondência de um alerta para a métrica de entrada
      case '1':   
        $log = [
          'date' => strval(date('Y-m-d h:i:s')),
          'method' => $_SERVER['REQUEST_METHOD'],
          'url' => $_GET['url'],
          'msg' => 'Inserção da métrica não gerou incidente. - Não existe correspondência de um alerta para a métrica de entrada.'
          ];
        break;
        
        //Inserção da métrica não gerou incidente
      case '2':
        $log = [
          'date' => strval(date('Y-m-d h:i:s')),
          'method' => $_SERVER['REQUEST_METHOD'],
          'url' => $_GET['url'],
          'msg' => 'Inserção da métrica não gerou incidente. - Alerta existe, mas está desabilitado.'
          ];
        break;
        case '3':
          $log = [
            'date' => strval(date('Y-m-d h:i:s')),
            'method' => $_SERVER['REQUEST_METHOD'],
            'url' => $_GET['url'],
            'msg' => 'Inserção da métrica não gerou incidente. '
            ];
          break;
      
      // Mensagem personalizada
      default:    
      $log = [
        'date' => strval(date('Y-m-d h:i:s')),
        'method' => $_SERVER['REQUEST_METHOD'],
        'url' => $_GET['url'],
        'msg' => $arg
        ];

        break;
    }


    
    $textencoded = json_encode($log)."\n";

    
    fwrite($file, $textencoded);
    fclose($file);


  }  


}


?>