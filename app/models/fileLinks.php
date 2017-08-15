<?php
/*
|   FileLinks Model handles all database funcitons for the file links controller
*/
class FileLinks
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Function to create a brand new file link
    public function createLink($subject, $expireDate, $userID)
    {
        $hash = substr(md5(uniqid(rand(), true)), 0, 20);
        $data = [
            'hash' => $hash,
            'name' => $subject,
            'expire' => $expireDate,
            'user' => $userID
        ];
        
        $qry = 'INSERT INTO `upload_links` (`link_hash`, `link_name`, `expire`, `allow_user_upload`, `user_id`) VALUES (:hash, :name, :expire, 0, :user)';
        $this->db->prepare($qry)->execute($data);
        
        return $this->db->lastInsertID();
    }
    
    //  Funciton to update an existing link
    public function updateLink($linkID, $linkData)
    {
        $qry = 'UPDATE `upload_links` SET `link_name` = :name, `expire` = :expire, `allow_user_upload` = :allow WHERE `link_id` = :link';
        $linkData['link'] = $linkID;
        $this->db->prepare($qry)->execute($linkData);
    }
    
    //  Function to remove a link from the database
    public function deleteLink($linkID)
    {
        $qry = 'DELETE FROM `upload_links` WHERE `link_id` = :link';
        if(!empty($linkID))
        {
            $this->db->prepare($qry)->execute(['link' => $linkID]);
        }
    }
    
    //  Function to get the ID of a link based on the link hash
    public function getLinkID($linkHash)
    {
        $qry = 'SELECT `link_id` FROM `upload_links` WHERE `link_hash` = :hash';
        $result = $this->db->prepare($qry);
        $result->execute(['hash' => $linkHash]);
        
        return $result->fetch();
    }
    
    //  Function to return the user ID of the user who created the link
    public function getLinkOwner($linkID)
    {
        $qry = 'SELECT `user_id` FROM `upload_links` WHERE `link_id` = :link';
        $result = $this->db->prepare($qry);
        $result->execute(['link' => $linkID]);
        
        $name = $result->fetch();
        return $name->user_id;
    }
    
    //  Determine if the link is valid or expired
    public function isLinkExpired($linkID)
    {
        $details = $this->getLinkDetails($linkID);
        
        return $details->expire <= date('Y-m-d') ? true : false;
    }
    
    //  Function to return the details about a link
    public function getLinkDetails($linkID)
    {
        $qry = 'SELECT `link_hash`, `link_name`, `expire`, `allow_user_upload` FROM `upload_links` WHERE `link_id` = :link';
        $result = $this->db->prepare($qry);
        $result->execute(['link' => $linkID]);
        
        return $result->fetch();
    }
    
    //  Function to get the links belonging to a specific user, or all users
    public function getLinks($userID = '')
    {
        if(empty($userID))
        {
            $qry = 'SELECT `link_id`, `link_name`, `expire`, `user_id` FROM `upload_links`';
            $result = $this->db->prepare($qry);
            $result->execute();
        }
        else
        {
            $qry = 'SELECT `link_id`, `link_name`, `expire` FROM `upload_links` WHERE `user_id` = :user';
            $result = $this->db->prepare($qry);
            $result->execute(['user' => $userID]);
        }
    
        return $result->fetchAll();
    }
    
    //  Get the files belonging to a link
    public function getLinkFiles($linkID)
    {
        $qry = 'SELECT `upload_link_files`.`added_on`, `upload_link_files`.`added_by`, `files`.`file_id`, `files`.`file_name`, `upload_link_notes`.`upload_note_id` FROM `upload_link_files` 
                LEFT JOIN `files` ON `upload_link_files`.`file_id` = `files`.`file_id` 
                LEFT JOIN `upload_link_notes` ON `files`.`file_id` = `upload_link_notes`.`file_id` 
                WHERE `upload_link_files`.`link_id` = :link 
                ORDER BY `upload_link_files`.`added_by`, `upload_link_files`.`added_on` ASC';
        $result = $this->db->prepare($qry);
        $result->execute(['link' => $linkID]);
        
        return $result->fetchAll();
    }
    
    //  Attach a file to a link ID
    public function insertLinkFile($linkID, $fileID, $userID)
    {
        $data = [
            'link' => $linkID,
            'file' => $fileID,
            'user' => $userID
        ];
        $qry = 'INSERT INTO `upload_link_files` (`link_id`, `file_id`, `added_by`) VALUES (:link, :file, :user)';
        $this->db->prepare($qry)->execute($data);
        
        return $this->db->lastInsertID();
    }
    
    //  Delete a file attached to a link
    public function deleteLinkFile($fileID)
    {
        $qry = 'DELETE FROM `files` WHERE `file_id` = :file';
        if(!empty($fileID))
        {
            $this->db->prepare($qry)->execute(['file' => $fileID]);
        }
    }
    
    //  Count how many files a link has
    public function countLinkFiles($linkID)
    {
        $qry = 'SELECT COUNT(`file_id`) FROM `upload_link_files` WHERE `link_id` = :link';
        $result = $this->db->prepare($qry);
        $result->execute(['link' => $linkID]);
        
        return $result->fetchColumn();
    }
    
    //  Get a note linked to a file
    public function getFileLinkNote($noteID)
    {
        $qry = 'SELECT `note` FROM `upload_link_notes` WHERE `upload_note_id` = :note';
        $result = $this->db->prepare($qry);
        $result->execute(['note' => $noteID]);
        
        return $result->fetch();
    }
    
    //  Add a note to a file
    public function insertFileLinkNote($fileID, $note)
    {
        $qry = 'INSERT INTO `upload_link_notes` (`file_id`, `note`) VALUES (:file, :note)';
        $this->db->prepare($qry)->execute(['file' => $fileID, 'note' => $note]);
    }
    
    //  Enable the ability for a user to upload files to a link
    public function allowLinkUpload($linkID)
    {
        $qry = 'UPDATE `upload_links` SET `allow_user_upload` = 1 WHERE `link_id` = :link';
        $this->db->prepare($qry)->execute(['link' => $linkID]);
    }
    
    //  Disable the ability for a user to upload files to a link
    public function disableLinkUpload($linkID)
    {
        $qry = 'UPDATE `upload_links` SET `allow_user_upload` = 0 WHERE `link_id` = :link';
        $this->db->prepare($qry)->execute(['link' => $linkID]);
    }
    
    //  Update a custom instruction
    public function updateLinkInstruction($linkID, $instruction)
    {
        $qry = 'UPDATE `upload_link_instructions` SET `instruction` = :ins WHERE `link_id` = :link';
        $this->db->prepare($qry)->execute(['ins' => $instruction, 'link' => $linkID]);
    }
    
    //  Get the custom instructions
    public function getLinkInstructions($linkID)
    {
        $qry = 'SELECT `instruction` FROM `upload_link_instructions` WHERE `link_id` = :link';
        $result = $this->db->prepare($qry);
        $result->execute(['link' => $linkID]);
        
        return $result->fetch();
    }
}