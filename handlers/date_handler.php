<?php

require_once(realpath(dirname(__FILE__) ."/../handlers/global_init_handler.php")); #global settings initialization

//Interface containing functions that must be implemented by the EsomoDate class
interface EsomoDateFunctions
{
    #returns the text that will be used for due dates
    public static function GetDueText($due_date);
    
    #returns the optimal date and time used to display the text in browser
    public static function GetOptimalDateTime($date_input);
    
    #returns the difference between the sent date and the due date
    public static function GetDateDiff($date_sent,$date_due); 
    
    #Gets the date info and returns it in an array - takes a date_input as a parameter    
    public static function GetDateInfo($date_input);

    #returns the current date the database date format
    public static function GetCurrentDate();

    #Return yesterday in db format ~ returns date yesterday
    public static function GetYesterday();

    #Return yesterday in db format ~ returns date 7 days ago
    public static function Get7DaysAgo();

    #Return yesterday in db format ~ returns start and end
    public static function GetThisMonth();

    #Return last month in db format ~ returns start and end
    public static function GetLastMonth();

    #Return 30 days ago in db format ~ returns the date 30 days ago
    public static function Get30DaysAgo();
}

class EsomoDate implements EsomoDateFunctions
{
    const DB_DATE_FORMAT = 'Y-m-d H:i:s';
    const DB_FIRST_MONTH_DAY = 'Y-m-01 H:i:s';
    const DB_LAST_MONTH_DAY = 'Y-m-t H:i:s';

    public static function Negate($number)
    {
        return (-1 * abs($number));
    }
    //returns the text that will be used for due dates
    public static function GetDueText($due_date)
    {
        //Variable initialization for the function
        $due_text = "";
        $due_class = "";
        
        //Finding the difference between the date today and the date before
        $date_today = date(self::DB_DATE_FORMAT);
        $date_difference = self::GetDateDiff($date_today,$due_date);
        
        //If the date difference is a negative number
        if($date_difference->invert==1)
        {
            $date_difference->y = self::Negate($date_difference->y);
            $date_difference->m = self::Negate($date_difference->m);
            $date_difference->d = self::Negate($date_difference->d);
            $date_difference->h = self::Negate($date_difference->h);
            $date_difference->i = self::Negate($date_difference->i);
            $date_difference->s = self::Negate($date_difference->s);
        }

        //the date has passed
        if($date_difference->d < 0 || $date_difference->m < 0 || $date_difference->y<0)
        {
            $due_text = "Late!";
            $due_class = "red darken-1";
        }
        #the date is today
        elseif($date_difference->d == 0 && $date_difference->m == 0 && $date_difference->y == 0)
        {
            $due_class = "red darken-1";
            if($date_difference->h > 0)
            {
                $due_text = "Due in ".$date_difference->h."h";
                if($date_difference->i>0)
                {
                    $due_text.=" and ".$date_difference->i."min";
                }
                elseif($date_difference->i==0)
                {
                    $due_text = "Due in ".$date_difference->h."h";
                }
                else
                {
                    $due_text = "Late!";
                }
            }
            else
            {
                if($date_difference->i>0)
                {
                    $due_text="Due in  ".$date_difference->i."min";
                }
                elseif($date_difference->i==0)
                {
                    $due_text = "Due Today!";
                }
                else
                {
                    $due_text = "Late!";
                }
            }

        }
        #one day left
        elseif($date_difference->d == 1 && $date_difference->m == 0 && $date_difference->y == 0)
        {
            $due_text = "Due Tomorrow!";
        }
        else
        {
            $due_class = "light-blue darken-4";
            $due_text = "Due in ".$date_difference->d." days";
        }

        return array("due_text"=>$due_text,"due_class"=>$due_class); 
    }
    //Returns a date item based on a phpmyadmin date
    private static function GetDbDate($date_input)
    {
        return DateTime::createFromFormat(self::DB_DATE_FORMAT,$date_input);
    }

    //returns the optimal date and time used to display the text in browser
    public static function GetOptimalDateTime($date_input)
    {
        $date = self::GetDbDate($date_input);
        
        $day_found = $date->format("D");
        $date_found = $date->format("d M Y");
        $time_found = $date->format("g:ia");

        $date_time_output = array("day"=>$day_found,"date"=>$date_found,"time"=>$time_found);
        
        return $date_time_output;
    }

    //returns the difference between the start date and the end date
    public static function GetDateDiff($date_start,$date_end)
    {   
        //Initialization ~ so that the variables are accessible in the entire scope of the function
        $start = null;
        $end = null;
        if(!is_a($date_start,"DateTime"))
        {
            $start = new DateTime($date_start);
        }
        else
        {
            $start = $date_start;
        }

        if(!is_a($date_end,"DateTime"))
        {
            $end = new DateTime($date_end);
        }
        else
        {
            $end = $date_end;
        }

        return date_diff($start,$end);
    } 

    //Returns the sum of a date and an interval - #date_interval is an array that contains days hours minutes
    public static function GetDateSum($date_input, $date_interval=array("days"=>0,"hours"=>0,"min"=>10))
    {
        $date = "";
        //If the time entered is not date time, create a new date time from it.
        if(!is_a($date_input,"DateTime") && is_string($date_input))
        {
            $date = new DateTime($date_input);
        }
        else //otherwise, if it is datetime, then set the date equal to it
        {
            $date = $date_input;
        }
        $interval = "P".$date_interval["days"]."DT".$date_interval["hours"]."H".$date_interval["min"]."M";
        $date_sum = $date->add(new DateInterval($interval));

        return $date_sum->format(self::DB_DATE_FORMAT);
    }

    //Get the current date. Return a database friendly type
    public static function GetCurrentDate()
    {
        return date(self::DB_DATE_FORMAT);
    }

    //Return whether the current time has already elapsed/passed ~ true if it has and false if not
    public static function DateTimeHasElapsed($date_time_input)
    {
        $current_date_time = strtotime(self::GetCurrentDate());
        return ($current_date_time > $date_time_input);
    }

    //Gets the date info and returns it in an array - takes a date_input as a parameter
    public static function GetDateInfo($date_input)
    {
        $date = null;
        //If the time entered is not date time, create a new date time from it.
        if(!is_a($date_input,"DateTime") && is_string($date_input))
        {
            $date = new DateTime($date_input);
        }
        else //otherwise, if it is datetime, then set the date equal to it
        {
            $date = $date_input;
        }

        $years = $date->format("%r%y");
        $months = $date->format("%r%m");
        $days = $date->format("%r%d");
        $hours = $date->format("%r%H");
        $minutes = $date->format("%r%I");
        $seconds = $date->format("%r%S");

        return array("years"=>$years,"months"=>$months,"days"=>$days,"hours"=>$hours,"minutes"=>$minutes,"seconds"=>$seconds);
    }

    /*TIMEFRAME FUNCTIONS*/
    //Returns the date - the number of days provided from the current date
    protected static function GetMonthDiff($months)
    {
        $today =  new DateTime(self::GetCurrentDate());
        $diff = $today->modify("-".$months." month");

        return $diff;
    }
    //Returns the date - the number of days provided from the current date
    protected static function GetDayDiff($days)
    {
        $today =  new DateTime(self::GetCurrentDate());
        
        $diff = $today->modify('-'.$days.' day');

        return $diff;
    }

    // Return yesterday in db format ~ returns date yesterday
    public static function GetYesterday()
    {
        return self::GetDayDiff(1)->format(EsomoDate::DB_DATE_FORMAT);
    }

    // Return yesterday in db format ~ returns date 7 days ago
    public static function Get7DaysAgo()
    {
        return self::GetDayDiff(7)->format(EsomoDate::DB_DATE_FORMAT);
    }

    // Return yesterday in db format ~ returns start and end
    public static function GetThisMonth()
    {
        $today =  new DateTime(self::GetCurrentDate());
        
        $month_start = $today->format(self::DB_FIRST_MONTH_DAY);

        $month_dates = array("start"=>$month_start,"end"=>$today->format(EsomoDate::DB_DATE_FORMAT));

        return $month_dates;
    }

    // Return last month in db format ~ returns start and end
    public static function GetLastMonth()
    {
        $today =  self::GetCurrentDate();
        $prev_month = self::GetMonthDiff(1);#Previous month date

        $prev_month_start = $prev_month->format(self::DB_FIRST_MONTH_DAY);
        $prev_month_end = $prev_month->format(self::DB_LAST_MONTH_DAY);

        
        $month_dates = array("start"=>$prev_month_start,"end"=>$prev_month_end);

        return $month_dates;
    }

    // Return 30 days ago in db format ~ returns the date 30 days ago
    public static function Get30DaysAgo()
    {
        return (self::GetDayDiff(30)->format(EsomoDate::DB_DATE_FORMAT));
    }
}#End of class
