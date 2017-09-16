<?php
/*
|   Default database settings for the Tech Bench
|   Updated 7-14-17
|   Database Version 2.4
*/

$database = '
CREATE SCHEMA IF NOT EXISTS `'.$_SESSION['setupData']['dbName'].'`;

USE `'.$_SESSION['setupData']['dbName'].'`;

CREATE TABLE IF NOT EXISTS `_database_version` (
    `version_id` INT(11) NOT NULL UNIQUE,
    `version` DECIMAL(3, 1) NOT NULL UNIQUE,
    PRIMARY KEY(`version_id`)
);

CREATE TABLE IF NOT EXISTS `users` (
	`user_id` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(20) NOT NULL,
	`first_name` VARCHAR(40) NOT NULL,
	`last_name` VARCHAR(40) NOT NULL,
	`email` VARCHAR(90) NOT NULL,
	`password` VARCHAR(90) NOT NULL,
	`salt` VARCHAR(10) NOT NULL,
	`active` TINYINT(1) NOT NULL,
	`login_session` VARCHAR(90),
	`change_password` TINYINT(1) NOT NULL,
	PRIMARY KEY(`user_id`)
);

CREATE TABLE IF NOT EXISTS `login_activity` (
	`activity_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `timestamp` TIMESTAMP,
    PRIMARY KEY (`activity_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `roles` (
	`role_id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_name` VARCHAR(50) NOT NULL,
    `role_home` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`role_id`)
);

CREATE TABLE IF NOT EXISTS `permissions` (
	`permission_id` INT(11) NOT NULL AUTO_INCREMENT,
    `permission_description` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`permission_id`)
);

CREATE TABLE IF NOT EXISTS `role_permissions` (
	`role_id` INT(11) NOT NULL,
    `permission_id` INT(11) NOT NULL,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`role_id`)
		ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`permission_id`)
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `user_roles` (
	`user_id` INT(11) NOT NULL,
    `role_id` INT(11) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
		ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`role_id`)
		ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `user_settings` (
    `user_id` INT(11) NOT NULL,
    `em_tech_tip` TINYINT(1) NOT NULL DEFAULT 1,
    `em_file_link` TINYINT(1) NOT NULL DEFAULT 1,
    `em_sys_notification` TINYINT(1) NOT NULL DEFAULT 1,
    `auto_delete_link` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE    
);

CREATE TABLE IF NOT EXISTS `system_categories` (
	`cat_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`cat_id`)
);

CREATE TABLE IF NOT EXISTS `system_file_types` (
	`type_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`type_id`)
);
    
CREATE TABLE IF NOT EXISTS `system_types` (
	`sys_id` INT(11) NOT NULL AUTO_INCREMENT,
    `cat_id` INT(11) NOT NULL,
    `name` VARCHAR(20) NOT NULL,
    `parent_id` INT(11),
    `folder_location` VARCHAR(90) NOT NULL,
    PRIMARY KEY (`sys_id`),
    FOREIGN KEY (`cat_id`) REFERENCES `system_categories` (`cat_id`) 
		ON UPDATE CASCADE,
    FOREIGN KEY (`parent_id`) REFERENCES `system_types` (`sys_id`)
        ON UPDATE CASCADE
);
    
CREATE TABLE IF NOT EXISTS `files` (
	`file_id` INT(11) NOT NULL AUTO_INCREMENT,
    `file_name` VARCHAR(90) NOT NULL,
    `file_link` VARCHAR(90) NOT NULL,
    `mime_type` VARCHAR(90) NOT NULL,
    `permission_id` INT(11) NOT NULL,
    PRIMARY KEY (`file_id`),
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`permission_id`)
		ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `system_files` (
	`sys_file_id` INT(11) NOT NULL AUTO_INCREMENT,
    `sys_id` INT(11) NOT NULL,
    `type_id` INT(11) NOT NULL,
    `file_id` INT(11) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
	`description` LONGTEXT,
    `added_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `user_id` INT(11) NOT NULL,
    PRIMARY KEY (`sys_file_id`),
    FOREIGN KEY (`sys_id`) REFERENCES `system_types` (`sys_id`) 
		ON UPDATE CASCADE,
	FOREIGN KEY (`type_id`) REFERENCES `system_file_types` (`type_id`)
		ON UPDATE CASCADE,
	FOREIGN KEY (`file_id`) REFERENCES `files` (`file_id`)
		ON UPDATE CASCADE,
	FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `notifications` (
	`notify_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(120) NOT NULL,
    `link` VARCHAR(120) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`notify_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `customers` (
	`cust_id` INT(11) NOT NULL UNIQUE,
    `name` VARCHAR(90) NOT NULL,
    `dba_name` VARCHAR(90),
    `address` VARCHAR(120) NOT NULL,
    `city` VARCHAR(90) NOT NULL,
    `state` CHAR(2) NOT NULL,
    `zip` INT(5) NOT NULL,
    `added_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`cust_id`)
);

CREATE TABLE IF NOT EXISTS `customer_systems` (
    `cust_id` INT(11) NOT NULL,
    `sys_id` INT(11) NOT NULL,
    FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`sys_id`) REFERENCES `system_types` (`sys_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `customer_contacts` (
    `cont_id` INT(11) NOT NULL AUTO_INCREMENT,
    `cust_id` INT(11) NOT NULL,
    `name` VARCHAR(120) NOT NULL,
    `phone` VARCHAR(30),
    `email` VARCHAR(255),
    PRIMARY KEY (`cont_id`),
    FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `phone_number_types` (
	`phone_type_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(90),
    PRIMARY KEY (`phone_type_id`)
);

CREATE TABLE IF NOT EXISTS `customer_contact_phones` (
	`cont_id` INT(11) NOT NULL AUTO_INCREMENT,
    `phone_type_id` INT(11) NOT NULL DEFAULT 1,
    `phone_number` VARCHAR(30),
    FOREIGN KEY (`cont_id`) REFERENCES `customer_contacts` (`cont_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`phone_type_id`) REFERENCES `phone_number_types` (`phone_type_id`) 
		ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `customer_note_levels` (
	`note_level_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(90) NOT NULL UNIQUE,
    PRIMARY KEY (`note_level_id`)
);

CREATE TABLE IF NOT EXISTS `customer_notes` (
    `note_id` INT(11) NOT NULL AUTO_INCREMENT,
    `note_level_id` INT(11) NOT NULL DEFAULT 1,
    `cust_id` INT(11) NOT NULL,
    `subject` VARCHAR(120) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `user_id` INT(11) NOT NULL,
    PRIMARY KEY (`note_id`),
    FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
        ON UPDATE CASCADE,
    FOREIGN KEY (`note_level_id`) REFERENCES `customer_note_levels`(`note_level_id`) 
        ON UPDATE CASCADE;
);

CREATE TABLE IF NOT EXISTS `customer_file_types` (
    `file_type_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(90) NOT NULL UNIQUE,
    PRIMARY KEY (`file_type_id`)
);

CREATE TABLE IF NOT EXISTS `customer_files` (
    `cust_file_id` INT(11) NOT NULL AUTO_INCREMENT,
    `file_id` INT(11) NOT NULL,
    `cust_id` INT(11) NOT NULL,
    `file_type_id` INT(11) NOT NULL,
    `name` VARCHAR(90) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `added_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`cust_file_id`),
    FOREIGN KEY (`file_id`) REFERENCES `files` (`file_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`file_type_id`) REFERENCES `customer_file_types` (`file_type_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
        ON UPDATE CASCADE
);

CREATE TABLE `customer_favs` (
	`user_id` INT(11) NOT NULL,
    `cust_id` INT(11) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `upload_links` (
	`link_id` INT(11) NOT NULL AUTO_INCREMENT,
	`link_hash` VARCHAR(120) NOT NULL,
	`link_name` VARCHAR(90) NOT NULL,
	`expire` DATE NOT NULL,
    `allow_user_upload` TINYINT(1) NOT NULL DEFAULT 1,
	`user_id` INT(11) NOT NULL,
	PRIMARY KEY (`link_id`),
	FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `upload_link_files` (
	`link_file_id` INT(11) NOT NULL AUTO_INCREMENT,
	`link_id` INT(11) NOT NULL,
	`file_id` INT(11) NOT NULL UNIQUE,
	`added_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`added_by` VARCHAR(90) NOT NULL,
	PRIMARY KEY (`link_file_id`),
	FOREIGN KEY (`link_id`) REFERENCES `upload_links` (`link_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`file_id`) REFERENCES `files` (`file_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `upload_link_notes` (
    `upload_note_id` INT(11) NOT NULL AUTO_INCREMENT,
    `file_id` INT(11) NOT NULL,
    `note` LONGTEXT NOT NULL,
    PRIMARY KEY (`upload_note_id`),
    FOREIGN KEY (`file_id`) REFERENCES `files` (`file_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `company_files` (
    `form_id` INT(11) NOT NULL AUTO_INCREMENT,
    `file_id` INT(11) NOT NULL,
    `name` VARCHAR(90) NOT NULL,
    PRIMARY KEY (`form_id`),
    FOREIGN KEY (`file_id`) REFERENCES `files` (`file_id`)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `tech_tips` (
    `tip_id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(120) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `added_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`tip_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `tech_tip_details` (
    `tip_id` INT(11) NOT NULL UNIQUE,
    `details` LONGTEXT NOT NULL,
    FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `tech_tip_tags` (
    `tip_id` INT(11) NOT NULL,
    `sys_id` INT(11) NOT NULL,
    FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`sys_id`) REFERENCES `system_types` (`sys_id`)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `tech_tip_files` (
    `tip_id` INT(11) NOT NULL,
    `file_id` INT(11) NOT NULL,
    FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`file_id`) REFERENCES `files` (`file_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `tech_tip_comments` (
	`comment_id` INT(11) NOT NULL AUTO_INCREMENT,
    `tip_id` INT(11) NOT NULL,
    `comment` LONGTEXT NOT NULL,
    `user_id` INT(11) NOT NULL,
    `added_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`comment_id`),
    FOREIGN KEY (`tip_id`) REFERENCES `tech_tips`(`tip_id`)
		ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) 
		ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `tech_tip_favs` (
    `user_id` INT(11) NOT NULL,
    `tip_id` INT(11) NOT NULL,
    FOREIGN KEY(`user_id`) REFERENCES `users` (`user_id`) 
        ON UPDATE CASCADE,
    FOREIGN KEY (`tip_id`) REFERENCES `tech_tips` (`tip_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `alert_levels` (
    `alert_level_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(90) NOT NULL UNIQUE,
    PRIMARY KEY (`alert_level_id`)
);

CREATE TABLE IF NOT EXISTS `broadcast_alerts` (
    `bdcst_alert_id` INT(11) NOT NULL AUTO_INCREMENT,
    `alert_level_id` INT(11) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `expire_date` DATE NOT NULL,
    PRIMARY KEY (`bdcst_alert_id`),
    FOREIGN KEY (`alert_level_id`) REFERENCES `alert_levels` (`alert_level_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `user_alerts` (
    `user_alert_id` INT(11) NOT NULL AUTO_INCREMENT,
    `alert_level_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `expire_date` DATE NOT NULL,
    `dismissable` TINYINT(1) DEFAULT 1,
    `dismissed` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`user_alert_id`),
    FOREIGN KEY (`alert_level_id`) REFERENCES `alert_levels` (`alert_level_id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `user_notifications`  (
    `notification_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `description` VARCHAR(500) NOT NULL,
    `link` VARCHAR(200) NOT NULL,
    `viewed` TINYINT(1) NOT NULL DEFAULT 0,
    `added_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`notification_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) 
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `user_password_links` (
    `user_id` INT(11) NOT NULL UNIQUE,
    `link` VARCHAR(90) NOT NULL UNIQUE,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
        ON UPDATE CASCADE
);

CREATE TABLE `failed_login_attempts` (
	`attempt_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_ip_address` VARCHAR(15) NOT NULL,
    `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`attempt_id`)
);

CREATE TABLE IF NOT EXISTS `upload_link_instructions` (
	`link_id` INT(11) NOT NULL AUTO_INCREMENT,
    `instruction` LONGTEXT,
    FOREIGN KEY (`link_id`) REFERENCES `upload_links`(`link_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `_settings` (
	`setting` VARCHAR(90) NOT NULL UNIQUE,
    `value` VARCHAR(90) NOT NULL
);

INSERT INTO `_database_version` (`version_id`, `version`) VALUES (1, "3.0");

INSERT INTO `_settings` (`setting`, `value`) VALUES ("maintenance_mode", 0);

CREATE UNIQUE INDEX `tag_unique` ON `tech_tip_tags` (`tip_id`, `sys_id`);

INSERT INTO `roles` (`role_id`, `role_name`, `role_home`) VALUES (1, "site admin", "/dashboard"), (2, "admin", "/dashboard"), (3, "report", "/dashboard"), (4, "tech", "/dashboard");

INSERT INTO `permissions` (`permission_id`, `permission_description`) VALUES (1, "site admin"), (2, "admin"), (3, "tech"), (4, "report"), (5, "open");

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (2, 2), (2, 3), (2, 4), (2, 5), (3, 3), (3, 4), (3, 5), (4, 3), (4, 5);

INSERT INTO `alert_levels` (`alert_level_id`, `description`) VALUES (1, "alert-success"), (2, "alert-info"), (3, "alert-warning"), (4, "alert-danger");

INSERT INTO `system_file_types` (`description`) VALUES ("Manuals"), ("Notes"), ("Handouts"), ("Firmware"), ("Software"), ("User Guides");

INSERT INTO `customer_file_types` (`description`) VALUES ("Backup"), ("Handout"), ("License"), ("Site Map"), ("Other");

INSERT INTO `customer_note_levels` (`note_level_id`, `description`) VALUES 
	(1, "info"), (2, "warning"), (3, "danger");
    
INSERT INTO `phone_number_types` (`phone_type_id`, `description`) VALUES 
	(1, "Work"), (2, "Home"), (3, "Cell");
';
