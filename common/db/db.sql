SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `writesdown` DEFAULT CHARACTER SET latin1 ;
USE `writesdown` ;

-- -----------------------------------------------------
-- Table `writesdown`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255) NULL DEFAULT NULL,
  `display_name` VARCHAR(255) NULL DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `password_reset_token` VARCHAR(255) NULL DEFAULT NULL,
  `auth_key` VARCHAR(32) NOT NULL,
  `status` SMALLINT(6) NOT NULL DEFAULT '5',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `login_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`auth_rule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`auth_rule` (
  `name` VARCHAR(64) NOT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`auth_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`auth_item` (
  `name` VARCHAR(64) NOT NULL,
  `type` INT(11) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `rule_name` VARCHAR(64) NULL DEFAULT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`),
  INDEX `rule_name` (`rule_name` ASC),
  CONSTRAINT `auth_item_ibfk_1`
    FOREIGN KEY (`rule_name`)
    REFERENCES `writesdown`.`auth_rule` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`auth_assignment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`auth_assignment` (
  `item_name` VARCHAR(64) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`),
  INDEX `user_id` (`user_id` ASC),
  CONSTRAINT `auth_assignment_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `writesdown`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `auth_assignment_ibfk_2`
    FOREIGN KEY (`item_name`)
    REFERENCES `writesdown`.`auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`auth_item_child`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`auth_item_child` (
  `parent` VARCHAR(64) NOT NULL,
  `child` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`parent`, `child`),
  INDEX `child` (`child` ASC),
  CONSTRAINT `auth_item_child_ibfk_1`
    FOREIGN KEY (`child`)
    REFERENCES `writesdown`.`auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2`
    FOREIGN KEY (`parent`)
    REFERENCES `writesdown`.`auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`post_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`post_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_type_name` VARCHAR(64) NOT NULL,
  `post_type_slug` VARCHAR(64) NOT NULL,
  `post_type_description` TEXT NULL DEFAULT NULL,
  `post_type_icon` VARCHAR(255) NULL DEFAULT NULL,
  `post_type_sn` VARCHAR(255) NOT NULL,
  `post_type_pn` VARCHAR(255) NOT NULL,
  `post_type_smb` SMALLINT(1) NOT NULL DEFAULT '0',
  `post_type_permission` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`post` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_author` INT(11) NOT NULL,
  `post_type` INT(11) NULL DEFAULT NULL,
  `post_title` TEXT NOT NULL,
  `post_excerpt` TEXT NULL DEFAULT NULL,
  `post_content` TEXT NULL DEFAULT NULL,
  `post_date` DATETIME NOT NULL,
  `post_modified` DATETIME NOT NULL,
  `post_status` VARCHAR(20) NOT NULL DEFAULT 'publish',
  `post_password` VARCHAR(255) NULL DEFAULT NULL,
  `post_slug` VARCHAR(255) NOT NULL,
  `post_comment_status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `post_comment_count` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `post_type` (`post_type` ASC),
  INDEX `post_author` (`post_author` ASC),
  CONSTRAINT `post_ibfk_1`
    FOREIGN KEY (`post_type`)
    REFERENCES `writesdown`.`post_type` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `post_ibfk_2`
    FOREIGN KEY (`post_author`)
    REFERENCES `writesdown`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`media`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`media` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `media_author` INT(11) NOT NULL,
  `media_post_id` INT(11) NULL DEFAULT NULL,
  `media_title` TEXT NOT NULL,
  `media_excerpt` TEXT NULL DEFAULT NULL,
  `media_content` TEXT NULL DEFAULT NULL,
  `media_password` VARCHAR(255) NULL DEFAULT NULL,
  `media_date` DATETIME NOT NULL,
  `media_modified` DATETIME NOT NULL,
  `media_slug` VARCHAR(255) NOT NULL,
  `media_mime_type` VARCHAR(100) NOT NULL,
  `media_comment_status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `media_comment_count` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `media_post_id` (`media_post_id` ASC),
  INDEX `media_author` (`media_author` ASC),
  CONSTRAINT `media_ibfk_1`
    FOREIGN KEY (`media_post_id`)
    REFERENCES `writesdown`.`post` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `media_ibfk_2`
    FOREIGN KEY (`media_author`)
    REFERENCES `writesdown`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`media_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`media_comment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_media_id` INT(11) NOT NULL,
  `comment_author` TEXT NULL DEFAULT NULL,
  `comment_author_email` VARCHAR(100) NULL DEFAULT NULL,
  `comment_author_url` VARCHAR(255) NULL DEFAULT NULL,
  `comment_author_ip` VARCHAR(100) NOT NULL,
  `comment_date` DATETIME NOT NULL,
  `comment_content` TEXT NOT NULL,
  `comment_approved` VARCHAR(20) NOT NULL,
  `comment_agent` VARCHAR(255) NOT NULL,
  `comment_parent` INT(11) NULL DEFAULT '0',
  `comment_user_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `comment_media_id` (`comment_media_id` ASC),
  CONSTRAINT `media_comment_ibfk_1`
    FOREIGN KEY (`comment_media_id`)
    REFERENCES `writesdown`.`media` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`media_meta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`media_meta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `media_id` INT(11) NOT NULL,
  `meta_name` VARCHAR(255) NOT NULL,
  `meta_value` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `media_id` (`media_id` ASC),
  CONSTRAINT `media_meta_ibfk_1`
    FOREIGN KEY (`media_id`)
    REFERENCES `writesdown`.`media` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`menu`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`menu` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_title` VARCHAR(255) NOT NULL,
  `menu_location` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`menu_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`menu_item` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_id` INT(11) NOT NULL,
  `menu_label` VARCHAR(255) NOT NULL,
  `menu_url` TEXT NOT NULL,
  `menu_description` TEXT NULL DEFAULT NULL,
  `menu_order` INT(11) NOT NULL DEFAULT '0',
  `menu_parent` INT(11) NOT NULL DEFAULT '0',
  `menu_options` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `menu_id` (`menu_id` ASC),
  CONSTRAINT `menu_item_ibfk_1`
    FOREIGN KEY (`menu_id`)
    REFERENCES `writesdown`.`menu` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`option`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`option` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `option_name` VARCHAR(64) NOT NULL,
  `option_value` TEXT NOT NULL,
  `option_label` VARCHAR(64) NULL DEFAULT NULL,
  `option_group` VARCHAR(64) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 45
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`post_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`post_comment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_post_id` INT(11) NOT NULL,
  `comment_author` TEXT NULL DEFAULT NULL,
  `comment_author_email` VARCHAR(100) NULL DEFAULT NULL,
  `comment_author_url` VARCHAR(255) NULL DEFAULT NULL,
  `comment_author_ip` VARCHAR(100) NOT NULL,
  `comment_date` DATETIME NOT NULL,
  `comment_content` TEXT NOT NULL,
  `comment_approved` VARCHAR(20) NOT NULL,
  `comment_agent` VARCHAR(255) NOT NULL,
  `comment_parent` INT(11) NULL DEFAULT '0',
  `comment_user_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `comment_post_id` (`comment_post_id` ASC),
  CONSTRAINT `post_comment_ibfk_1`
    FOREIGN KEY (`comment_post_id`)
    REFERENCES `writesdown`.`post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`post_meta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`post_meta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `meta_name` VARCHAR(255) NOT NULL,
  `meta_value` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `post_id` (`post_id` ASC),
  CONSTRAINT `post_meta_ibfk_1`
    FOREIGN KEY (`post_id`)
    REFERENCES `writesdown`.`post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`taxonomy`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`taxonomy` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `taxonomy_name` VARCHAR(200) NOT NULL,
  `taxonomy_slug` VARCHAR(200) NOT NULL,
  `taxonomy_hierarchical` SMALLINT(1) NOT NULL DEFAULT '0',
  `taxonomy_sn` VARCHAR(255) NOT NULL,
  `taxonomy_pn` VARCHAR(255) NOT NULL,
  `taxonomy_smb` SMALLINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`post_type_taxonomy`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`post_type_taxonomy` (
  `post_type_id` INT(11) NOT NULL,
  `taxonomy_id` INT(11) NOT NULL,
  PRIMARY KEY (`post_type_id`, `taxonomy_id`),
  INDEX `taxonomy_id` (`taxonomy_id` ASC),
  CONSTRAINT `post_type_taxonomy_ibfk_1`
    FOREIGN KEY (`post_type_id`)
    REFERENCES `writesdown`.`post_type` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `post_type_taxonomy_ibfk_2`
    FOREIGN KEY (`taxonomy_id`)
    REFERENCES `writesdown`.`taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`term`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`term` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `taxonomy_id` INT(11) NOT NULL,
  `term_name` VARCHAR(200) NOT NULL,
  `term_slug` VARCHAR(200) NOT NULL,
  `term_description` TEXT NULL DEFAULT NULL,
  `term_parent` INT(11) NULL DEFAULT '0',
  `term_count` INT(11) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `taxonomy_id` (`taxonomy_id` ASC),
  CONSTRAINT `term_ibfk_1`
    FOREIGN KEY (`taxonomy_id`)
    REFERENCES `writesdown`.`taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `writesdown`.`term_relationship`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `writesdown`.`term_relationship` (
  `object_id` INT(11) NOT NULL,
  `term_id` INT(11) NOT NULL,
  PRIMARY KEY (`object_id`, `term_id`),
  INDEX `term_id` (`term_id` ASC),
  CONSTRAINT `term_relationship_ibfk_1`
    FOREIGN KEY (`object_id`)
    REFERENCES `writesdown`.`post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `term_relationship_ibfk_2`
    FOREIGN KEY (`term_id`)
    REFERENCES `writesdown`.`term` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
