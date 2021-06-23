<?php
    //namespace App\Models;
    require_once $_SERVER['DOCUMENT_ROOT'] . "/configConn.php";

    class Alert
    {
        private static $table = 'alerts';

        public static function select(int $id) {
            echo ("<br>DEBUG Chegou na classe Alert metodo select id:<br>");
            echo var_dump($id) . "<br>";
            
            try{
                $connPdo = new PDO(strval(DBDRIVE.':host='.DBHOST.';dbname='.DBNAME),strval(DBUSER), strval(DBPASS));
                
                echo "<br>Conectou ao banco! Alert<br>";

                $sql = 'SELECT * FROM '.self::$table.' WHERE `alert_id` = :id';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            

            if ($stmt->rowCount() > 0) {

                //DEBUG
                echo ("<br>RETORNOU ".$stmt->rowCount(). " registros <BR>");
                $retorno = $stmt->fetch(PDO::FETCH_ASSOC);
                //$retorno = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo ("<br>RETORNO: <BR>");
                var_dump($retorno);
                echo ("<br>stmt: <BR>");
                var_dump($stmt);
                echo ("<br>json_encode: <BR>");
                //DEBUG

                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("Nenhum registro encontrado!");
            }
        }

        public static function selectAll() {
            try{
                $connPdo = new PDO(strval(DBDRIVE.':host='.DBHOST.';dbname='.DBNAME),strval(DBUSER), strval(DBPASS));

                $sql = 'SELECT * FROM '.self::$table;
                $stmt = $connPdo->prepare($sql);
                $stmt->execute();
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("Nenhum registro encontrado!");
            }
        }

        public static function insert($data)
        {
            try {
                $connPdo = new PDO(strval(DBDRIVE.':host='.DBHOST.';dbname='.DBNAME),strval(DBUSER), strval(DBPASS));

                $sql = 'INSERT INTO '.self::$table.' (email, password, name) VALUES (:em, :pa, :na)';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':em', $data['email']);
                $stmt->bindValue(':pa', $data['password']);
                $stmt->bindValue(':na', $data['name']);
                $stmt->execute();
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            
            if ($stmt->rowCount() > 0) {
                return 'Usuário(a) inserido com sucesso!';
            } else {
                throw new Exception("Falha ao inserir usuário(a)!");
            }
        }


        public static function insertAlert($data)
        {
            $connPdo = new PDO(strval(DBDRIVE.':host='.DBHOST.';dbname='.DBNAME),strval(DBUSER), strval(DBPASS));
            $sql = 'INSERT INTO '.self::$table.
                        ' (`alert_id`, `sampletime`, `app_name`, `title`, `description`, `enabled`, `metric`, `condition`, `threshold`) 
                            VALUES (DEFAULT,now(),:a3, :a4, :a5, :a6, :a7, :a8, :a9)';
            
            //INSERT INTO `db_app`.`alerts`  (`alert_id`, `sampletime`, `app_name`, `title`, `description`, `enabled`, `metric`, `condition`, `threshold`)
            //VALUES (DEFAULT,now(),'ms-system-01','Quantidade de Requisições ','Número de requisições aumentou','1','throughput','<=','1');
        }

        public static function deleteAlert($data)
        {
            try {
                $connPdo = new PDO(strval(DBDRIVE.':host='.DBHOST.';dbname='.DBNAME),strval(DBUSER), strval(DBPASS));

                //DELETE FROM `db_app`.`alerts` WHERE  `alert_id`=1;

            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

            
        }
    }