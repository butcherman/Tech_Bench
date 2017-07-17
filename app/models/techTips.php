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
    
    //  Search the datbase for a tip
    public function searchTips($keyword, $system)
    {
        if(empty($system))
        {
            $qry = 'SELECT `tip_id`, `title`, `added_on` FROM `tech_tips` WHERE `title` LIKE :keyword1 OR `tip_id` LIKE :keyword2 ORDER BY `added_on` DESC';
            $data = [
                'keyword1' => '%'.$keyword.'%',
                'keyword2' => '%'.$keyword.'%'
            ];
        }
        else
        {
            $qry = 'SELECT `tech_tips`.`tip_id`, `title`, `added_on` FROM `tech_tips` 
                        LEFT JOIN `tech_tip_tags` ON `tech_tips`.`tip_id` = `tech_tip_tags`.`tip_id` 
                        WHERE (`title` LIKE :keyword1 OR `tech_tips`.`tip_id` LIKE :keyword2) 
                        AND `tech_tip_tags`.`sys_id` = (SELECT `sys_id` FROM `system_types` WHERE `name` = :system)  
                        ORDER BY `added_on` DESC';
            $data = [
                'keyword1' => '%'.$keyword.'%',
                'keyword2' => '%'.$keyword.'%',
                'system' => $system
            ];
        }
        
        $result = $this->db->prepare($qry);
        $result->execute($data);
        
        return $result->fetchAll();
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
    
    //  Determine if the tip is in the users favorites
    public function isTipFav($tipID, $userID)
    {
        $qry = 'SELECT COUNT(`user_id`) FROM `tech_tip_favs` WHERE `user_id` = :userID AND `tip_id` = :tipID';
        $result = $this->db->prepare($qry);
        $result->execute(['userID' => $userID, 'tipID' => $tipID]);
        
        return $result->fetchColumn() ? true : false;
    }
    
    //  Add the customer as a user favorite
    public function addTipFav($tipID, $userID)
    {
        $qry = 'INSERT INTO `tech_tip_favs` (`user_id`, `tip_id`) VALUES (:user, :tip)';
        $this->db->prepare($qry)->execute(['user' => $userID, 'tip' => $tipID]);
    }
    
    //  Remove the customer as a user favorite
    public function removeTipFav($tipID, $userID)
    {
        $qry = 'DELETE FROM `tech_tip_favs` WHERE `user_id` = :user AND `tip_id` = :tip';
        $this->db->prepare($qry)->execute(['user' => $userID, 'tip' => $tipID]);
    }
    
    //  Get all system tags associated with the tech tip
    public function getTipTags($tipID)
    {
        $qry = 'SELECT `system_types`.`name` FROM `system_types` LEFT JOIN `tech_tip_tags` ON `system_types`.`sys_id` = `tech_tip_tags`.`sys_id` WHERE `tech_tip_tags`.`tip_id` = :tipID';
        $result = $this->db->prepare($qry);
        $result->execute(['tipID' => $tipID]);
        
        return $result->fetchAll();
    }
}