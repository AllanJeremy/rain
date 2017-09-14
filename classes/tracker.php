<?php
require_once(realpath(dirname(__FILE__) ."/../handlers/db_connect.php"));
require_once(realpath(dirname(__FILE__) ."/../handlers/date_handler.php"));
require_once(realpath(dirname(__FILE__) ."/../handlers/email_handler.php"));

/*This class controls tracking of the system's performance and minimal feedback*/
class EsomoTracker
{
    const DEV_EMAILS = "aj.dev254@gmail.com";
    const FROM_EMAIL = "aj.cgeek@gmail.com";#TODO ~ Change this email

    public static $ip;
    public static $date;
    public static $db_username;
    public static $db_name;
    public static $db_host;
    

    public static $call_count = 0; #Number of times this function has been called
    //Sends details of installation to the stated emails
    public static function SendInstallationDetails($superuser_create_status)
    {
        self::$call_count++;

        self::$ip = $_SERVER["REMOTE_ADDR"];
        self::$date = EsomoDate::GetCurrentDate();
        self::$db_host = DB_HOST;
        self::$db_name = DB_NAME;
        self::$db_username = DB_USERNAME;
        
        $subject = "New RAIN E-Learning system Installation";

        $superuser_create_message = "";
        if($superuser_create_status)
        {
            $superuser_create_message = "Successfully created the superuser account.<br><b>Call count :</b> ".self::$call_count;
        }
        else
        {
            $superuser_create_message = "Failed to created the superuser account.<br><b>Call count :</b> ".self::$call_count;
        }

        $to = self::DEV_EMAILS;
        $message = $superuser_create_message;
        $message .= "<br><h3>Additional Install information</h3>";
        $message .= "<p><b>IP Address : ".self::$ip."</p>";
        $message .= "<p><b>Date Installed : ".self::$date."</p>";
        $message .= "<p><b>Database host : ".self::$db_host."</p>";
        $message .= "<p><b>Database name : ".self::$db_name."</p>";
        $message .= "<p><b>Database username : ".self::$db_username."</p>";

        $send_status = null;
        //Send this details to our emails
        try
        {         
            //Try sending the details of the installation location to our emails
            //Headers for html email
            $email_data = array(
                "from"=>self::FROM_EMAIL,
                "to"=>self::DEV_EMAILS,
                "address_name"=> 'Rain Developers',
                "subject"=>$subject,
                "message"=>$message,
                "alt_message"=> ' ',
                "attachments"=>null,
                "cc"=>self::FROM_EMAIL
            );

            $result = EmailHandler::SendInstallationDetails($email_data);

//            $headers[] = 'MIME-Version: 1.0';
//            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
//
//            // Additional headers
//            $headers[] = 'To: '.self::DEV_EMAILS;
//            $headers[] = 'From: '.self::FROM_EMAIL;
//            $headers[] = 'Cc: '.self::FROM_EMAIL;
//
//            $send_status = mail($to, $subject, $message, implode("\r\n", $headers));
//
//            if($send_status)
//            {
//                echo "<p class='grey-text'>Sent html mail</p>";#TODO [debug] ~ remove
//            }
//            else
//            {
//                echo "<p class='grey-text'>Failed to send html mail</p>";#TODO [debug] ~ remove
//            }
            
        }
        catch(Exception $e)
        {
            $headers = 'From: '.self::FROM_EMAIL . "\r\n" .
                'Reply-To: '.self::DEV_EMAILS . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            //Send the error message that was encountered to the stated emails
            $send_status = mail($to, $subject, $superuser_create_message,$headers);

            if($send_status)
            {
                echo "<p class='grey-text'>Sent normal mail</p>";#TODO [debug] ~ remove
            }
            else
            {
                echo "<p class='grey-text'>Failed to normal mail</p>";#TODO [debug] ~ remove
            }
        }
        finally
        {
            $headers = 'From: '.self::FROM_EMAIL . "\r\n" .
                'Reply-To: '.self::DEV_EMAILS . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            //Send log stating that the message sending was completed
            // mail($to,$subject,"Installation tracking complete",$headers);

            
        }
    }
}
