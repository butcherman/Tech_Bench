<?php
/*
|   Default database views for the Tech Bench
|   Updated 7-14-17
|   Database Version 2.1
*/

$views = [];

$views[] = '
USE `'.$_SESSION['setupData']['dbName'].'`;
CREATE VIEW `sys_files_view` AS
    SELECT `system_files`.`name`, `system_files`.`description`, `system_files`.`file_id`, `files`.`file_name`, `system_files`.`added_on`, `system_files`.`user_id`, `system_types`.`name` AS `sys_name`, `system_file_types`.`description` AS `file_desc` FROM `system_files`
    JOIN `files` ON `system_files`.`file_id` = `files`.`file_id` 
    JOIN `system_file_types` ON `system_files`.`type_id` = `system_file_types`.`type_id` 
    JOIN `system_types` ON `system_files`.`sys_id` = `system_types`.`sys_id`;
    
CREATE VIEW `tech_tips_view` AS 
    SELECT `tech_tips`.`tip_id`, `tech_tips`.`title`, `tech_tips`.`added_on`, `tech_tip_details`.`details`,  `users`.`user_id` FROM `tech_tips` 
    JOIN `tech_tip_details` ON `tech_tips`.`tip_id` = `tech_tip_details`.`tip_id` 
    JOIN `users` ON `tech_tips`.`user_id` = `users`.`user_id`;
';
