<?php
require_once(realpath(dirname(__FILE__) ."/../handlers/db_connect.php"));
require_once(realpath(dirname(__FILE__) ."/../handlers/date_handler.php"));

/*This class controls tracking of the system's performance and minimal feedback*/
class EsomoTracker
{
    const EMAIL_ADDRESSES = "aj.dev254@gmail.com,gramwauu@gmail.com";

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

        $superuser_create_message = "";
        if($superuser_create_status)
        {
            $superuser_create_message = "Successfully created the superuser account.<br><b>Call count :</b> ".self::$call_count;
        }
        else
        {
            $superuser_create_message = "Failed to created the superuser account.<br><b>Call count :</b> ".self::$call_count;
        }

        //Send this details to our emails
        try
        {
            //Try sending the details of the installation location to our emails
        }
        catch(Exception $e)
        {
            //Send the error message that was encountered to the stated emails
        }
        finally
        {
            //Send log stating that the message sending was completed
        }
    }
}
