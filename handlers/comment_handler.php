<?php
require_once (realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Allows connection to database
require_once (realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Allows connection to database

class CommentHandler
{
    //Return the commentor link and commentor name  based on what kind of commentor it is 
    public static function GetCommentorInfo($acc_id,$commentor_type="student")
    {
        //Prefix for the commentor link
        $commentor_link = realpath(dirname(__FILE__) . "/../profile.php?");
        
        switch($commentor_type)#add case for every new type off accessible profile
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
                $commentor_link .= "accType='undefined'&id=$acc_id";#undefined means unviewable profile debug. Should be blank ideally 
                $commentor_name = "Anonymous";#if we cannot find the commentor's account type                
        }
        return array("commentor_link"=>$commentor_link,"commentor_name"=>$commentor_name);
    }

    //Convenience - comment on anything - assumes all comment tables have the same base structure
    protected static function CommentOnItem($table_name,$fk_col_name,$fk_col_value,$acc_id,$comment_text,$commentor_type="student")
    {
        $ass_id = htmlspecialchars($ass_id);
        $acc_id = htmlspecialchars($acc_id);

        global $dbCon;
        
        //Insert Query
        $insert_query="INSERT INTO $table_name($fk_col_name,comment_text,commentor_name,commentor_link)  VALUES(?,?,?,?)";
        
        //Commentor variables
        $commentor_info = self::GetCommentorInfo($acc_id,$commentor_type);#Commentor info array
        $commentor_name = $commentor_info["commentor_name"];#default is anonymous if we can't find the commentor name
        $comment_link = $commentor_info["commentor_link"];#Link to commentor's profile

        if($insert_stmt = $dbCon->prepare($insert_stmt))
        {
            //(ass_id,comment_text,commentor_name,commentor_link)
            $insert_stmt->bind_param("isss",$fk_col_value,$comment_text,$commentor_name,$commentor_link);
            
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

    //Comment on assignment
    public static function CommentOnAss($ass_id,$acc_id,$comment_text,$commentor_type="student")
    {
        return self::CommentOnItem("ass_comments","ass_id",$ass_id,$acc_id,$comment_text,$commentor_type);
    } 

    //Comment on assignment submission
    public static function CommentOnAssSubmission($submission_id,$acc_id,$comment_text,$commentor_type="student")
    {
        return self::CommentOnItem("ass_submission_comments","submission_id",$submission_id,$acc_id,$comment_text,$commentor_type);
    } 
};