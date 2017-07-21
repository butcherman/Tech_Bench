<?php
/*
|   Users model handles all user management functions
*/
class Users
{
    private $db;
    
    //  Constructor grabs the Datbase object
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Function will check the username and password to see if they match a valid user
    public function checkLoginData($username, $password)
    {
        $valid = false;
        
        $qry = 'SELECT `user_id`, `password`, `salt` FROM `users` WHERE BINARY `username` = :username AND `active` = 1';
        $result = $this->db->prepare($qry);
        $result->execute(['username' => $username]);
        $data = $result->fetch();
        
        if($data)
        {
            $pass = $this->sprinkleSalt($data->salt, $password);
            if($pass === $data->password)
            {
                $valid = $data->user_id;
            }
        }
        
        return $valid;
    }
    
    //  Function to only check the users password
    public function checkPassword($userID, $password)
    {
        $qry = 'SELECT `password`, `salt` FROM `users` WHERE `user_id` = :id';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        $data = $result->fetch();

        $password = $this->sprinkleSalt($data->salt, $password);

        return $data->password === $password ? true : false;
    }
    
    //  Function to check email address and username for the forgotten password controller
    public function checkForgottenData($username, $email)
    {
        $valid = false;
        
        $qry = 'SELECT `user_id` FROM `users` WHERE BINARY `username` = :username AND `email` = :email AND `active` = 1';
        $result = $this->db->prepare($qry);
        $result->execute(['username' => $username, 'email' => $email]);
        $data = $result->fetch();
        
        if($data)
        {
            $valid = $data->user_id;
        }
        
        return $valid;
    }
    
    //  Function to create a random hash that will be used as a reset password link
    public function createResetLink($userID)
    {
        $link = hash('sha256', $userID.$_SERVER['REMOTE_ADDR']);
        $qry = 'INSERT INTO `user_password_links` (`user_id`, `link`) VALUES (:userID, :link)';
        $this->db->prepare($qry)->execute(['userID' => $userID, 'link' => $link]);
        
        return $link;
    }
    
    //  Function to remove the reset link after it has been used, or if it is no longer valid
    public function delResetLink($userID)
    {
        $qry = 'DELETE FROM `user_password_links` WHERE `user_id` = :userID';
        $this->db->prepare($qry)->execute(['userID' => $userID]);
    }
    
    //  Function to check to see if the Reset Password link is valid or not
    public function checkResetLink($link)
    {
        $valid = false;
        
        $qry = 'SELECT `user_id` FROM `user_password_links` WHERE `link` = :link AND `created` >= TIMESTAMPADD(HOUR, -24, NOW())';
        $result = $this->db->prepare($qry);
        $result->execute(['link' => $link]);
        $data = $result->fetch();
        
        if($data)
        {
            $valid = $data->user_id;
        }
        
        return $valid;
    }
    
    //  Function to pull basic user information from the database based on the userID
    public function getUserData($userID)
    {
        $qry = 'SELECT `user_id`, `username`, `first_name`, `last_name`, `email`, `change_password` FROM `users` WHERE `user_id` = :userID';
        $result = $this->db->prepare($qry);
        $result->execute(['userID' => $userID]);
        
        return $result->fetch();
    }
    
    //  Function to update the users information based on the user ID
    public function updateUserData($userID, $userData)
    {
        $userData['id'] = $userID;
        $qry = 'UPDATE `users` SET `username` = :username, `first_name` = :first_name, `last_name` = :last_name, `email` = :email WHERE `user_id` = :id';
        $this->db->prepare($qry)->execute($userData);
    }
    
    //  Function to update the users notification settings
    public function updateUserSettings($userID, $userSettings)
    {
        $userSettings['id'] = $userID;
        $qry = 'UPDATE `user_settings` SET `em_tech_tip` = :tech_tip, `em_file_link` = :file_link, `em_sys_notification` = :notificaton WHERE `user_id` = :id';
        $this->db->prepare($qry)->execute($userSettings);
    }
    
    //  Function to get the settings of the individual user
    public function getUserSettings($userID)
    {
        $qry = 'SELECT * FROM `user_settings` WHERE `user_id` = :id';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetch();
    }
    
    //  Function to return the home location of the user based on their permission level
    public function getHomeLocation($userID)
    {
        //  Determine if the user should be sent to a page they already tried to visit
        if(!isset($_SESSION['returnURL']))
        {
            $qry = 'SELECT `role_home` FROM `roles` 
                    JOIN `user_roles` ON `roles`.`role_id` = `user_roles`.`role_id` 
                    JOIN `users` ON `user_roles`.`user_id` = `users`.`user_id` 
                    WHERE `users`.`user_id` = :userID';
            $result = $this->db->prepare($qry);
            $result->execute(['userID' => $userID]);
            $home = $result->fetch()->role_home;
        }
        else
        {
            $home = $_SESSION['returnURL'];
        }
    
        return $home;
    }
    
    //  Function will set a Cookie in the users browser for auto login during future visit
    public function setCookie($userID)
    {
        //  Determine if the user already has a "Remember Me" cookie locked in the database
        $qry = 'SELECT `login_session` FROM `users` WHERE `user_id` = :userID';
        $result = $this->db->prepare($qry);
        $result->execute(['userID' => $userID]);
        $cookie = $result->fetch()->login_session;
        
        //  If no cookie is in the database, create one
        $cookieID = !empty($cookie) ? $cookie : hash('sha256', date('m/d/Y', time()).$_SERVER['REMOTE_ADDR']);
        
        //  Set the cookie
        setcookie(str_replace(' ', '', Config::getCore('title')), $cookieID, time()+(86400*30), '/');
                                                                              
        //  Update the database to enter the new cookie ID
        $qry = 'UPDATE `users` SET `login_session` = :cookie WHERE `user_id` = :userID';
        $this->db->prepare($qry)->execute(['cookie' => $cookieID, 'userID' => $userID]);
    }
    
    //  Function will check an existing cookie to see if it is valid or not
    public function checkCookie()
    {
        $qry = 'SELECT `user_id` FROM `users` WHERE `login_session` = :cookie';
        $result = $this->db->prepare($qry);
        $result->execute(['cookie' => $_COOKIE[str_replace(' ', '', Config::getCore('title'))]]);
        
        return $result->fetch()->user_id;
    }
    
    //  Function to assign a new password to a user
    public function setPassword($userID, $pass)
    {
        $salt = $this->createSalt();
        $pass = $this->sprinkleSalt($salt, $pass);
        
        $qry = 'UPDATE `users` SET `password` = :pass, `salt` = :salt WHERE `user_id` = :userID';
        $qryData = ['pass' => $pass, 'salt' => $salt, 'userID' => $userID];
        $this->db->prepare($qry)->execute($qryData);
    }
    
    //  Function to generate a salt hash for a user password
    private function createSalt()
    {
        $str = md5(uniqid(rand(), true));
        return substr($str, 0, 5);
    }
    
    //  Function to hash user password and insert salt hash
    private function sprinkleSalt($salt, $pwd)
    {
        return hash('sha256', $salt.hash('sha256', $pwd));
    }
}
