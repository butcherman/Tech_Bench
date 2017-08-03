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
    
    public function addNewForm($formName, $fileID)
    {
        $qry = 'INSERT INTO `company_files` (`file_id`, `name`) VALUES (:file, :id)';
        $this->db->prepare($qry)->execute(['file' => $fileID, 'id' => $formName]);
    }
    
    public function getFiles()
    {
        $qry = 'SELECT `company_files`.`file_id`, `company_files`.`name`, `files`.`file_name` FROM `company_files` JOIN `files` ON `company_files`.`file_id` = `files`.`file_id` ORDER BY `company_files`.`name` ASC';
        $result = $this->db->query($qry);
        
        return $result->fetchAll();
    }
}