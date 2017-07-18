<?php
/*
|   Customers model will handle all database information for customers
*/
class Customers
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Add a new customer to the database
    public function addCustomer($custData)
    {
        $data = [
            'id' => ltrim($custData['custID'], '0'),
            'name' => $custData['custName'],
            'dba' => $custData['custDBA'],
            'addr' => $custData['custAddr'],
            'city' => $custData['custCity'],
            'state' => $custData['state'],
            'zip' => $custData['zipCode']
        ];
        $qry = 'INSERT INTO `customers` (`cust_id`, `name`, `dba_name`, `address`, `city`, `state`, `zip`) VALUES (:id, :name, :dba, :addr, :city, :state, :zip)';
        $this->db->prepare($qry)->execute($data);
    }
    
    //  Search customer function will find a customer based on search paramaters
    public function searchCustomer($name = '', $city = '', $syst = '')
    {
        //  Select the full query based on whether or not the system type is being searched for as well
        if(!empty($syst))
        {
            $qry = 'SELECT `customers`.`cust_id`, `customers`.`name`, `customers`.`city`, `customers`.`state` FROM `customers` 
                LEFT JOIN `customer_systems` ON `customers`.`cust_id` = `customer_systems`.`cust_id` 
                LEFT JOIN `system_types` ON `customer_systems`.`sys_id` = `system_types`.`sys_id` 
                WHERE (`customers`.`name` LIKE :name1 OR `dba_name` LIKE :name2 OR `customers`.`cust_id` 
                LIKE :name3) AND `city` LIKE :city 
                AND `system_types`.`name` LIKE :syst 
                ORDER BY `customers`.`name` ASC';
            $data = [
                'name1' => '%'.$name.'%',
                'name2' => '%'.$name.'%',
                'name3' => '%'.$name.'%',
                'city'  => '%'.$city.'%',
                'syst' => $syst
            ];
        }
        else
        {
            $qry = 'SELECT `customers`.`cust_id`, `customers`.`name`, `customers`.`city`, `customers`.`state` FROM `customers` 
                    WHERE (`customers`.`name` LIKE :name1 OR `dba_name` LIKE :name2 OR `customers`.`cust_id` LIKE :name3) AND `city` LIKE :city 
                     ORDER BY `customers`.`name` ASC';
            $data = [
                'name1' => '%'.$name.'%',
                'name2' => '%'.$name.'%',
                'name3' => '%'.$name.'%',
                'city'  => '%'.$city.'%'
            ];
        }
        
        $result = $this->db->prepare($qry);
        $result->execute($data);
        
        return $result->fetchAll();
    }
    
    //  Pull the system types belonging to the customer
    public function getCustSystem($custID)
    {
        $qry = 'SELECT `system_types`.`name` FROM `system_types` 
                JOIN `customer_systems` ON `system_types`.`sys_id` = `customer_systems`.`sys_id` 
                WHERE `customer_systems`.`cust_id` = :custID';
        $result = $this->db->prepare($qry);
        $result->execute(['custID' => $custID]);
        
        return $result->fetchAll();
    }
    
    //  Pull all customer information from the database
    public function getCustData($custID)
    {
        $qry = 'SELECT `name`, `dba_name`, `address`, `city`, `state`, `zip` FROM `customers` WHERE `cust_id` = :custID';
        $result = $this->db->prepare($qry);
        $result->execute(['custID' => $custID]);
        
        return $result->fetch();
    }
    
    //  Determine if a customer is listed as a user favoirite
    public function isCustFav($custID, $userID)
    {
        $qry = 'SELECT COUNT(`user_id`) FROM `customer_favs` WHERE `user_id` = :userID AND `cust_id` = :custID';
        $result = $this->db->prepare($qry);
        $result->execute(['userID' => $userID, 'custID' => $custID]);        
        
        return $result->fetchColumn() ? true : false;
    }
    
    //  Add the customer as a user favorite
    public function addCustFav($custID, $userID)
    {
        $qry = 'INSERT INTO `customer_favs` (`user_id`, `cust_id`) VALUES (:user, :cust)';
        $this->db->prepare($qry)->execute(['user' => $userID, 'cust' => $custID]);
    }
    
    //  Remove the customer as a user favorite
    public function removeCustFav($custID, $userID)
    {
        $qry = 'DELETE FROM `customer_favs` WHERE `user_id` = :user AND `cust_id` = :cust';
        $this->db->prepare($qry)->execute(['user' => $userID, 'cust' => $custID]);
    }
    
    //  Edit the customers basic information
    public function updateCustData($custID, $dataArr)
    {
        $data = [
            'name' => $dataArr['custName'],
            'dba' => $dataArr['custDBA'],
            'address' => $dataArr['custAddr'],
            'city' => $dataArr['custCity'],
            'state' => $dataArr['state'],
            'zip' => $dataArr['zipCode'],
            'custID' => $custID
        ];
        $qry = 'UPDATE `customers` SET `name` = :name, `dba_name` = :dba, `address` = :address, `city` = :city, `state` = :state, `zip` = :zip WHERE `cust_id` = :custID';
        $this->db->prepare($qry)->execute($data);
    }
    
    //  Pull the system specific information for the customer
    public function getSysData($table, $tableCols, $custID)
    {
        $key = Config::getKey();
        $colQry = [];
        foreach($tableCols as $col)
        {
            $col = $col->COLUMN_NAME;
            if($col != 'data_id' && $col != 'cust_id')
            {
                $colQry[] = 'AES_DECRYPT(`'.$col.'`, "'.$key.'") AS `'.$col.'`';
            }
        }
        
        $qry = 'SELECT '.implode(', ', $colQry).' FROM  `'.$table.'` WHERE `cust_id` = :custID';
        $result = $this->db->prepare($qry);
        $result->execute(['custID' => $custID]);
        
        return $result->fetch();
    }
    
    //  Update the system specific information for the customer
    public function updateSysData($table, $tableCols, $custID, $data)
    {
        $key = Config::getKey();
        $colQry = [];
        $colValue = [];
        $i = 0;
        foreach($tableCols as $col)
        {
            $col = $col->COLUMN_NAME;
            if($col != 'data_id' && $col != 'cust_id')
            {
                $colQry[] = '`'.$col.'` = AES_ENCRYPT(:col'.$i.', "'.$key.'")';
                $colValue['col'.$i] = $data[$col];
                $i++;
            }
        }
        $colValue['custID'] = $custID;
        
        $qry = 'UPDATE `'.$table.'` SET '.implode(', ', $colQry).' WHERE `cust_id` = :custID';
        $this->db->prepare($qry)->execute($colValue);
    }
    
    //  Pull all customer contacts from the database
    public function getContacts($custID)
    {
        $qry = 'SELECT * FROM `customer_contacts` WHERE `cust_id` = :custID';
        $result = $this->db->prepare($qry);
        $result->execute(['custID' => $custID]);
        
        return $result->fetchAll();
    }
    
    //  Pull only one contact from the database
    public function getOneContact($contID)
    {
        $qry = 'SELECT * FROM `customer_contacts` WHERE `cont_id` = :id';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $contID]);
        
        return $result->fetch();
    }
    
    //  Add a new contact to the database
    public function addContact($custID, $contData)
    {
        $qry = 'INSERT INTO `customer_contacts` (`cust_id`, `name`, `phone`, `email`) VALUES (:custID, :name, :phone, :email)';
        $data = [
            'custID' => $custID,
            'name' => $contData['contName'],
            'phone' => $contData['contPhone'],
            'email' => $contData['contEmail']
        ];

        $this->db->prepare($qry)->execute($data);
    }
    
    //  Edit an existing contact to the database
    public function editContact($contID, $contData)
    {
        $qry = 'UPDATE `customer_contacts` SET `name` = :contName, `phone` = :contPhone, `email` = :contEmail WHERE `cont_id` = :contID';
        $contData['contID'] = $contID;
        $this->db->prepare($qry)->execute($contData);
    }
    
    //  Delete an existing contact from the database
    public function deleteContact($contID)
    {
        $qry = 'DELETE FROM `customer_contacts` WHERE `cont_id` = :id';
        if(!empty($contID))
        {
            $this->db->prepare($qry)->execute(['id' => $contID]);
        }
    }
    
    //  Get all customer notes
    public function getAllNotes($custID)
    {
        $qry = 'SELECT `note_id`, `subject`, `description`, `updated`, `user_id` FROM `customer_notes` WHERE `cust_id` = :id';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $custID]);
        
        return $result->fetchAll();
    }
    
    //  Get one customer note
    public function getNote($noteID)
    {
        $qry = 'SELECT `subject`, `description` FROM `customer_notes` WHERE `note_id` = :note';
        $result = $this->db->prepare($qry);
        $result->execute(['note' => $noteID]);
        
        return $result->fetch();
    }
    
    //  Add a new customer note
    public function addNewNote($custID, $userID, $noteData)
    {
        $data = [
            'custID' => $custID,
            'subject' => $noteData['noteSubject'],
            'body' => $noteData['noteDescription'],
            'user' => $userID
        ];
        $qry = 'INSERT INTO `customer_notes` (`cust_id`, `subject`, `description`, `user_id`) VALUES (:custID, :subject, :body, :user)';
        $this->db->prepare($qry)->execute($data);
    }
    
    //  Edit an existing note
    public function updateNote($noteID, $userID, $noteData)
    {
        $data = [
            'note' => $noteID,
            'user' => $userID,
            'subj' => $noteData['noteSubject'],
            'body' => $noteData['noteDescription']
        ];
        $qry = 'UPDATE `customer_notes` SET `subject` = :subj, `description` = :body, `updated` = CURRENT_TIMESTAMP, `user_id` = :user WHERE `note_id` = :note';
        $this->db->prepare($qry)->execute($data);
    }
    
    //  Get the types of files that can be added for a customer
    public function getFileTypes()
    {
        $qry = 'SELECT `description` FROM `customer_file_types`';
        $result = $this->db->query($qry);
        
        return $result->fetchAll();
    }
    
    //  Get all customer files
    public function getAllFiles($custID)
    {
        $qry = 'SELECT `customer_files`.`cust_file_id`, `customer_files`.`name`, `customer_files`.`file_id`, `customer_files`.`user_id`, `customer_files`.`added_on`, `files`.`file_name`, `customer_file_types`.`description` FROM `customer_files` 
        JOIN `files` ON `customer_files`.`file_id` = `files`.`file_id`  
        LEFT JOIN `customer_file_types` ON `customer_files`.`file_type_id` = `customer_file_types`.`file_type_id` 
        WHERE `cust_id` = :cust ORDER BY `customer_file_types`.`file_type_id` ASC';
        $result = $this->db->prepare($qry);
        $result->execute(['cust' => $custID]);
        
        return $result->fetchAll();
    }
    
    //  Get one specific file
    public function getFile($fileID)
    {
        $qry = 'SELECT `customer_files`.`file_id`, `customer_files`.`name`, `customer_file_types`.`description` FROM `customer_files` JOIN `customer_file_types` ON `customer_files`.`file_type_id` = `customer_file_types`.`file_type_id` WHERE `cust_file_id` = :fileID';
        $result = $this->db->prepare($qry);
        $result->execute(['fileID' => $fileID]);
        
        return $result->fetch();
    }
    
    //  Add a customer file
    public function addFile($custID, $userID, $fileData)
    {
        $fileData['custID'] = $custID;
        $fileData['userID'] = $userID;
        $qry = 'INSERT INTO `customer_files` (`file_id`, `cust_id`, `file_type_id`, `name`, `user_id`) VALUES (:fileID, :custID, (SELECT `file_type_id` FROM `customer_file_types` WHERE `description` = :type), :name, :userID)';
        $this->db->prepare($qry)->execute($fileData);
    }
    
    //  Edit a customer file
    public function editFile($fileID, $fileData)
    {
        $fileData['fileID'] = $fileID;
        $qry = 'UPDATE `customer_files` SET `name` = :fileName, `file_type_id` = (SELECT `file_type_id` FROM `customer_file_types` WHERE `description` = :fileType) WHERE `cust_file_id` = :fileID';
        $this->db->prepare($qry)->execute($fileData);
    }
    
    //  Delete a customer file
    public function deleteFile($fileID)
    {
        $qry = 'DELETE FROM `customer_files` WHERE `cust_file_id` = :fileID';
        if(!empty($fileID))
        {
            $this->db->prepare($qry)->execute(['fileID' => $fileID]);
        }
    }
}
