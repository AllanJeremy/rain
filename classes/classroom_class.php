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

    //Other Code here
    public static function CreateClassroom($class_name,$class_stream)
    {
        $class_code = self::GenerateClassroomCode($class_name,$class_stream);#class_code : this is used to join classes
        
        global $dbCon;#Connection string mysqli object

        if($class_code)#if class_code was successfully generated, we can create the classroom
        {

        }
        else
        {

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

    //Delete Classroom
    public static function UpdateClassroomInfo($class_id,$class_name,$stream)
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::ClassroomExists($class_id))#if the classroom exists - safety check
        {

        }
        else
        {
            return false;
        }
    }

    //Delete Classroom
    public static function DeleteClassroom($class_id)
    {
        global $dbCon;#Connection string mysqli object

        #if the classroom exists - safety check
        if(DbInfo::ClassroomExists($class_id))
        {

        }
        else
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
    private function GenerateClassroomCode($class_name,$class_stream)
    {

    }
};