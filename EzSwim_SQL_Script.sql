-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Abzeichen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Abzeichen` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Abzeichen` (
  `id_abzeichen` INT NOT NULL AUTO_INCREMENT,
  `namen` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_abzeichen`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Benutzer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Benutzer` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Benutzer` (
  `id_benutzer` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `nachname` VARCHAR(45) NOT NULL,
  `geburtstag` DATE NULL,
  `telefonnummer1` VARCHAR(45) NULL,
  `telefonnummer2` VARCHAR(45) NULL,
  PRIMARY KEY (`id_benutzer`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Rolle`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Rolle` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Rolle` (
  `id_rolle` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_rolle`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Ort`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Ort` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Ort` (
  `id_ort` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `beschreibung` VARCHAR(45) NULL,
  PRIMARY KEY (`id_ort`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Gruppe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Gruppe` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Gruppe` (
  `id_gruppe` INT NOT NULL AUTO_INCREMENT,
  `beschreibung` VARCHAR(45) NULL,
  `name` VARCHAR(45) NULL,
  `id_gruppenverantwortlicher` INT NOT NULL,
  PRIMARY KEY (`id_gruppe`, `id_gruppenverantwortlicher`),
  INDEX `fk_grupperverantwortlicher_idx` (`id_gruppenverantwortlicher` ASC),
  UNIQUE INDEX `id_gruppenverantwortlicher_UNIQUE` (`id_gruppenverantwortlicher` ASC),
  CONSTRAINT `fk_grupperverantwortlicher`
    FOREIGN KEY (`id_gruppenverantwortlicher`)
    REFERENCES `mydb`.`Benutzer` (`id_benutzer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Benutzer_Rolle`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Benutzer_Rolle` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Benutzer_Rolle` (
  `id_benutzer` INT NOT NULL,
  `id_rolle` INT NOT NULL,
  PRIMARY KEY (`id_benutzer`, `id_rolle`),
  INDEX `fk_Benutzer_has_Rolle_Rolle1_idx` (`id_rolle` ASC),
  INDEX `fk_Benutzer_has_Rolle_Benutzer1_idx` (`id_benutzer` ASC),
  CONSTRAINT `fk_Benutzer_has_Rolle_Benutzer1`
    FOREIGN KEY (`id_benutzer`)
    REFERENCES `mydb`.`Benutzer` (`id_benutzer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Benutzer_has_Rolle_Rolle1`
    FOREIGN KEY (`id_rolle`)
    REFERENCES `mydb`.`Rolle` (`id_rolle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Schwimmer_Gruppe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Schwimmer_Gruppe` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Schwimmer_Gruppe` (
  `id_schwimmer` INT NOT NULL,
  `id_gruppe` INT NOT NULL,
  PRIMARY KEY (`id_schwimmer`, `id_gruppe`),
  INDEX `fk_Benutzer_has_Gruppe_Gruppe1_idx` (`id_gruppe` ASC),
  INDEX `fk_Benutzer_has_Gruppe_Benutzer1_idx` (`id_schwimmer` ASC),
  CONSTRAINT `fk_Benutzer_has_Gruppe_Benutzer1`
    FOREIGN KEY (`id_schwimmer`)
    REFERENCES `mydb`.`Benutzer` (`id_benutzer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Benutzer_has_Gruppe_Gruppe1`
    FOREIGN KEY (`id_gruppe`)
    REFERENCES `mydb`.`Gruppe` (`id_gruppe`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Zeitslot`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Zeitslot` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Zeitslot` (
  `id_zeitslot` INT NOT NULL AUTO_INCREMENT,
  `von` DATETIME NOT NULL,
  `bis` DATETIME NOT NULL,
  PRIMARY KEY (`id_zeitslot`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Unterrichtsstunde`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Unterrichtsstunde` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Unterrichtsstunde` (
  `id_unterrichtsstunde` INT NOT NULL AUTO_INCREMENT,
  `Gruppe_id_gruppe` INT NOT NULL,
  `Ort_id_ort` INT NOT NULL,
  `id_zeitslot` INT NOT NULL,
  `Unterrichtsstundecol` VARCHAR(45) NULL,
  PRIMARY KEY (`id_unterrichtsstunde`),
  INDEX `fk_Unterrichtsstunde_Gruppe1_idx` (`Gruppe_id_gruppe` ASC),
  INDEX `fk_Unterrichtsstunde_Ort1_idx` (`Ort_id_ort` ASC),
  INDEX `fk_zeitslot_idx` (`id_zeitslot` ASC),
  CONSTRAINT `fk_Unterrichtsstunde_Gruppe1`
    FOREIGN KEY (`Gruppe_id_gruppe`)
    REFERENCES `mydb`.`Gruppe` (`id_gruppe`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Unterrichtsstunde_Ort1`
    FOREIGN KEY (`Ort_id_ort`)
    REFERENCES `mydb`.`Ort` (`id_ort`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_zeitslot`
    FOREIGN KEY (`id_zeitslot`)
    REFERENCES `mydb`.`Zeitslot` (`id_zeitslot`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Trainer_Unterrichtsstunde`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Trainer_Unterrichtsstunde` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Trainer_Unterrichtsstunde` (
  `id_trainer_unterrichtsstunde` INT NOT NULL,
  `id_trainer` INT NOT NULL,
  `id_unterrichtsstunde` INT NOT NULL,
  `confirmed` INT NULL,
  INDEX `fk_trainer_idx` (`id_trainer` ASC),
  INDEX `fk_unterrichtsstunde_idx` (`id_unterrichtsstunde` ASC),
  PRIMARY KEY (`id_trainer_unterrichtsstunde`),
  CONSTRAINT `fk_trainer`
    FOREIGN KEY (`id_trainer`)
    REFERENCES `mydb`.`Benutzer` (`id_benutzer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_unterrichtsstunde`
    FOREIGN KEY (`id_unterrichtsstunde`)
    REFERENCES `mydb`.`Unterrichtsstunde` (`id_unterrichtsstunde`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Trainingseinheit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Trainingseinheit` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Trainingseinheit` (
  `id_trainingseinheit` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `zweck` VARCHAR(45) NOT NULL,
  `beschreibung` VARCHAR(45) NOT NULL,
  `dauer` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_trainingseinheit`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Unterrichtsstunde_Trainingseinheit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Unterrichtsstunde_Trainingseinheit` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Unterrichtsstunde_Trainingseinheit` (
  `id_unterrichtsstunde` INT NOT NULL,
  `id_trainingseinheit` INT NOT NULL,
  PRIMARY KEY (`id_unterrichtsstunde`, `id_trainingseinheit`),
  INDEX `fk_Unterrichtsstunde_has_Trainingseinheit_Trainingseinheit1_idx` (`id_trainingseinheit` ASC),
  INDEX `fk_Unterrichtsstunde_has_Trainingseinheit_Unterrichtsstunde_idx` (`id_unterrichtsstunde` ASC),
  CONSTRAINT `fk_Unterrichtsstunde_has_Trainingseinheit_Unterrichtsstunde1`
    FOREIGN KEY (`id_unterrichtsstunde`)
    REFERENCES `mydb`.`Unterrichtsstunde` (`id_unterrichtsstunde`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Unterrichtsstunde_has_Trainingseinheit_Trainingseinheit1`
    FOREIGN KEY (`id_trainingseinheit`)
    REFERENCES `mydb`.`Trainingseinheit` (`id_trainingseinheit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Abzeicheneinheit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Abzeicheneinheit` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Abzeicheneinheit` (
  `id_abzeicheneinheiten` INT NOT NULL AUTO_INCREMENT,
  `id_trainingseinheit` INT NOT NULL,
  `id_abzeichen` INT NOT NULL,
  PRIMARY KEY (`id_abzeicheneinheiten`),
  INDEX `fk_Trainingseinheit_has_Abzeichen_Abzeichen1_idx` (`id_abzeichen` ASC),
  INDEX `fk_Trainingseinheit_has_Abzeichen_Trainingseinheit1_idx` (`id_trainingseinheit` ASC),
  CONSTRAINT `fk_Trainingseinheit_has_Abzeichen_Trainingseinheit1`
    FOREIGN KEY (`id_trainingseinheit`)
    REFERENCES `mydb`.`Trainingseinheit` (`id_trainingseinheit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Trainingseinheit_has_Abzeichen_Abzeichen1`
    FOREIGN KEY (`id_abzeichen`)
    REFERENCES `mydb`.`Abzeichen` (`id_abzeichen`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Gruppe_Trainingseinheit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Gruppe_Trainingseinheit` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Gruppe_Trainingseinheit` (
  `id_gruppe_trainingseinheit` INT NOT NULL AUTO_INCREMENT,
  `id_gruppe` INT NOT NULL,
  `id_trainingseinheit` INT NOT NULL,
  `time` TIME NULL,
  INDEX `fk_Gruppe_has_Trainingseinheit_Trainingseinheit1_idx` (`id_trainingseinheit` ASC),
  INDEX `fk_Gruppe_has_Trainingseinheit_Gruppe1_idx` (`id_gruppe` ASC),
  PRIMARY KEY (`id_gruppe_trainingseinheit`),
  CONSTRAINT `fk_Gruppe_has_Trainingseinheit_Gruppe1`
    FOREIGN KEY (`id_gruppe`)
    REFERENCES `mydb`.`Gruppe` (`id_gruppe`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Gruppe_has_Trainingseinheit_Trainingseinheit1`
    FOREIGN KEY (`id_trainingseinheit`)
    REFERENCES `mydb`.`Trainingseinheit` (`id_trainingseinheit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Qualifikation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Qualifikation` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Qualifikation` (
  `id_qualifikation` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_qualifikation`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Benutzer_Qualifikation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Benutzer_Qualifikation` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Benutzer_Qualifikation` (
  `Benutzer_id_benutzer` INT NOT NULL,
  `Qualifikation_id_qualifikation` INT NOT NULL,
  PRIMARY KEY (`Benutzer_id_benutzer`, `Qualifikation_id_qualifikation`),
  INDEX `fk_Benutzer_has_Qualifikation_Qualifikation1_idx` (`Qualifikation_id_qualifikation` ASC),
  INDEX `fk_Benutzer_has_Qualifikation_Benutzer1_idx` (`Benutzer_id_benutzer` ASC),
  CONSTRAINT `fk_Benutzer_has_Qualifikation_Benutzer1`
    FOREIGN KEY (`Benutzer_id_benutzer`)
    REFERENCES `mydb`.`Benutzer` (`id_benutzer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Benutzer_has_Qualifikation_Qualifikation1`
    FOREIGN KEY (`Qualifikation_id_qualifikation`)
    REFERENCES `mydb`.`Qualifikation` (`id_qualifikation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Abzeicheneinheit_Benutzer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Abzeicheneinheit_Benutzer` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Abzeicheneinheit_Benutzer` (
  `id_abzeicheneinheit` INT NOT NULL,
  `id_benutzer` INT NOT NULL,
  `datum_abnahme` DATETIME NOT NULL,
  PRIMARY KEY (`id_abzeicheneinheit`, `id_benutzer`),
  INDEX `fk_benutzer_idx` (`id_benutzer` ASC),
  CONSTRAINT `fk_Abzeicheneinheit`
    FOREIGN KEY (`id_abzeicheneinheit`)
    REFERENCES `mydb`.`Abzeicheneinheit` (`id_abzeicheneinheiten`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_benutzer`
    FOREIGN KEY (`id_benutzer`)
    REFERENCES `mydb`.`Benutzer` (`id_benutzer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`bevorzugte_Timeslots`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`bevorzugte_Timeslots` ;

CREATE TABLE IF NOT EXISTS `mydb`.`bevorzugte_Timeslots` (
  `Benutzer_id_benutzer` INT NOT NULL,
  `Zeitslot_id_zeitslot` INT NOT NULL,
  PRIMARY KEY (`Benutzer_id_benutzer`, `Zeitslot_id_zeitslot`),
  INDEX `fk_Benutzer_has_Zeitslot_Zeitslot1_idx` (`Zeitslot_id_zeitslot` ASC),
  INDEX `fk_Benutzer_has_Zeitslot_Benutzer1_idx` (`Benutzer_id_benutzer` ASC),
  CONSTRAINT `fk_Benutzer_has_Zeitslot_Benutzer1`
    FOREIGN KEY (`Benutzer_id_benutzer`)
    REFERENCES `mydb`.`Benutzer` (`id_benutzer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Benutzer_has_Zeitslot_Zeitslot1`
    FOREIGN KEY (`Zeitslot_id_zeitslot`)
    REFERENCES `mydb`.`Zeitslot` (`id_zeitslot`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Material`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Material` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Material` (
  `id_material` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `menge` INT NOT NULL,
  PRIMARY KEY (`id_material`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Unterrichtsstunde_Material`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Unterrichtsstunde_Material` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Unterrichtsstunde_Material` (
  `Unterrichtsstunde_id_unterrichtsstunde` INT NOT NULL,
  `Material_id_material` INT NOT NULL,
  `verwendete_menge` INT NOT NULL,
  PRIMARY KEY (`Unterrichtsstunde_id_unterrichtsstunde`, `Material_id_material`),
  INDEX `fk_Unterrichtsstunde_has_Material_Material1_idx` (`Material_id_material` ASC),
  INDEX `fk_Unterrichtsstunde_has_Material_Unterrichtsstunde1_idx` (`Unterrichtsstunde_id_unterrichtsstunde` ASC),
  CONSTRAINT `fk_Unterrichtsstunde_has_Material_Unterrichtsstunde1`
    FOREIGN KEY (`Unterrichtsstunde_id_unterrichtsstunde`)
    REFERENCES `mydb`.`Unterrichtsstunde` (`id_unterrichtsstunde`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Unterrichtsstunde_has_Material_Material1`
    FOREIGN KEY (`Material_id_material`)
    REFERENCES `mydb`.`Material` (`id_material`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Tag` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Tag` (
  `id_tag` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_tag`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Trainingseinheit_Tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Trainingseinheit_Tag` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Trainingseinheit_Tag` (
  `Trainingseinheit_id_trainingseinheit` INT NOT NULL,
  `Tag_id_tag` INT NOT NULL,
  PRIMARY KEY (`Trainingseinheit_id_trainingseinheit`, `Tag_id_tag`),
  INDEX `fk_Trainingseinheit_has_Tag_Tag1_idx` (`Tag_id_tag` ASC),
  INDEX `fk_Trainingseinheit_has_Tag_Trainingseinheit1_idx` (`Trainingseinheit_id_trainingseinheit` ASC),
  CONSTRAINT `fk_Trainingseinheit_has_Tag_Trainingseinheit1`
    FOREIGN KEY (`Trainingseinheit_id_trainingseinheit`)
    REFERENCES `mydb`.`Trainingseinheit` (`id_trainingseinheit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Trainingseinheit_has_Tag_Tag1`
    FOREIGN KEY (`Tag_id_tag`)
    REFERENCES `mydb`.`Tag` (`id_tag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
