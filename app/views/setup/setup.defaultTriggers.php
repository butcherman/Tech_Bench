<?php
/*
|   Default database triggers for the Tech Bench
|   Updated 7-14-17
|   Database Version 2.1
*/

$triggers = [];

$triggers[] = '    
CREATE TRIGGER `'.$_SESSION['setupData']['dbName'].'`.`new_user_settings` AFTER INSERT ON `users`
    FOR EACH ROW
    BEGIN
        INSERT INTO `user_settings` (`user_id`) VALUES (NEW.user_id);
    END;
';

$triggers[] = '
CREATE TRIGGER `'.$_SESSION['setupData']['dbName'].'`.`new_upload_link` AFTER INSERT ON `upload_links`
    FOR EACH ROW
    BEGIN
        INSERT INTO `upload_link_instructions` (`link_id`) VALUES (NEW.link_id);
    END;
';
