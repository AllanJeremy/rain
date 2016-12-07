<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_handler.php"));#Updates information in the database

#Functions used by the schedule
interface ScheduleFunctions
{
    public static function CreateSchedule($schedule_title,$schedule_description,$teacher_id,$class_id,$schedule_date,$schedule_time);#create a schedule
    public static function MarkAttendedSchedule($schedule_id,$teacher_id);#mark a schedule as attended
    public static function UnmarkAttendedSchedule($schedule_id,$teacher_id);#unmark attended schedule

}

#HANDLES SCHEDULE RELATED FUNCTIONS
class Schedule implements ScheduleFunctions
{
    //Update the attended_schedule column - helper function
    private static function UpdateAttendedSchedule($schedule_id,$teacher_id,$attended_schedule)
    {
        global $dbCon;

        $update_query = "UPDATE schedules SET attended_schedule=? WHERE schedule_id=? AND teacher_id=?";
        if ($update_stmt = $dbCon->prepare($update_query))
        {
            $update_stmt->bind_param("iii",$attended_schedule,$schedule_id,$teacher_id);
            if($update_stmt->execute())
            {
                return true;#successfully updated the attended schedule column
            }
            else
            {
                return false;#failed to update the attended schedule column
            }
        }
        else
        {
            return null;
        }
    }

    #Mark a schedule as attended
    public static function MarkAttendedSchedule($schedule_id,$teacher_id)
    {
        self::UpdateAttendedSchchedule($schedule_id,$teacher_id,true);
    }

    #Unmark attended schedule
    public static function UnmarkAttendedSchedule($schedule_id,$teacher_id)
    {
        self::UpdateAttendedSchchedule($schedule_id,$teacher_id,false);
    }
    
    //Create a Schedule
    public static function CreateSchedule($schedule_title,$schedule_description,$teacher_id,$class_id,$schedule_date,$schedule_time)
    {
        global $dbCon;

        $insert_query = "INSERT INTO schedules(schedule_title,schedule_description,teacher_id,class_id,schedule_date,schedule_time) VALUES(?,?,?,?,?,?)";

        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            $insert_stmt->bind_param("ssiiss",$schedule_title,$schedule_description,$teacher_id,$class_id,$schedule_date,$schedule_time);

            if($insert_stmt->execute())
            {
                return true;#successfully ran the query and created the schedule
            }
            else
            {
                return false;#failed to execute query
            }
        }
        else
        {
            return null;#failed to prepare insert query
        }
    }
};