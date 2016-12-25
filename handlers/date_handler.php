<?php
interface EsomoDateFunctions
{
    #returns the text that will be used for due dates
    public static function GetDueText($date_difference = array("days"=>0,"hours"=>0,"minutes"=>0));
    
    #returns the optimal date and time used to display the text in browser
    public static function GetOptimalDateTime($date_input);
    
    #returns the difference between the sent date and the due date
    public static function GetDateDiff($date_sent,$due_date); 
    
    #Gets the date info and returns it in an array - takes a date_diff as a parameter    
    public static function GetDateInfo($date_diff);
}
class EsomoDate implements EsomoDateFunctions
{
    //returns the text that will be used for due dates
    public static function GetDueText($date_difference = array("days"=>0,"hours"=>0,"minutes"=>0))
    {
        $due_text = "";
        $due_class = "";

        if($date_difference["days"] < 0)
        {
            $due_text = "Late!";
        }
        elseif($date_difference["days"] == 0)
        {
            if($date_difference["hours"]>0)
            {
                $due_text = "Due in ".$date_difference["hours"]."h";
                if($date_difference["minutes"]>0)
                {
                    $due_text.=" and ".$date_difference["minutes"]."min";
                }
                elseif($date_difference["minutes"]==0)
                {
                    $due_text = "Due in ".$date_difference["hours"]."h";
                }
                else
                {
                    $due_text = "Late!";
                }
            }
            else
            {
                if($date_difference["minutes"]>0)
                {
                    $due_text="Due in  ".$date_difference["minutes"]."min";
                }
                elseif($date_difference["minutes"]==0)
                {
                    $due_text = "Due Today!";
                }
                else
                {
                    $due_text = "Late!";
                }
            }

        }
        elseif($date_difference["days"] == 1)
        {
            $due_text = "Due Tomorrow!";
        }
        else
        {
            $due_text = "Due in ".$date_difference["days"]." days";
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
        $years = $date_diff->format("%Y");
        $months = $date_diff->format("%M");
        $days = $date_diff->format("%D");
        $hours = $date_diff->format("%H");
        $minutes = $date_diff->format("%I");

        return array("years"=>$years,"months"=>$months,"days"=>$days,"hours"=>$hours,"minutes"=>$minutes);
    }
}