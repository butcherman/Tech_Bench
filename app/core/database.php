<?php
/*
|   Database Class consists of static function that will allow for database queries to a MySQL database
*/

class Database
{
    private static $pdo, $result;
    
    //  Initialize the database
    public static function init()
    {   
        $dbHost = Config::getDB('host');
        $dbUser = Config::getDB('dbUser');
        $dbPass = Config::getDB('dbPass');
        $dbName = Config::getDB('dbName');
        $charset = 'utf8';
        $dsn = 'mysql:host='.$dbHost.';dbname='.$dbName.';charset='.$charset;
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        //  Try to connect to the database and create PDO object.
        try
        {
            self::$pdo = new PDO($dsn, $dbUser, $dbPass, $opt);
            //  Check to see if the database version matches the release version
            $qry = 'SELECT `version` FROM `_database_version` WHERE `version_id` = 1';
            $result = self::$pdo->query($qry)->fetch()->version;
            if($result != DBVERSION && $_GET['url'] != 'err/no-db')
            {
                $msg = 'Database is running incorrect version '.$result.', expecting '.DBVERSION;
                Logs::writeLog('Database-Error', $msg);
                header('Location: /err/no-db');
                die();
            }
        }
        catch(PDOException $e)
        {
            //  If there is an error connecting, redirect to the error page (if not already there)
            if($_GET['url'] != 'err/no-db')
            {
                $msg = 'Connection Failed: '.$e->getMessage();
                Logs::writeLog('Database-Error', $msg);
                header('Location: /err/no-db');
                die();
            }
        }
    }
    
    //  Return the database object
    public static function getDB()
    {
        return self::$pdo;
    }
}
