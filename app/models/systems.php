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
    
    //  Function to get the category ID
    public function getCategoryID($catName)
    {
        $qry = 'SELECT `cat_id` FROM `system_categories` WHERE `description` = :desc';
        $result = $this->db->prepare($qry);
        $result->execute(['desc' => $catName]);
        
        return $result->fetch()->cat_id;
    }
    
    //  Get system category
    public function getSysCategory($sysName)
    {
        $qry = 'SELECT `description` FROM `system_categories` JOIN `system_types` ON `system_categories`.`cat_id` = `system_types`.`cat_id` WHERE `system_types`.`name` = :name';
        $this->db->prepare($qry)->execute(['name' => $sysName]);
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
    
    //  Pull the files for a specific system and file type
    public function getSysFiles($sys, $fileType)
    {
        $qry = 'SELECT * FROM `sys_files_view` WHERE `sys_name` = :sys AND `file_desc` = :type';
        $result = $this->db->prepare($qry);
        $result->execute(['sys' => $sys, 'type' => $fileType]);
        
        return $result->fetchAll();
    }
    
    //  Pull the folder that the files are stored in for a selected system
    public function getSysFolder($sysName)
    {
        $qry = 'SELECT `system_types`.`folder_location`, `system_categories`.`description` FROM `system_types` JOIN `system_categories` ON `system_types`.`cat_id` = `system_categories`.`cat_id` WHERE `name` = :sysName';
        $result = $this->db->prepare($qry);
        $result->execute(['sysName' => str_replace('-', ' ', $sysName)]);
        
        $data = $result->fetch();
       
        return str_replace(' ', '_', $data->description).Config::getFile('slash').$data->folder_location.Config::getFile('slash');
    }
    
    //  Function to add a file to the System
    public function addSysFile($fileArr)
    {
        $qry = 'INSERT INTO `system_files` (`sys_id`, `type_id`, `file_id`, `name`, `description`, `user_id`) VALUES (:sysID, (SELECT `type_id` FROM `system_file_types` WHERE `description` = :fileType), :fileID, :name, :description, :user)';
        $this->db->prepare($qry)->execute($fileArr);        
    }
    
    //  Pull the name of the table that holds the customer's system information
    public function getSysTable($sysID)
    {
        $qry = 'SELECT `folder_location` FROM `system_types` WHERE `sys_id` = :sysID';
        $result = $this->db->prepare($qry);
        $result->execute(['sysID' => $sysID]);
        
        return 'data_'.$result->fetch()->folder_location;
    }
    
    //  Pull the table names about the system from the database
    public function getCols($table)
    {
        $qry = 'SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` = :schema AND `TABLE_NAME` = :table';
        $result = $this->db->prepare($qry);
        $result->execute(['schema' => Config::getDB('dbName'), 'table' => $table]);
        
        return $result->fetchAll();
    }
    
    //  Add a new system type for a customer
    public function addSysType($custID, $sysID, $sysData)
    {   
        //  Add the system type into the database
        $qry = 'INSERT INTO `customer_systems` (`cust_id`, `sys_id`) VALUES (:cust, :sys)';
        $this->db->prepare($qry)->execute(['cust' => $custID, 'sys' => $sysID]);
        
        //  Get the database informaiton necessary for the system data
        $table = $this->getSysTable($sysID);
        $cols  = $this->getCols($table);
        
        //  Create the query values for the database
        $key = Config::getKey();
        $colQry = [];
        $valQry = [];
        $colValue = [];
        $i = 0;
        foreach($cols as $col)
        {
            $col = $col->COLUMN_NAME;
            if($col != 'data_id' && $col != 'cust_id')
            {
                $colQry[] = '`'.$col.'`'; // = AES_ENCRYPT(:col'.$i.', "'.$key.'")';
                $valQry[] = 'AES_ENCRYPT(:col'.$i.', "'.$key.'")';
                $colValue['col'.$i] = $sysData[$col];
                $i++;
            }
        }
        $colValue['custID'] = $custID;
        
        //  Add the syste data to the databse
        $qry = 'INSERT INTO `'.$table.'` (`cust_id`, '.implode(', ', $colQry).') VALUES (:custID, '.implode(', ', $valQry).')';
        $this->db->prepare($qry)->execute($colValue);
    }
}