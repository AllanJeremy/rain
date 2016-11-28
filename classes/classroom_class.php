<?php

require_once (realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Allows connection to database

#HANDLES CLASSROOM RELATED FUNCTIONS
class Classroom
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        
    }

    
    //Create a classroom - returns true on success and false on fail, null if there was an error with the prepare statement for the query
    public static function CreateClassroom($class_name,$class_stream,$class_subject_id,$student_ids)
    {
        $class_code = self::GenerateClassroomCode($class_name,$class_stream,$class_subject_id);#class_code : this is used to join classes
        
        global $dbCon;#Connection string mysqli object

        if($class_code)#if class_code was successfully generated, we can create the classroom
        {
            $insert_query = "INSERT INTO classroom (class_name,class_code,subject_id,student_ids,stream_id) VALUES(?,?,?,?,?)";

            if($insert_stmt = $dbCon->prepare($insert_query))
            {
                $insert_stmt->bind_param("ssisi",$class_name,$class_code,$class_subject_id,$student_ids,$class_stream);
                
                if ($insert_stmt->execute())
                {
                    echo 'true';
                }
                else #could not execute query to create classroom
                {
                    echo $dbCon->error;
                }
            }
            else
            {
                echo $dbCon->error;#could not prepare the query
            }
        }
        else
        {
            echo 'false';
        }
    }

    //Join a Classroom
    public static function JoinClassroom($class_code,$std_id)
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::ClassroomCodeExists($class_code))#if the classroom code exists
        {

        }
        else #classroom code does not exist - show error message
        {
            return false;
        }
    }

    //Add Student to clasroom
    public static function AddStudent($class_id,$std_id)
    {
        global $dbCon;#Connection string mysqli object

        #if the classroom and student exist  - safety check
        if(DbInfo::ClassroomExists($class_id) && DbInfo::StudentIdExists($std_id))
        {

        }
        else
        {
            return false;
        }
    }

    //Remove student from JoinClassroom
    public static function RemoveStudent($class_id,$std_id)
    {
        global $dbCon;#Connection string mysqli object

        #if the classroom and student exist - safety check
        if(DbInfo::ClassroomExists($class_id) && DbInfo::StudentIdExists($std_id))
        {

        }
        else
        {
            return false;
        }
    }

    //Generate a unique classroom code for a classroom - used internally during creation process - returns code if successful and false if it fails
    private static function GenerateClassroomCode($class_name,$class_stream,$class_subject_id)
    {
        return "MyClassCode";
    }
};

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_POST['action'])) {
    
    $classroom = new Classroom();
    
    switch($_POST['action']) {
        case 'CreateClassroom':
            
            
            $args = array(
                'class_name' => $_POST['classroomtitle'],
                'class_stream' => $_POST['classroomstream'],
                'class_subject_id' => $_POST['classroomid']
            );
            
            if(isset($_POST['studentids'])) {
                
                $args['student_ids'] = $_POST['studentids'];
                
            } else {
                
                $args['student_ids'] = 0;
                
            }
            
            $result = $classroom::CreateClassroom($args['class_name'], $args['class_stream'], $args['class_subject_id'], $args['student_ids']);
            
            return $result;
            
            break;
        case 'RemoveStudent':
            
            
            //dddd
            break;
        case 'getAllStudents':
            
            $result = $classroom::getAllStudents();
            
            return $result;
            
            break;
        default:
            return null;
            break;
    }

} else {
    return null;
}







