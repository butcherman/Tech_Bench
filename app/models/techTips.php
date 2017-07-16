<?php
/*
|   Tips model handles all Tech Tips/Knowledge Base information for the databse
*/

class TechTips
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    //  Add a new tech tip to the database
    public function createTip($title, $body, $user, $tags)
    {
        //  Create the tip
        $qry = 'INSERT INTO `tech_tips` (`title`, `user_id`) VALUES (:title, :user)';
        $this->db->prepare($qry)->execute(['title' => $title, 'user' => $user]);
        $tipID = $this->db->lastInsertID();
        
        //  Insert the tip body
        $qry = 'INSERT INTO `tech_tip_details` (`tip_id`, `details`) VALUES (:tipID, :details)';
        $this->db->prepare($qry)->execute(['tipID' => $tipID, 'details' => $body]);
        
        //  If there are any system tags, insert those into the database as well
        if(!empty($tags))
        {
            $tagQry = [];
            $tagData = [];
            $i = 1;
            foreach($tags as $tag)
            {
                $tagQry[] = '(:tip'.$i.', (SELECT `sys_id` FROM `system_types` WHERE `name` = :tag'.$i.'))';
                $tagData['tip'.$i] = $tipID;
                $tagData['tag'.$i] = $tag;
                $i++;
            }
            
            //  Run the query to add tags
            $qry = 'INSERT INTO `tech_tip_tags` (`tip_id`, `sys_id`) VALUES '.implode(',', $tagQry);
            $this->db->prepare($qry)->execute($tagData);
        }
        
        return $tipID;
    }
    
    //  link a file to a tech tip
    public function insertTipFile($tipID, $fileID)
    {
        $qry = 'INSERT INTO `tech_tip_files` (`tip_id`, `file_id`) VALUES (:tip, :file)';
        $this->db->prepare($qry)->execute(['tip' => $tipID, 'file' => $fileID]);
    }
    
    //  Get tip information from the tech bench
    public function getTipData($tipID)
    {
        $qry = 'SELECT * FROM `tech_tips_view` WHERE `tip_id` = :tipID';
        $result = $this->db->prepare($qry);
        $result->execute(['tipID' => $tipID]);
        
        return $result->fetch();
    }
}