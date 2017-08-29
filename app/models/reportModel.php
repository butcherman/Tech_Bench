<?php
/*
|   Report Model runs all queries that will span multiple sections of the Tech Bench Application
*/

class ReportModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Get the number of system files a user has uploaded
    public function countSysFiles($userID, $last30 = false)
    {
        if(!$last30)
        {
            $qry = 'SELECT COUNT(`file_id`) FROM `system_files` WHERE `user_id` = :id';
        }
        else
        {
            $qry = 'SELECT COUNT(`file_id`) FROM `system_files` WHERE `user_id` = :id AND `added_on`  > CURDATE() - INTERVAL 30 DAY';
        }

        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchColumn();
    }
    
    //  Get the number of customer backups loaded
    public function countCustBackups($userID, $last30 = false)
    {
        if(!$last30)
        {
            $qry = 'SELECT COUNT(`file_id`) FROM `customer_files`
                    LEFT JOIN `customer_file_types` ON `customer_files`.`file_type_id` = `customer_file_types`.`file_type_id` WHERE `user_id` = :id AND `customer_file_types`.`description` = "backup"';
        }
        else
        {
            $qry = 'SELECT COUNT(`file_id`) FROM `customer_files`
                    LEFT JOIN `customer_file_types` ON `customer_files`.`file_type_id` = `customer_file_types`.`file_type_id` WHERE `user_id` = :id AND `customer_file_types`.`description` = "backup" AND `added_on`  > CURDATE() - INTERVAL 30 DAY';
        }

        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchColumn();
    }
    
    //  Get the number of customer files loaded that are not backups
    public function countCustFiles($userID, $last30 = false)
    {
        if(!$last30)
        {
            $qry = 'SELECT COUNT(`file_id`) FROM `customer_files`
                    LEFT JOIN `customer_file_types` ON `customer_files`.`file_type_id` = `customer_file_types`.`file_type_id` WHERE `user_id` = :id AND `customer_file_types`.`description` != "backup"';
        }
        else
        {
            $qry = 'SELECT COUNT(`file_id`) FROM `customer_files`
                    LEFT JOIN `customer_file_types` ON `customer_files`.`file_type_id` = `customer_file_types`.`file_type_id` WHERE `user_id` = :id AND `customer_file_types`.`description` != "backup" AND `added_on`  > CURDATE() - INTERVAL 30 DAY';
        }

        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchColumn();
    }
    
    //  Get the number of notes a user has updated
    public function countCustNotes($userID, $last30 = false)
    {
        if(!$last30)
        {
            $qry = 'SELECT COUNT(`note_id`) FROM `customer_notes` WHERE `user_id` = :id';
        }
        else
        {
            $qry = 'SELECT COUNT(`note_id`) FROM `customer_notes` WHERE `user_id` = :id AND `updated`  > CURDATE() - INTERVAL 30 DAY';
        }

        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchColumn();
    }
    
    //  Get the number of tech tips a user created
    public function countTechTips($userID, $last30 = false)
    {
        if(!$last30)
        {
            $qry = 'SELECT COUNT(`tip_id`) FROM `tech_tips` WHERE `user_id` = :id';
        }
        else
        {
            $qry = 'SELECT COUNT(`tip_id`) FROM `tech_tips` WHERE `user_id` = :id AND `added_on`  > CURDATE() - INTERVAL 30 DAY';
        }

        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchColumn();
    }
    
    //  Get the number of tech tip comments a user has done
    public function countTipComments($userID, $last30 = false)
    {
        if(!$last30)
        {
            $qry = 'SELECT COUNT(`comment_id`) FROM `tech_tip_comments` WHERE `user_id` = :id';
        }
        else
        {
            $qry = 'SELECT COUNT(`comment_id`) FROM `tech_tip_comments` WHERE `user_id` = :id AND `added_on`  > CURDATE() - INTERVAL 30 DAY';
        }

        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchColumn();
    }
    
    //  Find number of user logins for a specific month
    public function userLoginsPerMonth($userID, $month)
    {
        $qry = 'SELECT COUNT(`timestamp`) FROM `login_activity` WHERE `user_id` = :id AND MONTH(`timestamp`) = :month';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID, 'month' => $month]);
        
        return $result->fetchColumn();
    }
    
    //  Count the number of files in the system
    public function countFiles()
    {
        $qry = 'SELECT COUNT(`file_id`) FROM `files`';
        $result = $this->db->query($qry);
        
        return $result->fetchColumn();
    }
}