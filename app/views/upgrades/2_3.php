<?php
$qry = '
DROP TABLE IF EXISTS `tech_tip_comments`;

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

UPDATE `_database_version` SET `version` = "2.3" WHERE `version_id` = 1;

CREATE TRIGGER `new_upload_link` AFTER INSERT ON `upload_links`
    FOR EACH ROW
    BEGIN
        INSERT INTO `upload_link_instructions` (`link_id`) VALUES (NEW.link_id);
    END;
';