<?php
require_once (realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Allows connection to database
require_once (realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Allows connection to database

#HANDLES ASSIGNMENT RELATED FUNCTIONS
class Assignment
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        
    }

    //Create/send an Assignment
    public static function CreateAssignment($args=array(
        "teacher_id"=>0,
        "ass_title"=>"",
        "ass_description"=>"",
        "class_ids"=>"",
        "due_date"=>"",
        "attachments"=>"",
        "file_option"=>"view",
        "max_grade"=>100,
        "comments_enabled"=>true)
    )
    {
        global $dbCon;

        $insert_query = "INSERT INTO assignments(teacher_id,ass_title,ass_description,class_ids,date_sent,due_date,attachments,file_option,max_grade,comments_enabled) VALUES(?,?,?,?,?,?,?,?,?,?)";

        if($insert_stmt = $dbCon->prepare($insert_query))
        {

        }
        else
        {
            return null;#failed to prepare the query
        }
    }
    
    //Submit assignment - students


    //Comment on assignment
    
    //Comment on submission
};