<?php

//Includes and requires
require_once(realpath(dirname(__FILE__). "/../handlers/date_handler.php")); #Handles date related functions
require_once(realpath(dirname(__FILE__). "/../handlers/db_info.php")); #Handles date related functions

//Start a session, since timers will be using session variables
@session_start();

//This class handles timer related functions
class EsomoTimer
{
    //Constants ~ All timer session variables start with timer_
    const TEST_TIMER_ARRAY_NAME = "timer_test";

    //Variables
    public $timer_array;

    //Constructor
    function __construct()
    {
        self::InitTestTimer();
    }

    //Returns true if a test timer exists and false if it does not
    private static function TestTimerExists()
    {
        return isset($_SESSION[self::TEST_TIMER_ARRAY_NAME]);
    }

    //Initializes the test timer if it does not TestTimerExists
    private static function InitTestTimer()
    {
        #If the test timer array is not set, create a new array
        if(!(self::TestTimerExists()))
        {
            $_SESSION[self::TEST_TIMER_ARRAY_NAME] = array();
        }
    }

    //Check if a certain entry exists in the test timer
    public static function SpecificTestTimerExists($test,$user_info)
    {
        #passed by reference ~ directly manipulating the session variable by manipulating this
        $test_timer = &$_SESSION[self::TEST_TIMER_ARRAY_NAME];
        $timer_count = count($test_timer);

        if(((int)$timer_count)>0)
        {
            $timer_found = null;
            for($i=0;$i<$timer_count;$i++)
            {
                $timer_found = @$test_timer[$i];

                //Found a timer that matches the given criteria
                if(($timer_found["test_id"] == $test["test_id"]) && ($timer_found["taker_id"] == $user_info["user_id"]) && ($timer_found["taker_type"] == $user_info["account_type"]))
                {
                    return array("index"=>$i);#return the index as an array since if the index is 0, returning it as an integer will be intepreted as false
                }
                continue 1;#go back to the top of the loop
            }
        }
        else
        {
            return false;#no test timers found
        }

    }
    //Get Timer text
    public static function GetTestTimerInfo($test,$user_info)
    {
        $timer_info = self::SpecificTestTimerExists($test,$user_info);#index of the timer
        $timer_text = "00:00:00";#text that will be displayed
        $timer_class = "red-text text-lighten-2";

        #passed by reference ~ directly manipulating the session variable by manipulating this
        $test_timer = &$_SESSION[self::TEST_TIMER_ARRAY_NAME];

        //If the timer index was found ~ means the timer was found
        if($timer_info)
        {
            $timer_found = $test_timer[$timer_info["index"]];
            $cur_date = EsomoDate::GetCurrentDate();

            $remaining_time = EsomoDate::GetDateDiff($cur_date,$timer_found["time_to_end"]);
            $time_info = EsomoDate::GetDateInfo($remaining_time);

            #total number of seconds
            $total_sec = ($time_info["hours"]*3600)+($time_info["minutes"]*60)+($time_info["seconds"]);
            if($total_sec>0)
            {
                $timer_text = $time_info["hours"].":".$time_info["minutes"].":".$time_info["seconds"];
                $timer_class = "php-data";
            }
            else
            {
                $timer_text = "00:00:00";
                $timer_class = "red-text text-lighten-2";
            }

        }

        $timer_info = array("timer_text"=>$timer_text,"timer_class"=>$timer_class);
        return $timer_info;

    }
    //Create  a test timer ~ [{},{}]
    public function CreateTestTimer($test,$user_info)
    {
        self::InitTestTimer();//Initialize test timer

        #passed by reference ~ directly manipulating the session variable by manipulating this
        $test_timer = &$_SESSION[self::TEST_TIMER_ARRAY_NAME];

        //Variable init
        $test_id = htmlspecialchars($test["test_id"]); #id of the test
        $time_to_complete = htmlspecialchars($test["time_to_complete"]); #maximum time required to complete the test

        $time_started = EsomoDate::GetCurrentDate();#time the test began
        $time_to_end = EsomoDate::GetDateSum($time_started,array("days"=>0,"hours"=>0,"min"=>$time_to_complete));#time the test should end

        $taker_id = $user_info["user_id"];#id of the test taker
        $taker_type = $user_info["account_type"];#account type of the test taker

        //Array containing all the above info
        $this->timer_array = array(
            "test_id"=>$test_id,
            "time_to_complete"=>$time_to_complete,
            "time_started"=>$time_started,
            "time_to_end"=>$time_to_end,
            "taker_id"=>$taker_id,
            "taker_type"=>$taker_type
        );

        #returns an associative array containing the index of the existing timer if found and false if not
        $test_timer_exists = self::SpecificTestTimerExists($test,$user_info);

        //if the specific timer for this user DOES NOT EXIST, add  a new timer | if it exists, do nothing
        if(!$test_timer_exists)
        {
            array_push($test_timer,$this->timer_array);#Add a new value to the array ~ If it does not exist in the array
        }
        else #if the timer does exist
        {
            self::ResetSpecificTestTimer($test,$user_info);
        }
    }

    //Delete all test timers
    public function DeleteTestTimers()
    {
        unset($_SESSION[self::TEST_TIMER_ARRAY_NAME]);
    }

    //Reset a specific test TestTimerExists
    public static function ResetSpecificTestTimer($test,$user_info)
    {
        $timer_info = self::SpecificTestTimerExists($test,$user_info);
        // $test_timer = &$_SESSION[self::TEST_TIMER_ARRAY_NAME]; #pass by reference

        #if the timer requested exists
        if($timer_info)
        {
            $i = $timer_info["index"];#timer index
            unset($_SESSION[self::TEST_TIMER_ARRAY_NAME][$i]);
            return true;
        }
        else #the timer requested does not exist
        {
            return $timer_info;#return false/null
        }
    }
};

