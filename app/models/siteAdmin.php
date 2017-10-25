<?php
/*
|   Site Admin model handels all primary system and system category information
*/

class siteAdmin
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Create a new system category
    public function createCategory($catName)
    {
        $qry = 'INSERT INTO `system_categories` (`description`) VALUES (:catname)';
        $this->db->prepare($qry)->execute(['catname' => $catName]);
    }
    
    //  Update the name of an existing system category
    public function updateCategory($catID, $newName)
    {
        $qry = 'UPDATE `system_categories` SET `description` = :name WHERE `cat_id` = :catID';
        $this->db->prepare($qry)->execute(['name' => $newName, 'catID' => $catID]);
    }
    
    //  Create a new system type
    public function createSystem($category, $system, $tables)
    {
        //  Arange columns and remove spaces
        $cols = [];
        $i = 1;
        do
        {
            if(!empty($tables['col'.$i]))
            {
                $cols[] = str_replace(' ', '_', $tables['col'.$i]);
            }
            $i++;
        }while(isset($tables['col'.$i]));
        
        //  Insert the system type into the database
        $data = [
            'category' => $category,
            'name' => $system,
            'folder' => str_replace(' ', '_', $system)
        ];
        $qry = 'INSERT INTO `system_types` (`cat_id`, `name`, `folder_location`) VALUES ((SELECT `cat_id` FROM `system_categories` WHERE `description` = :category), :name, :folder)';
        $this->db->prepare($qry)->execute($data);
        
        //  Create the databse table for the new system
        $qry = 'CREATE TABLE IF NOT EXISTS `data_'.$data['folder'].'` (
                    `data_id` INT(11) NOT NULL AUTO_INCREMENT,
                    `cust_id` INT(11) NOT NULL UNIQUE,';
        foreach($cols as $col)
        {
            $qry .= '`'.$col.'` VARCHAR(90), ';
        }
        $qry .= 'PRIMARY KEY(`data_id`), FOREIGN KEY(`cust_id`) REFERENCES `customers`(`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE )';
        $this->db->query($qry);
        
        return $data['folder'];
    }
    
    public function getSystemFolder($sysName)
    {
        $qry = 'SELECT `folder_location` FROM `system_types` WHERE `name` = :sysName';
        $result = $this->db->prepare($qry);
        $result->execute(['sysName' => str_replace('-', ' ', $sysName)]);
        
        $data = $result->fetch();
       
        return $data->folder_location;
    }
    
    //  Add a system alert
    public function addSystemAlert($message, $expire, $level)
    {
        $qry = 'INSERT INTO `broadcast_alerts` (`alert_level_id`, `description`, `expire_date`) VALUES ((SELECT `alert_level_id` FROM `alert_levels` WHERE `description` = :level), :msg, :expire)';
        $data = [
            'level' => 'alert-'.$level,
            'msg' => $message,
            'expire' => $expire
        ];
        $this->db->prepare($qry)->execute($data);
    }
    
    //  Add a user alert
    public function addUserAlert($message, $expire, $level, $user, $dismissable)
    {
        $qry = 'INSERT INTO `user_alerts` (`alert_level_id`, `user_id`, `description`, `expire_date`, `dismissable`) VALUES ((SELECT `alert_level_id` FROM `alert_levels` WHERE `description` = :level), :user, :message, :expire, :dismiss)';
        $data = [
            'level' => 'alert-'.$level,
            'user' => $user,
            'message' => $message,
            'expire' => $expire,
            'dismiss' => $dismissable
        ];
        $this->db->prepare($qry)->execute($data);
    }
}
