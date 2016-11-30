<?php
require_once (realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Allows connection to database
require_once (realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Allows connection to database

class CommentHandler
{
    //Comment on assignment
    private static function CommentOnAss($ass_id,$acc_id,$comment_text,$commentor_type="student")
    {
        $ass_id = htmlspecialchars($ass_id);
        $acc_id = htmlspecialchars($acc_id);

        global $dbCon;
        //Query
        $insert_query="INSERT INTO ass_comments(ass_id,comment_text,commentor_name,commentor_link)  VALUES(?,?,?,?)";
        
        //Commentor variables
        $commentor_link = realpath(dirname(__FILE__) . "/../profile.php?");
        $commentor_name = "Anonymous";#default is anonymous if we can't find the commentor name

        //Update the commentor link and commentor name  based on what kind of commentor it is
        switch($commentor_type)
        {
            case "student":
                if($student = DbInfo::GetStudentByAccId($acc_id))
                {
                    $commentor_link .= "accType='std'&id=$acc_id";#std means student
                    $commentor_name = $student["full_name"];
                }
                
            break;

            case "teacher":         
                if($teacher = DbInfo::GetTeacherById($acc_id))
                {
                    $commentor_link .= "accType='tr'&id=$acc_id";#tr means teacher
                    $commentor_name = $teacher["first_name"] . " " . $teacher["last_name"];
                }
            break;

            default:
                $commentor_link .= "accType='undefined'&id=$acc_id";#undefined means unviewable profile                
        }

        if($insert_stmt = $dbCon->prepare($insert_stmt))
        {
            //(ass_id,comment_text,commentor_name,commentor_link)
            $insert_stmt->bind_param("isss",$ass_id,$comment_text,$commentor_name,$commentor_link);
            
            if($insert_stmt->execute())
            {
                return true;#succesfully added the assignment
            }
            else
            {
                return false;#failed to send the assignment
            }
        }
        else
        {
            var_dump($dbCon->error);
            return null;#failed to execute the query
        }
    } 

    #Teacher Comment on assignment
    protected static function TeacherCommentOnAss($ass_id,$teacher_id,$comment_text)
    {
        return self::CommentOnAss($ass_id,$teacher_id,$comment_text,"teacher");
    }

    #Student Comment on assignment
    protected static function StudentCommentOnAss($ass_id,$student_acc_id,$comment_text) 
    {
        return self::CommentOnAss($ass_id,$student_acc_id,$comment_text,"student");        
    }
};