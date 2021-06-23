-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema db_app
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_app
-- -----------------------------------------------------
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

-- Inserção de 1 registro na tabela metricssampletime
INSERT INTO `db_app`.`metrics` (`id`, `sampletime`, `metricName`, `appName`, `value`) 
VALUES 
	(default, '2021-06-21 22:17:17', 'response_time', 'ms-system-01', '2'),
	(default, now(), 'error_rate_percentile', 'ms-system-01', '1'),
    (default, now(), 'throughput', 'ms-system-01', '4');
SELECT `Id`, `sampletime`, `metricName`, `appName`, `value` FROM `db_app`.`metrics` WHERE  `id`=1;
select * from `db_app`.`alerts`;