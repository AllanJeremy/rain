<?php
interface EsomoDateFunctions
{
    public static function GetDueText($date_difference = array("days"=>0,"hours"=>0,"minutes"=>0));#returns the text that will be used for due dates
    public static function GetOptimalDateTime($date_input);#returns the optimal date and time used to display the text in browser
    public static function GetDateDiff($date_sent,$due_date); #returns the difference between the sent date and the due date

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

    //returns the optimal date and time used to display the text in browser
    public static function GetOptimalDateTime($date_input)
    {

    }

    //returns the difference between the sent date and the due date
    public static function GetDateDiff($date_sent,$due_date)
    {
        date_diff();
    } 
    
}