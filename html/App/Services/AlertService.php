<?php
    //namespace App\Services;
    //use App\Models\User;

    require_once "./App/Models/Alert.php";


    class AlertService
    {
        public function get($id = null) 
        {
            echo ("<br>DEBUG AlertService id:<br>");
            echo var_dump($id) . "<br>";
            
            if ($id) {
                return Alert::select($id);
            } else {
                return Alert::selectAll();
            }
        }

        public function post() 
        {
            $data = $_POST;

            return Alert::insert($data);
        }

        public function update() 
        {
            
        }

        public function delete() 
        {
            
        }
    }