<?php
/*
|   Logs Class consists of static functions that will write and retreive application logs.
|   All logs are located in the "/logs/" folder
*/

class Logs
{
    //  Default log folder location
    private static $logFolder = __DIR__.'/../../logs/';
    
    private function __construct(){}
    
    //  Write an error log
    public static function writeLog($logFile, $msg)
    {
        $logMsg = date('m/d/Y h:i:s a', time())."\t\t".$msg."\r\n";
        file_put_contents(self::$logFolder.$logFile.'.log', $logMsg, FILE_APPEND | LOCK_EX);
    }
}
