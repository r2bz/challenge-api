<?php
    namespace Api\App\Services;
    use Api\App\Models\MetricsReport;

    // require_once "./App/Models/MetricsReport.php";

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
                //return Metric::select($id);
            } else {
                return (new MetricsReport())->incident_by_metric();
                //return Metric::selectAll();
            }
        }

        // public function post() 
        // {
        //     $data = $_POST;
        //     return (new Metric())->insert($data);
        //     // return Metric::insert($data);
        // }


        // // Atualiza um campo de uma métrica específica
        // public function patch() 
        // {   
        //     // Não foi solicitada a implementação
        // }

        // // Deleta uma métrica
        // public function delete() 
        // {
        //     // Não foi solicitada a implementação

        // }
    }
