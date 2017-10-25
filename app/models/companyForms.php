<?php
/*
|   CompanyForms Model allows for the uploading and retrieving of company forms
*/

class CompanyForms
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Add a new company form/file
    public function addNewForm($formName, $fileID)
    {
        $qry = 'INSERT INTO `company_files` (`file_id`, `name`) VALUES (:file, :id)';
        $this->db->prepare($qry)->execute(['file' => $fileID, 'id' => $formName]);
    }
    
    //  Get all company forms/files
    public function getFiles()
    {
        $qry = 'SELECT `company_files`.`file_id`, `company_files`.`name`, `files`.`file_name` FROM `company_files` JOIN `files` ON `company_files`.`file_id` = `files`.`file_id` ORDER BY `company_files`.`name` ASC';
        $result = $this->db->query($qry);
        
        return $result->fetchAll();
    }
    
    //  Add a new user file
    public function addUserFile($userID, $fileName, $fileID)
    {
        $qry = 'INSERT INTO `user_files` (`user_id`, `file_id`, `name`) VALUES (:user, :file, :name)';
        $data = [
            'user' => $userID,
            'file' => $fileID,
            'name' => $fileName
        ];
        $this->db->prepare($qry)->execute($data);
    }
    
    //  Get all of the files for a specific user
    public function getUserFiles($userID)
    {
        $qry = 'SELECT `user_files`.`file_id`, `user_files`.`name`, `files`.`file_name` FROM `user_files` JOIN `files` ON `user_files`.`file_id` = `files`.`file_id` WHERE `user_id` = :id ORDER BY `user_files`.`name` ASC';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchAll();
    }
}
