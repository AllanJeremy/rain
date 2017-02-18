<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_handler.php"));#Updates information in the database
require_once(realpath(dirname(__FILE__) . "/../handlers/date_handler.php")); #Date handler. Handles all date operations

#Functions used by the schedule
interface ScheduleFunctions
{
    public static function CreateSchedule($schedule_title,$schedule_description,$schedule_objectives,$teacher_id,$class_id,$due_date,$guid_id);#create a schedule
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
        return self::UpdateAttendedSchedule($schedule_id,$teacher_id,true);
    }

    #Unmark attended schedule
    public static function UnmarkAttendedSchedule($schedule_id,$teacher_id)
    {
        return self::UpdateAttendedSchedule($schedule_id,$teacher_id,false);
    }
    
    //Create a Schedule
    public static function CreateSchedule($schedule_title,$schedule_description,$schedule_objectives,$teacher_id,$class_id,$due_date,$guid_id)
    {
        global $dbCon;

        $insert_query = "INSERT INTO schedules(schedule_title,schedule_description,schedule_objectives,teacher_id,class_id,due_date,guid_id) VALUES(?,?,?,?,?,?,?)";

        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            $insert_stmt->bind_param("sssiiss",$schedule_title,$schedule_description,$schedule_objectives,$teacher_id,$class_id,$due_date,$guid_id);

            if($insert_stmt->execute())
            {
                return true;#successfully ran the query and created the schedule
            }
            else
            {
                return $dbCon->error;#failed to execute query
            }
        }
        else
        {
            return $dbCon->error;#failed to prepare insert query
        }
    }
};#END OF CLASS


/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_POST['action'])) {
    
    switch($_POST['action']) {
        case 'CreateSchedule':
            
            $args = array(
                'schedule_title' => $_POST['scheduletitle'],
                'schedule_description' => $_POST['scheduledescription'],
                'schedule_objectives' => $_POST['scheduleobjectives'],
                'teacher_id' => $_SESSION['admin_acc_id'],
                'class_id' => $_POST['scheduleclassroom'],
                'due_date' => $_POST['duedate'],
                'guid_id' => $_POST['guidid']
            );
             
            $result = Schedule::CreateSchedule($args['schedule_title'], $args['schedule_description'], $args['teacher_id'], $args['class_id'], $args['due_date'], $args['guid_id']);
            
            echo $result;
            
            break;
        case 'RemoveStudent':
            
            
            //dddd
            break;
        case 'MarkAttendedSchedule':

            $result = Schedule::MarkAttendedSchedule($_POST['scheduleid'], $_SESSION['admin_acc_id']);

            echo json_encode($result);

            //dddd
            break;
        case 'UnmarkAttendedSchedule':

            $result = Schedule::UnmarkAttendedSchedule($_POST['scheduleid'], $_SESSION['admin_acc_id']);

            echo json_encode($result);

            //dddd
            break;
        default:
            return null;
            break;
    }

} else {
    return null;
}







