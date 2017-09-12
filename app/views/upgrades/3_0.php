<?php
$qry = '
CREATE TABLE IF NOT EXISTS `customer_note_levels` (
	`note_level_id` INT(11) NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(90) NOT NULL UNIQUE,
    PRIMARY KEY (`note_level_id`)
);

INSERT INTO `customer_note_levels` (`note_level_id`, `description`) VALUES 
	(1, "info"), (2, "warning"), (3, "danger");

ALTER TABLE `customer_notes` 
	ADD COLUMN `note_level_id` INT(11) NOT NULL DEFAULT 1
    AFTER `note_id`;
    
ALTER TABLE `customer_notes` 
	ADD CONSTRAINT `customer_notes_ibfk_3` FOREIGN KEY (`note_level_id`) REFERENCES `customer_note_levels`(`note_level_id`) 
    ON UPDATE CASCADE;





UPDATE `_database_version` SET `version` = '3.0' WHERE `version_id` = 1;
';