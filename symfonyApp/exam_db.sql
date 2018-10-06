-- MySQL Script generated by MySQL Workbench
-- Sat Oct  6 13:25:07 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema exam_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema exam_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `exam_db` DEFAULT CHARACTER SET utf8 ;
USE `exam_db` ;

-- -----------------------------------------------------
-- Table `exam_db`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `firstname` VARCHAR(20) NOT NULL,
  `lastname` VARCHAR(20) NOT NULL,
  `teacher` TINYINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exam_db`.`Course`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`Course` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exam_db`.`Exam`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`Exam` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `course_id` INT NOT NULL,
  `creator_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `exam_course_id_idx` (`course_id` ASC),
  INDEX `exam_user_id_idx` (`creator_id` ASC),
  CONSTRAINT `exam_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `exam_db`.`Course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `exam_creator_id`
    FOREIGN KEY (`creator_id`)
    REFERENCES `exam_db`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exam_db`.`Question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`Question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `course_id` INT NOT NULL,
  `question` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `question_course_id_idx` (`course_id` ASC),
  CONSTRAINT `question_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `exam_db`.`Course` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exam_db`.`Answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`Answer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `answer` VARCHAR(255) NOT NULL,
  `correct_answer` TINYINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `answer_question_id_idx` (`question_id` ASC),
  CONSTRAINT `answer_question_id`
    FOREIGN KEY (`question_id`)
    REFERENCES `exam_db`.`Question` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exam_db`.`ExamInstance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`ExamInstance` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `exam_id` INT NOT NULL,
  `grade` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `exam_instance_user_id_idx` (`user_id` ASC),
  INDEX `exam_instance_exam_id_idx` (`exam_id` ASC),
  CONSTRAINT `exam_instance_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `exam_db`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `exam_instance_exam_id`
    FOREIGN KEY (`exam_id`)
    REFERENCES `exam_db`.`Exam` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exam_db`.`ExamQuestion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`ExamQuestion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `exam_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `exam_question_exam_id_idx` (`exam_id` ASC),
  INDEX `exam_question_question_id_idx` (`question_id` ASC),
  CONSTRAINT `exam_question_question_id`
    FOREIGN KEY (`question_id`)
    REFERENCES `exam_db`.`Question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `exam_question_exam_id`
    FOREIGN KEY (`exam_id`)
    REFERENCES `exam_db`.`Exam` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exam_db`.`AnswerGiven`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exam_db`.`AnswerGiven` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `exam_instance_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `answer_given_answer_id_idx` (`answer_id` ASC),
  INDEX `answer_exam_instance_id_idx` (`exam_instance_id` ASC),
  INDEX `answer_given_exam_question_id_idx` (`question_id` ASC),
  CONSTRAINT `answer_given_exam_question_id`
    FOREIGN KEY (`question_id`)
    REFERENCES `exam_db`.`ExamQuestion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `answer_given_answer_id`
    FOREIGN KEY (`answer_id`)
    REFERENCES `exam_db`.`Answer` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `answer_exam_instance_id`
    FOREIGN KEY (`exam_instance_id`)
    REFERENCES `exam_db`.`ExamInstance` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
