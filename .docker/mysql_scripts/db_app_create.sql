
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


CREATE SCHEMA IF NOT EXISTS `db_app` DEFAULT CHARACTER SET latin1 ;
USE `db_app` ;

-- -----------------------------------------------------
-- Table `db_app`.`alerts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`alerts` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_name` VARCHAR(150) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` VARCHAR(512) NOT NULL,
  `enabled` TINYINT(1) NOT NULL COMMENT 'zero is considered as false, and non-zero value is considered as true. To use Boolean literals, you use the constants TRUE and FALSE that evaluate to 1 and 0 respectively.',
  `metric` VARCHAR(150) NOT NULL,
  `condition` CHAR(2) NOT NULL COMMENT 'Condition',
  `threshold` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `db_app`.`incidents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`incidents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `timestamp` TIMESTAMP NOT NULL,
  `alert_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `db_app`.`metrics`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`metrics` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sampletime` TIMESTAMP NOT NULL,
  `metricName` VARCHAR(150) NOT NULL,
  `appName` VARCHAR(150) NOT NULL,
  `value` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- ########################################
-- ########################################
-- INCLUDES
-- ########################################
-- ########################################

-- Dados do arquivo alerts-list.csv
INSERT INTO `db_app`.`alerts` (`id`, `app_name`, `title`, `description`, `enabled`, `metric`, `condition`, `threshold`) 
VALUES (DEFAULT,'ms-system-01','Tempo de Resposta','Tempo de Resposta da aplicação está muito alto',1,'response_time','>=',2),
  (DEFAULT,'ms-system-01','Porcentagem de Erros','Taxa de erros acima do normal',1,'error_rate_percentile','>',1),
  (DEFAULT,'ms-system-01','Quantidade de Requisições ','Número de requisições aumentou',1,'throughput','<=',1),
  (DEFAULT,'ms-system-02','Tempo de Resposta','Tempo de Resposta da aplicação está muito alto',1,'response_time','=',3),
  (DEFAULT,'ms-system-02','Porcentagem de Erros','Taxa de erros acima do normal',1,'error_rate_percentile','>',0),
  (DEFAULT,'ms-system-02','Quantidade de Requisições ','Número de requisições aumentou',1,'throughput','>',4),
  (DEFAULT,'ms-system-03','Tempo de Resposta','Tempo de Resposta da aplicação está muito alto',1,'response_time','>',1),
  (DEFAULT,'ms-system-03','Porcentagem de Erros','Taxa de erros acima do normal',1,'error_rate_percentile','>= ',2),
  (DEFAULT,'ms-system-03','Quantidade de Requisições ','Número de requisições aumentou',1,'throughput','<',3),
  (DEFAULT,'ms-system-04','Tempo de Resposta','Tempo de Resposta da aplicação está muito alto',1,'response_time','>',2),
  (DEFAULT,'ms-system-04','Porcentagem de Erros','Taxa de erros acima do normal',1,'error_rate_percentile','>',0),
  (DEFAULT,'ms-system-04','Quantidade de Requisições ','Número de requisições aumentou',1,'throughput','<=',5),
  (DEFAULT,'ms-system-05','Tempo de Resposta','Tempo de Resposta da aplicação está muito alto',1,'response_time','>',2),
  (DEFAULT,'ms-system-05','Porcentagem de Erros','Taxa de erros acima do normal',1,'error_rate_percentile','>=',1),
  (DEFAULT,'ms-system-05','Quantidade de Requisições ','Número de requisições aumentou',1,'throughput','<',3);

-- Inserção de registroS na tabela metrics
INSERT INTO `metrics` VALUES 
(1,'2021-06-22 01:17:17','response_time','ms-system-01',2),
(2,'2021-06-27 02:12:16','error_rate_percentile','ms-system-01',1),
(3,'2021-06-27 02:12:16','throughput','ms-system-01',4),
(4,'2021-06-27 02:47:07','response_time','ms-system-01',2),
(5,'2021-06-27 02:47:44','response_time','ms-system-02',3),
(6,'2021-06-27 02:48:06','response_time','ms-system-02',3),
(7,'2021-06-27 02:48:07','response_time','ms-system-02',3),
(8,'2021-06-27 02:48:35','error_rate_percentile','ms-system-02',3),
(9,'2021-06-27 02:48:37','error_rate_percentile','ms-system-02',3),
(10,'2021-06-27 02:48:40','error_rate_percentile','ms-system-02',3),
(11,'2021-06-27 02:48:41','error_rate_percentile','ms-system-02',3),
(12,'2021-06-27 02:48:42','error_rate_percentile','ms-system-02',3),
(13,'2021-06-27 02:49:19','throughput','ms-system-02',3),
(14,'2021-06-27 02:49:27','throughput','ms-system-02',6),
(15,'2021-06-27 02:49:58','throughput','ms-system-02',6),
(16,'2021-06-27 02:50:09','throughput','ms-system-02',6),
(17,'2021-06-27 02:50:10','throughput','ms-system-02',6),
(18,'2021-06-27 02:50:11','throughput','ms-system-02',6),
(19,'2021-06-27 02:50:12','throughput','ms-system-02',6),
(20,'2021-06-27 02:50:12','throughput','ms-system-02',6),
(21,'2021-06-27 02:50:13','throughput','ms-system-02',6),
(22,'2021-06-27 02:50:52','error_rate_percentile','ms-system-02',6),
(23,'2021-06-27 02:50:55','error_rate_percentile','ms-system-02',6),
(24,'2021-06-27 02:52:58','error_rate_percentile','ms-system-04',3),
(25,'2021-06-27 02:53:01','error_rate_percentile','ms-system-04',3);


-- Inserção de registros na tabela de incidentes
INSERT INTO `incidents` VALUES 
(1,'2021-06-27 02:47:07',1),
(2,'2021-06-27 02:47:44',4),
(3,'2021-06-27 02:48:06',4),
(4,'2021-06-27 02:48:07',4),
(5,'2021-06-27 02:48:35',5),
(6,'2021-06-27 02:48:37',5),
(7,'2021-06-27 02:48:40',5),
(8,'2021-06-27 02:48:41',5),
(9,'2021-06-27 02:48:42',5),
(10,'2021-06-27 02:49:27',6),
(11,'2021-06-27 02:49:58',6),
(12,'2021-06-27 02:50:09',6),
(13,'2021-06-27 02:50:10',6),
(14,'2021-06-27 02:50:11',6),
(15,'2021-06-27 02:50:12',6),
(16,'2021-06-27 02:50:12',6),
(17,'2021-06-27 02:50:13',6),
(18,'2021-06-27 02:50:52',5),
(19,'2021-06-27 02:50:55',5),
(20,'2021-06-27 02:52:58',11),
(21,'2021-06-27 02:53:01',11);