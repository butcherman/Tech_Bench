<?php
/*
|   dbBackup class will create a dump file of the system database
*/

use Ifsnop\Mysqldump as IMysqldump;

class DbBackup
{
    public static function runBackup()
    {
        $dbHost = Config::getDB('host');
        $dbUser = Config::getDB('dbUser');
        $dbPass = Config::getDB('dbPass');
        $dbName = Config::getDB('dbName');
        
        try 
        {
            $dump = new IMysqldump\Mysqldump("mysql:host=$dbHost;dbname=$dbName", "$dbUser", "$dbPass");
            $dump->start(__DIR__.'/../../backups/backup.sql');
        } 
        catch (\Exception $e) 
        {
            echo 'mysqldump-php error: ' . $e->getMessage();
        }
    }
}
