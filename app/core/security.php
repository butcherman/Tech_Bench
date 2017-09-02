<?php
/*
|   Security class determines if a user has permissions to visit the page
*/

class Security
{
    private static $securityLevel = 'admin';
    
    //  Determine if the user is logged in with a proper username and ID from their session information
    public static function isLoggedIn()
    {
        $valid = false;
        if(isset($_SESSION['id']) && isset($_SESSION['username']))
        {
            $qry = 'SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = :userID AND `username` = :username';
            
            $result = Database::getDB()->prepare($qry);
            $result->execute(['userID' => $_SESSION['id'], 'username' => $_SESSION['username']]);
            
            $result->fetchColumn() ? $valid = true : $valid = false;
        }
        
        return $valid;
    }
    
    //  Set the security level of a Controller or Function within that controller
    public static function setPageLevel($level)
    {
        Self::$securityLevel = $level;
    }
    
    //  Check the security level of the page vs. the users security level of the user
    public static function doIBelong($allowMaint = false)
    {
        $valid = false;
        
        //  Determine if the system is in maintenance mode or not
        if(Template::inMaintenanceMode() && !$allowMaint)
        {
            $qry = 'SELECT COUNT(*) FROM `role_permissions` 
                    JOIN `user_roles` ON `role_permissions`.`role_id` = `user_roles`.`role_id` 
                    JOIN `permissions` ON `role_permissions`.`permission_id` = `permissions`.`permission_id` 
                    WHERE `user_roles`.`user_id` = :userID AND `permissions`.`permission_description` = :pageLevel';
            $result = Database::getDB()->prepare($qry);
            $result->execute(['userID' => $_SESSION['id'], 'pageLevel' => 'site admin']);
            
            $result->fetchColumn() ? $valid = true : $valid = false;
            
            if(!$valid)
            {
                header('Location: /err/maintenance');
                die();
            }
        }
        else if(Self::$securityLevel === 'open')
        {
            $valid = true;
        }
        else if(Self::isLoggedIn())
        {
            if(isset($_SESSION['changePassword']) && $_SESSION['changePassword'] && !preg_match('/^account/', $_GET['url']))
            {
                header('Location: /account/password');
                die();
            }
            $qry = 'SELECT COUNT(*) FROM `role_permissions` 
                    JOIN `user_roles` ON `role_permissions`.`role_id` = `user_roles`.`role_id` 
                    JOIN `permissions` ON `role_permissions`.`permission_id` = `permissions`.`permission_id` 
                    WHERE `user_roles`.`user_id` = :userID AND `permissions`.`permission_description` = :pageLevel';
            $result = Database::getDB()->prepare($qry);
            $result->execute(['userID' => $_SESSION['id'], 'pageLevel' => Self::$securityLevel]);
            
            $result->fetchColumn() ? $valid = true : $valid = false;
        }
        
        return $valid;
    }
    
    //  Function to get the real IP Address of the user
    public static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
