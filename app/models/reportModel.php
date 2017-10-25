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
    
    //  Get the full list of files and folders within a directory
    public function listFolderFiles($dir)
    {
        $results = [];
        $fileList = scandir($dir);
        
        unset($fileList[array_search('.', $fileList, true)]);
        unset($fileList[array_search('..', $fileList, true)]);
        
        if(count($fileList) > 0)
        {
            foreach($fileList as $file)
            {
                if(is_dir($dir.'/'.$file))
                {
                    $path = rtrim($dir.$file, '/').'/';
                    $results = array_merge($results, $this->listFolderFiles($path));
                }
                else
                {
                    $results[] = $dir.$file;
                }
            }
        }
        
        return $results;
    }
    
    //  Get the full list of files for a specific database folder
    public function listTableFiles($table)
    {
        $qry1 = 'SELECT `'.$table.'`.`file_id`, `files`.`file_name`, `files`.`file_link` FROM `'.$table.'` JOIN `files` ON `'.$table.'`.`file_id` = `files`.`file_id`';
        $qry2 = 'SELECT `files`.`file_name`, `files`.`file_link` FROM `files`';
        
        $qry = $table === 'files' ? $qry2 : $qry1;
        
        $result = $this->db->query($qry);
        
        return $result->fetchAll();
    }
    
    //  Compare the files that are in a directory, verses what is supposed to be in the database
    public function compareFiles($directory, $table)
    {        
        $path = $directory === 'root' ? Config::getFile('uploadRoot') : Config::getFile('uploadRoot').$directory;
        
        $fileList =  $this->listFolderFiles($path);
        $inDatabase = [];
        $tableList = $this->listTableFiles($table);
        
        foreach($tableList as $list)
        {
            $inDatabase[] = $list->file_link.$list->file_name;
        }
        
        $missingList = array_diff($inDatabase, $fileList);
        $missing = count($missingList);
        $unknownList = array_diff($fileList, $inDatabase);
        $unknown = count($unknownList);
        
        $validFiles = count($inDatabase) - $missing;
        
        $data = [
            'missing' => $missing, 
            'unknown' => $unknown, 
            'valid' => $validFiles,
            'missingList' => $missingList,
            'unknownList' => $unknownList
        ];
            
        return $data;
    }
}
