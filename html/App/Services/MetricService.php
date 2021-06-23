<?php
    //namespace App\Services;
    //use App\Models\Metric;

    require_once "./App/Models/Metric.php";


    class MetricService
    {
        public function get($id = null) 
        {
            if ($id) {
                return Metric::select($id);
            } else {
                return Metric::selectAll();
            }
        }

        public function post() 
        {
            $data = $_POST;

            return Metric::insert($data);
        }

        public function update() 
        {
            
        }

        public function delete() 
        {
            
        }
    }