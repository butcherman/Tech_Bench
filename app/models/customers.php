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
    
    //  Search customer function will find a customer based on search paramaters
    public function searchCustomer($name = '', $city = '', $syst = '')
    {
        $qry = 'SELECT `customers`.`cust_id`, `customers`.`name`, `customers`.`city`, `customers`.`state` FROM `customers` 
                LEFT JOIN `customer_systems` ON `customers`.`cust_id` = `customer_systems`.`cust_id` 
                LEFT JOIN `system_types` ON `customer_systems`.`sys_id` = `system_types`.`sys_id` 
                WHERE (`customers`.`name` LIKE :name1 OR `dba_name` LIKE :name2 OR `customers`.`cust_id` LIKE :name3) AND `city` LIKE :city';
        $data = [
            'name1' => '%'.$name.'%',
            'name2' => '%'.$name.'%',
            'name3' => '%'.$name.'%',
            'city'  => '%'.$city.'%'
        ];
        if(!empty($syst))
        {
            $qry .= ' AND `system_types`.`name` LIKE :syst';
            $data['syst'] = $syst;
        }
        $qry .= ' ORDER BY `customers`.`name` ASC';
        
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
        foreach($tableCols as $col)
        {
            $col = $col->COLUMN_NAME;
            if($col != 'data_id' && $col != 'cust_id')
            {
                $colQry[] = '`'.$col.'` = AES_ENCRYPT(`'.$data[$col].'`, "'.$key.'")';
            }
        }
        
        $qry = 'UPDATE `'.$table.'` SET '.implode(', ', $colQry).' WHERE `cust_id` = :custID';
        $this->db->prepare($qry)->execute(['custID' => $custID]);
    }
}