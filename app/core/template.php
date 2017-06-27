<?php
/*
|   Template class creates static functions necessary for dynamic areas of themplate
*/

class Template
{
    //  Function to determine how many notification the user has
    public static function getNotifications()
    {
        $qry = 'SELECT COUNT(*) FROM `user_notifications` WHERE `user_id` = :userID AND `viewed` = 0';
        $result = Database::getDB()->prepare($qry);
        $result->execute(['userID' => $_SESSION['id']]);
        
        return $result->fetchColumn();
    }
    
    //  Determine if there are any administrative links for the user
    public static function getAdminLinks()
    {
        $adminLink = '';
        
        $qry = 'SELECT COUNT(*) FROM `role_permissions` 
                JOIN `user_roles` ON `role_permissions`.`role_id` = `user_roles`.`role_id` 
                JOIN `permissions` ON `role_permissions`.`permission_id` = `permissions`.`permission_id` 
                WHERE `user_roles`.`user_id` = :userID AND `permissions`.`permission_description` = :linkRole';
        $result = Database::getDB()->prepare($qry);
        
        //  Determine if the "Site Admin" link belongs on the page
        $result->execute(['userID' => $_SESSION['id'], 'linkRole' => 'site admin']);
        if($result->fetchColumn())
        {
            $adminLink .= '<li><span class="glyphicon glyphicon-home"></span> <a href="/site-administration">Site Administration</a></li>';
        }
        
        //  Determine if the "Admin" link belongs on the page
        $result->execute(['userID' => $_SESSION['id'], 'linkRole' => 'admin']);
        if($result->fetchColumn())
        {
            $adminLink .= '<li><span class="glyphicon glyphicon-lock"></span> <a href="/admin">Administration</a></li>';
        }
        
        //  Determine if the "Report" link belongs on the page
        $result->execute(['userID' => $_SESSION['id'], 'linkRole' => 'report']);
        if($result->fetchColumn())
        {
            $adminLink .= '<li><span class="glyphicon glyphicon-eye-open"></span> <a href="/reports">Reports</a></li>';
        }
        
        if(!empty($adminLink))
        {
            $adminLink = '<h3>Administration</h3><ul class="nav-toggle">'.$adminLink.'</ul>';
        }
        
        return $adminLink;
    }
    
    //  create the system category and system navigation menu's
    public static function getSysLinks()
    {
        $nav = '';
        
        //  Get the system categories
        $qry = 'SELECT * FROM `system_categories`';
        $catQry = Database::getDB()->query($qry);
        
        //  Cycle through each category and build the system navigations
        while($catObj = $catQry->fetch())
        {
            $parentMenu = [];
            $subMenu = [];
            
            //  Create the Category Header
            $nav .= '<h3>'.$catObj->description.'</h3>';
            $nav .= '<ul class="nav-toggle">';
            
            //  Pull all system types for the current category
            $qry = 'SELECT * FROM `system_types` WHERE `cat_id` = :cat ORDER BY `name`, `parent_id`';
            $result = Database::getDB()->prepare($qry);
            $result->execute(['cat' => $catObj->cat_id]);
            
            //  Cycle through systems and place into arrays based on primary and sub-systems
            while($sysObj = $result->fetch())
            {
                if(empty($sysObj->parent_id))
                {
                    $parentMenu[$sysObj->sys_id]['name'] = $sysObj->name;
                }
                else
                {
                    $subMenu[$sysObj->sys_id]['parent'] = $sysObj->parent_id;
                    $subMenu[$sysObj->sys_id]['name'] = $sysObj->name;
                    $parentMenu[$sysObj->parent_id]['parent'] = true;
                }
            }
            
            //  Use parent menu and sub menu arrays to build the navigation menus
            foreach($parentMenu as $key=>$menuItem)
            {
                //  Determine if the parent item has children or is a stand alone
                if(isset($menuItem['parent']) && $menuItem['parent'])
                {
                    $nav .= '<li class="folder-icn"><a href="/systems/'.$catObj->description.'/'.str_replace(' ', '-', $menuItem['name']).'">'.$menuItem['name'].'</a>';
                    $nav .= '<ul class="nav-toggle">';
                    //  Cycle through sub menus
                    foreach($subMenu as $sub)
                    {
                        if($sub['parent'] == $key)
                        {
                            $nav .= '<li><span class="glyphicon glyphicon-menu-hamburger"></span> <a href="/system/'.str_replace(' ', '-', $catObj->description).'/'.str_replace(' ', '-', $sub['name']).'">'.$sub['name'].'</a></li>';
                        }
                    }
                    $nav .= '</ul></li>';
                }
                else
                {
                    $nav .= '<li><span class="glyphicon glyphicon-menu-hamburger"></span> <a href="/system/'.str_replace(' ', '-', $catObj->description).'/'.str_replace(' ', '-', $menuItem['name']).'">'.$menuItem['name'].'</a></li>';
                }
            }
            
            $nav .= '</ul>';
        }
        
        return $nav;
    }
}
