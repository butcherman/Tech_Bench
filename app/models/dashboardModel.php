<?php
/*
    Dashboard Model will handle all database calls for the primary landing or Dashboard page
*/

class DashboardModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getDB();
    }
    
    // Function to get system alerts
    public function getSystemAlerts()
    {
        $qry = 'SELECT `broadcast_alerts`.`bdcst_alert_id`, `broadcast_alerts`.`description`, `alert_levels`.`description` AS `alert_level` FROM `broadcast_alerts` 
                LEFT JOIN `alert_levels` ON `broadcast_alerts`.`alert_level_id` = `alert_levels`.`alert_level_id` 
                WHERE `expire_date` >= CURDATE()';
        $result = $this->db->query($qry);
        
        return $result->fetchAll();
    }
    
    //  Function to get a specific user alerts
    public function getUserAlerts($userID)
    {
        $qry = 'SELECT `user_alerts`.`user_alert_id`, `user_alerts`.`description`, `alert_levels`.`description` AS `alert_level` FROM `user_alerts` 
                LEFT JOIN `alert_levels` ON `user_alerts`.`alert_level_id` = `alert_levels`.`alert_level_id` 
                WHERE `user_alerts`.`user_id` = :user AND `user_alerts`.`dismissed` = 0 AND `expire_date` >= CURDATE()';
        $result = $this->db->prepare($qry);
        $result->execute(['user' => $userID]);
        
        return $result->fetchAll();
    }
    
    //  Function to delete a user alert
    public function deleteUserNotification($alertID)
    {
        $qry = 'DELETE FROM `user_alerts` WHERE `user_alert_id` = :id';
        $this->db->prepare($qry)->execute(['id' => $alertID]);
    }
    
    //  Functions to get all notifications for a user
    public function getNotifications($id)
    {
        $qry = 'SELECT * FROM `user_notifications` WHERE `user_id` = :id ORDER BY `added_on` DESC';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $id]);
        
        return $result->fetchAll();
    }
    
    //  Function to mark a user notification
    public function markNotification($noteID)
    {
        $qry = 'UPDATE `user_notifications` SET `viewed` = 1 WHERE `notification_id` = :id';
        $this->db->prepare($qry)->execute(['id' => $noteID]);
    }
    
    //  Function to delete a user notification
    public function deleteNotification($noteID)
    {
        $qry = 'DELETE FROM `user_notifications` WHERE `notification_id` = :id';
        if(!empty($noteID))
        {
            $this->db->prepare($qry)->execute(['id' => $noteID]);
        }
    }
    
    //  Function to get the customer favorites for a user
    public function getCustFavs($userID)
    {
        $qry = 'SELECT `customers`.`cust_id`, `customers`.`name` FROM `customers` 
                JOIN `customer_favs` ON `customers`.`cust_id` = `customer_favs`.`cust_id` 
                WHERE `customer_favs`.`user_id` = :id';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchAll();
    }
    
    //  Function to get the tech tip favorites for a user
    public function getTechTipFavs($userID)
    {
        $qry = 'SELECT `tech_tips`.`tip_id`, `tech_tips`.`title` FROM `tech_tips` 
                JOIN `tech_tip_favs` ON `tech_tips`.`tip_id` = `tech_tip_favs`.`tip_id` 
                WHERE `tech_tip_favs`.`user_id` = :id';
        $result = $this->db->prepare($qry);
        $result->execute(['id' => $userID]);
        
        return $result->fetchAll();
    }
}