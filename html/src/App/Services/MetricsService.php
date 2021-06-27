<?php
    namespace Api\App\Services;
    use Api\App\Models\MetricsReport;


    /**
     * Endpoint api/metrics 
     * Exposição de Métricas 
     * responsável por extenalizar as métricas 
     * quantidade de registros da tabela de incidentes, agregado pelo nome da propria métrica
     * quantidade de alertas habilitados e desabilitados 
     * quantidade de incidentes gerados agrupados por APP_NAME
     */
    class MetricsService
    {
        public function get($id = null) 
        {
            if ($id) {
                return (new MetricsReport())->select($id); 

            } else {
                return (new MetricsReport())->report();

            }
        }


    }
