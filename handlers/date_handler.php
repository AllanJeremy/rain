<?php
interface EsomoDateFunctions
{
    #returns the text that will be used for due dates
    public static function GetDueText($due_date);
    
    #returns the optimal date and time used to display the text in browser
    public static function GetOptimalDateTime($date_input);
    
    #returns the difference between the sent date and the due date
    public static function GetDateDiff($date_sent,$date_due); 
    
    #Gets the date info and returns it in an array - takes a date_diff as a parameter    
    public static function GetDateInfo($date_diff);
}
class EsomoDate implements EsomoDateFunctions
{
    //returns the text that will be used for due dates
    public static function GetDueText($due_date)
    {
        //Variable initialization for the function
        $due_text = "";
        $due_class = "";
        
        //Finding the difference between the date today and the date before
        $date_today = date('Y-m-d H:i:s');
        $date_difference = self::GetDateDiff($date_today,$due_date);
        $date_info = self::GetDateInfo($date_difference); 

        if($date_info["days"] < 0 || $date_info["months"] < 0 || $date_info["years"])
        {
            $due_text = "Late!";
            $due_class = "red darken-1";
        }
        elseif($date_info["days"] == 0)
        {
            $due_class = "red darken-1";
            if($date_info["hours"]>0)
            {
                $due_text = "Due in ".$date_info["hours"]."h";
                if($date_info["minutes"]>0)
                {
                    $due_text.=" and ".$date_info["minutes"]."min";
                }
                elseif($date_info["minutes"]==0)
                {
                    $due_text = "Due in ".$date_info["hours"]."h";
                }
                else
                {
                    $due_text = "Late!";
                }
            }
            else
            {
                if($date_info["minutes"]>0)
                {
                    $due_text="Due in  ".$date_info["minutes"]."min";
                }
                elseif($date_info["minutes"]==0)
                {
                    $due_text = "Due Today!";
                }
                else
                {
                    $due_text = "Late!";
                }
            }

        }
        elseif($date_info["days"] == 1)
        {
            $due_text = "Due Tomorrow!";
        }
        else
        {
            $due_class = "light-blue darken-4";
            $due_text = "Due in ".$date_info["days"]." days";
        }

        return array("due_text"=>$due_text,"due_class"=>$due_class); 
    }
    //Returns a date item based on a phpmyadmin date
    private static function GetDbDate($date_input)
    {
        return DateTime::createFromFormat("Y-m-d H:i:s",$date_input);
    }

    //returns the optimal date and time used to display the text in browser
    public static function GetOptimalDateTime($date_input)
    {
        $date = self::GetDbDate($date_input);
        
        $day_found = $date->format("D");
        $date_found = $date->format("d M Y");
        $time_found = $date->format("h:ia");

        $date_time_output = array("day"=>$day_found,"date"=>$date_found,"time"=>$time_found);
        
        return $date_time_output;
    }

    //returns the difference between the sent date and the due date
    public static function GetDateDiff($date_sent,$date_due)
    {
        $sent = new DateTime($date_sent);
        $due = new DateTime($date_due);

        return date_diff($sent,$due);
    } 

    //Gets the date info and returns it in an array - takes a date_diff as a parameter    
    public static function GetDateInfo($date_diff)
    {
        $years = $date_diff->format("%r%y");
        $months = $date_diff->format("%r%m");
        $days = $date_diff->format("%r%d");
        $hours = $date_diff->format("%r%H");
        $minutes = $date_diff->format("%r%I");

        return array("years"=>$years,"months"=>$months,"days"=>$days,"hours"=>$hours,"minutes"=>$minutes);
    }
}