<?php
/*
|   Upgrade Controller is for upgrading the database to the newer version while maintaining all 
|   database data.
|
|   Note:  This is the only controller that does not rely on a model to handle all database 
|   functionality.  All necessary database functions are done in this controller itself.
|
|   All database scripts are stored in app/views/upgrades/
*/
class Upgrade extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('site admin');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  Landing page to initiate upgrade process
    public function index()
    {
        $data = [
            'appVersion' => VERSION,
            'expected' => DBVERSION,
            'actual' => Database::getVersion()
        ];

        $this->view('admin.site.upgrade', $data);
        $this->template('standard');
        $this->render();
    }
    
    //  Check the database version and run through the upgrade processes
    public function database()
    {
        $upQry = 'SELECT `version` FROM `_database_version` WHERE `version_id` = 1';
        $ver = Database::getDB()->query($upQry)->fetch()->version;
        while(DBVERSION != $ver)
        {
            $ver = str_replace('.', '_', Database::getVersion());
            $fnct = 'up_from_'.$ver;
            if(method_exists($this, $fnct))
            {
                $this->$fnct();
            }
            else
            {
                echo 'didnt work';
                break;
            }
            $ver = Database::getDB()->query($upQry)->fetch()->version;
        }
        
        $this->render('success');
    }
    
    //  Upgrade from database version 2.1 to version 2.3
    public function up_from_2_1()
    {
        require_once(__DIR__.'../../views/upgrades/2_3.php');
        
        //  Add new tables and trigger
        $db = Database::getDB();
        $db->exec($qry);
        
        //  Cycle through each system table (data_) and modify foreign key constraint
        $qry = 'SELECT `folder_location` FROM `system_types`';
        $result = $db->query($qry);
        $data = $result->fetchAll();
        
        foreach($data as $sys)
        {
            $qry = 'ALTER TABLE `data_'.$sys->folder_location.'` DROP FOREIGN KEY `data_'.$sys->folder_location.'_ibfk_1`';
            $db->query($qry);
            $qry = 'ALTER TABLE `data_'.$sys->folder_location.'` ADD FOREIGN KEY (`cust_id`) REFERENCES `customers`(`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE';
            $db->query($qry);
        }
    }
}