<?php
/*
|   Systems model pulls all system information
*/
class Systems
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Function to return all system categories
    public function getCategories()
    {
        $qry = 'SELECT * FROM `system_categories`';
        $result = $this->db->query($qry);
        
        return $result->fetchAll();
    }
    
    //  Function to return all the systems for a specific category
    public function getSystems($category)
    {
        $qry = 'SELECT `name` FROM `system_types` JOIN `system_categories` ON `system_types`.`cat_id` = `system_categories`.`cat_id` WHERE `system_categories`.`description` = :category ORDER BY `folder_location` ASC';
        $result = $this->db->prepare($qry);
        $result->execute(['category' => $category]);
        
        return $result->fetchAll();
    }
    
    //  Get the ID of a specific system
    public function getSysID($sysName)
    {
        $valid = false;
        
        $qry = 'SELECT `sys_id` FROM `system_types` WHERE `name` = :sysName';
        $result = $this->db->prepare($qry);
        $result->execute(['sysName' => $sysName]);
        $data = $result->fetch();
        
        if($data)
        {
            $valid = $data->sys_id;
        }
        
        return $valid;
    }
    
    //  Get the types of files that are saved for systems
    public function getFileTypes()
    {
        $qry = 'SELECT `description` FROM `system_file_types`';
        $result = $this->db->query($qry);
        
        return $result->fetchAll();
    }
}