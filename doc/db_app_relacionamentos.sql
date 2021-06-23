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
-- Table `db_app`.`metrics`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`metrics` (
  `id` INT NOT NULL,
  `metricname` VARCHAR(150) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_app`.`apps`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`apps` (
  `id` INT NOT NULL,
  `appname` VARCHAR(45) NULL,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_app`.`metric_has_app`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`metric_has_app` (
  `metric_id` INT NOT NULL,
  `app_id` INT NOT NULL,
  `value` INT NULL,
  `sampletime` DATETIME NULL,
  PRIMARY KEY (`metric_id`, `app_id`),
  INDEX `fk_metric_has_app_app1_idx` (`app_id` ASC) ,
  INDEX `fk_metric_has_app_metric_idx` (`metric_id` ASC) ,
  CONSTRAINT `fk_metric_has_app_metric`
    FOREIGN KEY (`metric_id`)
    REFERENCES `db_app`.`metrics` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_metric_has_app_app1`
    FOREIGN KEY (`app_id`)
    REFERENCES `db_app`.`apps` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_app`.`alerts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`alerts` (
  `alert_id` INT(10) NOT NULL,
  `sampletime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` VARCHAR(250) NOT NULL,
  `description` VARCHAR(512) NOT NULL,
  `enabled` TINYINT(1) NOT NULL COMMENT 'zero is considered as false, and non-zero value is considered as true. To use Boolean literals, you use the constants TRUE and FALSE that evaluate to 1 and 0 respectively.',
  `condition` CHAR(2) NOT NULL COMMENT 'Condition',
  `threshold` INT(11) NOT NULL,
  `metric_has_app_metric_id` INT NOT NULL,
  `metric_has_app_app_id` INT NOT NULL,
  PRIMARY KEY (`alert_id`),
  INDEX `fk_alerts_metric_has_app1_idx` (`metric_has_app_metric_id` ASC, `metric_has_app_app_id` ASC) ,
  CONSTRAINT `fk_alerts_metric_has_app1`
    FOREIGN KEY (`metric_has_app_metric_id` , `metric_has_app_app_id`)
    REFERENCES `db_app`.`metric_has_app` (`metric_id` , `app_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `db_app`.`incidents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_app`.`incidents` (
  `incident_id` INT(11) NOT NULL,
  `timestamp` DATETIME NOT NULL,
  `alerts_alert_id` INT(10) NOT NULL,
  PRIMARY KEY (`incident_id`),
  INDEX `fk_incidents_alerts1_idx` (`alerts_alert_id` ASC) ,
  CONSTRAINT `fk_incidents_alerts1`
    FOREIGN KEY (`alerts_alert_id`)
    REFERENCES `db_app`.`alerts` (`alert_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


