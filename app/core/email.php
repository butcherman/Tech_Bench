<?php
/*
|   Email class uses the PHPMailer library to create and send emails
*/
class Email
{
    private static $mail, $body, $sendTo;
    
    public static function init()
    {        
        //  Setup initial paramaters for the email
        self::$mail = new PHPMailer(true);
        self::$mail->IsSMTP();
        self::$mail->Mailer = 'smtp';
        self::$mail->Host = Config::getEmail('emHost');
        self::$mail->Port = Config::getEmail('emPort');
        self::$mail->SMTPAuth = true;
        self::$mail->Username = Config::getEmail('emUser');
        self::$mail->Password = Config::getEmail('emPass');
        self::$mail->setFrom(Config::getEmail('emFrom'));
        self::$mail->IsHTML(true);
    }
    
    //  Create the subject line of the email
    public static function addSubject($subject)
    {
        self::$mail->Subject = $subject;
    }
    
    //  Add a reciepent email address - note:  input can be both an array or standard string
    public static function addUser($emailAddr)
    {
        if(is_array($emailAddr))
        {
            foreach($emailAddr as $em)
            {
                self::$mail->addAddress($em);
            }
        }
        else
        {
            self::$mail->addAddress($emailAddr);
        }
    }
    
    //  Set the body of the email
    public static function addBody($msg)
    {
        self::$mail->msgHTML($msg);
    }
    
    //  Send the email
    public static function send()
    {
        $success = 'success';

        try
        {
            self::$mail->send();
            Logs::writeLog('Email', 'Email Sent');
        }
        catch(phpmailerException $e)
        {
            Logs::writeLog('Email', 'Message Failed: '.$e);
            $success = false;
        }
        catch(Exception $e)
        {
            Logs::writeLog('Email', 'Message Failed: '.$e);
            $success = false;
        }
        
        return $success;
    }
}
