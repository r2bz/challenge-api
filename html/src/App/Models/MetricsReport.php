<?php
namespace Api\App\Models;

// ENDPOINT metrics/
//
/**
 * Verifica se existe um alerta cadastrado (par de appName + metricName) na tabela de alertas
 * 
 * select mais interno retorna apenas os alertas com o mesmo par appname e metricname
 * select mais externo filtra dos alertas retornados apenas 
 * o alerta do par appname+metricname da métrica em foco
 * 
 * SELECT tb.id,tb.app_name,tb.title,tb.enabled,tb.metric,tb.`condition`,tb.threshold FROM 
 * (SELECT a.id,a.app_name,a.title,a.enabled,a.metric,a.`condition`,a.threshold 
 *      FROM alerts a
 *      INNER JOIN metrics m 
 *  	ON a.app_name = m.appName 
 *      AND a.metric = m.metricName) tb
 *  	WHERE tb.metric = $insert[metricName]
 */
    class MetricsReport 
    {
        
    }
    

 
?>