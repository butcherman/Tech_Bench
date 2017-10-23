<?php
$qry = '
CREATE TABLE IF NOT EXISTS `customer_note_levels` (
	`note_level_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(90) NOT NULL UNIQUE,
    PRIMARY KEY (`note_level_id`)
);

ALTER TABLE `customers` 
	ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT 1
    AFTER `added_on`;

INSERT INTO `customer_note_levels` (`note_level_id`, `description`) VALUES 
	(1, "info"), (2, "warning"), (3, "danger");

ALTER TABLE `customer_notes` 
	ADD COLUMN `note_level_id` INT(11) NOT NULL DEFAULT 1
    AFTER `note_id`;
    
ALTER TABLE `customer_notes` 
	ADD CONSTRAINT `customer_notes_ibfk_3` FOREIGN KEY (`note_level_id`) REFERENCES `customer_note_levels`(`note_level_id`) 
    ON UPDATE CASCADE;
    
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

CREATE TABLE IF NOT EXISTS `customer_linked_sites` (
	`parent_id` INT(11) NOT NULL,
    `cust_id` INT(11) NOT NULL,
    FOREIGN KEY (`parent_id`) REFERENCES `customers`(`cust_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`cust_id`) REFERENCES `customers`(`cust_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	UNIQUE KEY (`parent_id`, `cust_id`)
);

CREATE TABLE IF NOT EXISTS `user_files` (
	`user_file_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `file_id` INT(11) NOT NULL,
    `name` VARCHAR(90) NOT NULL,
    PRIMARY KEY (`user_file_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`file_id`) REFERENCES `files`(`file_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `phone_number_types` (`phone_type_id`, `description`) VALUES 
	(1, "Work"), (2, "Home"), (3, "Cell");

INSERT INTO `_settings` (`setting`, `value`) VALUES ("allow_upload_links", 1), ("allow_my_files", 1), ("allow_company_forms", 1);

UPDATE `_database_version` SET `version` = "3.0" WHERE `version_id` = 1;
';
