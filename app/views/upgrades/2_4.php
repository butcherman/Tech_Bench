<?php
$qry = '
    UPDATE `_database_version` SET `version` = "2.3" WHERE `version_id` = 1;
    
    CREATE TABLE IF NOT EXISTS `_settings` (
        `setting` VARCHAR(90) NOT NULL UNIQUE,
        `value` VARCHAR(90) NOT NULL
    );

    INSERT INTO `_settings` (`setting`, `value`) VALUES ("maintenance_mode", 0);
';