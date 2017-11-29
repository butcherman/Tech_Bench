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
    
    //  Delete an existing category
    public function deleteCategory($catName)
    {
        $success = false;
        
        $qry = 'DELETE FROM `system_categories` WHERE `description` = :category';
        try
        {
            $result = $this->db->prepare($qry);
            $result->execute(['category' => $catName]);
            
            $success = true;
        }
        catch (Exception $e)
        {
            $success = false;
        }
        
        return $success;
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
    
    public function basicSystemTable($tableName)
    {
        $qry = 'CREATE TABLE IF NOT EXISTS `'.$tableName.'` (
                    `data_id` INT(11) NOT NULL AUTO_INCREMENT,
                    `cust_id` INT(11) NOT NULL UNIQUE,
                    PRIMARY KEY(`data_id`), FOREIGN KEY(`cust_id`) REFERENCES `customers`(`cust_id`) 
                        ON DELETE CASCADE ON UPDATE CASCADE )';
        $this->db->query($qry);
    }
    
    public function deleteSystem($sysName)
    {
        $success = false;
        
        //  Get the database for the system 
        $table = 'data_'.$this->getSystemFolder($sysName);
        
        $qry = 'DELETE FROM `system_types` WHERE `name` = :sysName';
        try
        {
            $result = $this->db->prepare($qry);
            $result->execute(['sysName' => $sysName]);
            
            $success = true;
        }
        catch (Exception $e)
        {
            $success = false;
        }
        
        if($success)
        {
            $qry = 'DROP TABLE IF EXISTS `'.$table.'`';
            $this->db->query($qry);
        }
        
        return $success;
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
    
    //  Add a new file type for systems
    public function addSysFileType($newType)
    {
        $qry = 'INSERT INTO `system_file_types` (`description`) VALUES (:type)';
        $this->db->prepare($qry)->execute(['type' => $newType]);
    }
    
    //  Edit an existing file type for a system
    public function editSysFileType($name, $typeID)
    {
        $qry = 'UPDATE `system_file_types` SET `description` = :desc WHERE `type_id` = :id';
        $this->db->prepare($qry)->execute(['desc' => $name, 'id' => $typeID]);
    }
    
    //  Delete an exiting file type for a system
    public function delSysFileType($typeID)
    {
        $qry = 'DELETE FROM `system_file_types` WHERE `type_id` = :id';
        
        try
        {
            $result = $this->db->prepare($qry);
            $result->execute(['id' => $typeID]);
            
            $success = true;
        }
        catch (Exception $e)
        {
            $success = false;
        }
        
        return $success;
    }
    
    //  Check the databse tables for errors
    public function databaseCheck()
    {
        //  Turn off the "emulate Prepares" attribute
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        
        //  Get the list of tables in the databse
        $qry = 'SHOW TABLES';
        $result = $this->db->query($qry);
        $tables = $result->fetchAll(PDO::FETCH_COLUMN, 0);
        $result->closeCursor();

        $qry = 'CHECK TABLE '.implode(', ', $tables);
        $tbCheck = $this->db->query($qry)->fetchAll();
        
        
        
        print_r($tbCheck);
    }
}
