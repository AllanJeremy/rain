<?php
require_once(realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Session related functions ~ eg. login info
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Database info related functions
require_once(realpath(dirname(__FILE__) . "/../classes/timer.php")); #Timer related functions ~ eg. create timer

/*Handles AJAX Requests pertaining to the EsomoTimer*/
//POST requests
if(isset($_POST["action"]))
{
    $user_info = MySessionHandler::GetLoggedUserInfo();

    switch($_POST["action"])
    {
        case "StartTestTimer":
            $test = DbInfo::TestExists($_POST["test_id"]);#get the test
            
            $test_timer = new EsomoTimer();#create new timer object
            
            $test_timer->DeleteTestTimers();
            $test_timer->CreateTestTimer($test,$user_info);#create a test timer for the test provided and user provided
            
            $timer_array = $test_timer->timer_array;#array containing the created timer's information'
        break;

        default:
            echo "<p>Unknown request</p>";
    }
}



