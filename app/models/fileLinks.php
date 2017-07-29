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
}