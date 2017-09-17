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

            case "principal":
                if($principal = DbInfo::GetPrincipalById($acc_id))
                {
                    $commentor_link .= "accType='principal'&id=$acc_id";#tr means teacher
                    $commentor_name = $principal["first_name"] . " " . $principal["last_name"];
                }
            break;
            default:
                $commentor_link .= "accType='undefined'&id=$acc_id";#undefined means unviewable profile debug. Should be blank ideally
                $commentor_name = "Anonymous";#if we cannot find the commentor's account type
        }
        return array("commentor_link"=>$commentor_link,"commentor_name"=>$commentor_name);
    }

    //Convenience - comment on anything - allows for commenting on any acceptable comment type
    protected static function CommentOnItem($comment_category,$fk_id,$acc_id,$comment_text,$commentor_type="student")
    {
        //$ass_id = htmlspecialchars($ass_id);
        $acc_id = htmlspecialchars($acc_id);

        global $dbCon;

        //Insert Query
        $insert_stmt="INSERT INTO comments(fk_id,comment_category,comment_text,commentor_name,commentor_link,commentor_type,commentor_id)  VALUES(?,?,?,?,?,?,?)";

        //Commentor variables
        $commentor_info = self::GetCommentorInfo($acc_id,$commentor_type);#Commentor info array
        $commentor_name = $commentor_info["commentor_name"];#default is anonymous if we can't find the commentor name
        $comment_link = $commentor_info["commentor_link"];#Link to commentor's profile

        if($insert_stmt = $dbCon->prepare($insert_stmt))
        {
            //(ass_id,comment_text,commentor_name,commentor_link)
            $insert_stmt->bind_param("isssssi",$fk_id,$comment_category,$comment_text,$commentor_name,$comment_link,$commentor_type,$acc_id);

            if($insert_stmt->execute())
            {
                return true;#succesfully added the assignment
            }
            else
            {
                return $insert_stmt->error;#failed to send the assignment
            }
        }
        else
        {
            // var_dump($dbCon->error);
            return null;#failed to execute the query
        }
    }

    #Student comment on assignment
    public static function StudentCommentOnAss($ass_id,$acc_id,$comment_text)
    {
        $commentor_type="student";
        return self::CommentOnItem("assignment",$ass_id,$acc_id,$comment_text,$commentor_type);
    }

    #Student comment on assignment submission
    public static function StudentCommentOnAssSubmission($submission_id,$acc_id,$comment_text)
    {
        $commentor_type="student";
        return self::CommentOnItem("ass_submission",$submission_id,$acc_id,$comment_text,$commentor_type);
    }

    #Teacher comment on assignment
    public static function TeacherCommentOnAss($ass_id,$acc_id,$comment_text)
    {
        $commentor_type="teacher";
        return self::CommentOnItem("assignment",$ass_id,$acc_id,$comment_text,$commentor_type);
    }

    #Teacher comment on assignment submission
    public static function TeacherCommentOnAssSubmission($submission_id,$acc_id,$comment_text)
    {
        $commentor_type="teacher";
        return self::CommentOnItem("ass_submission",$submission_id,$acc_id,$comment_text,$commentor_type);
    }


    #Teacher comment on schedule
    public static function TeacherCommentOnSchedule($schedule_id,$acc_id,$comment_text)
    {
        $commentor_type="teacher";
        return self::CommentOnItem("schedule",$schedule_id,$acc_id,$comment_text,$commentor_type);
    }

    #Principal comment on schedule
    public static function PrincipalCommentOnSchedule($schedule_id,$acc_id,$comment_text)
    {
        $commentor_type="principal";
        return self::CommentOnItem("schedule",$schedule_id,$acc_id,$comment_text,$commentor_type);
    }

    /*RETRIEVING COMMENTS*/
    #Check if comment exists in a $table | return the comment if it does, false if it doesn't and null if prepare failed
    protected static function GetComments($comment_category,$fk_id)
    {
      global $dbCon;

      $stmt = "SELECT * FROM comments WHERE comment_category=? AND fk_id=?";
      if($stmt = $dbCon->prepare($stmt))
      {
        $stmt->bind_param("si",$comment_category,$fk_id);
        if($stmt->execute())
        {
            $result = $stmt->get_result();
            if(@$result->num_rows > 0)
            {
                return $result;
            }
            else
            {
                echo "No $comment_category comment(s) found";
                return false;
            }
        }
        else
        {
            echo "Unable to execute query to check if the $comment_category comment exists";
            return false;
        }
      }
      else
      {

        echo "Unable to prepare query to check if the $comment_category comment exists";
        return null;
      }

    }

    //Get specific assignment comments
    //Get comment data and return it
    private static function GetCommentData($comments,$comment_type)
    {
        $comment_list = array();
        $comment_data = array("comment_type"=>$comment_type,"comments"=>$comment_list);

        //If comments were available
        if(@$comments && $comments->num_rows>0)
        {
            $cur_comment = array("comment_id"=>"","poster_name"=>"","poster_type"=>"","poster_link"=>"","poster_id"=>"","comment_text"=>"","date"=>"","time"=>"");

            //For each comment
            foreach($comments as $comment)
            {
                $cur_comment["comment_id"] = $comment["comment_id"];
                $cur_comment["poster_name"] = $comment["commentor_name"];
                $cur_comment["poster_type"] = $comment["commentor_type"];
                $cur_comment["poster_link"] = $comment["commentor_link"];
                $cur_comment["poster_id"] = $comment["commentor_id"];
                $cur_comment["comment_text"] = $comment["comment_text"];

                $date_info = EsomoDate::GetDateInfo($comment["date_sent"]);

                //Format the date and time into a usable format eg. (Fri,April 28, 2017)
                $date = $date_info["days"].", ".$date_info["months"]." ".$date_info["days"].", ".$date_info["years"];
                $time = $date_info["hours"].":".$date_info["minutes"];

                $cur_comment["date"] = $date;
                $cur_comment["time"] = $time;

                array_push($comment_data["comments"],$cur_comment);
            }
            //var_dump($comment_data);
            return $comment_data;
        }
        else
        {
            return $comments;
        }
    }
    #Get assignment comments
    public static function GetAssComments($ass_id)
    {
        $comment_type = "assignment";
        
        $comments = self::GetComments($comment_type,$ass_id);
        return self::GetCommentData($comments,$comment_type);
    }

    #Get assignment submissions comments
    public static function GetAssSubmissionComments($submission_id)
    {
        $comment_type = "ass_submission";
        
        $comments = self::GetComments($type,$submission_id);
        return self::GetCommentData($comments,$comment_type);
    }

    #Get schedule comments
    public static function GetScheduleComments($schedule_id)
    {
        $comment_type = "schedule";
        
        $comments = self::GetComments($comment_type,$schedule_id);
        return self::GetCommentData($comments,$comment_type);
    }
};

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_POST['action'])) {
    $user_info = MySessionHandler::GetLoggedUserInfo();#store the logged in user info anytime an AJAX call is made
    sleep(1);//Sleep for  ashort amount of time, to reduce odds of a DDOS working.

    switch($_POST['action']) {
        case 'TeacherCommentOnSchedule':

            $comment_text = $_POST['comment'];
            $schedule_id = $_POST['id'];
            $acc_id = $_SESSION['admin_acc_id'];

            $result = CommentHandler::TeacherCommentOnSchedule($schedule_id,$acc_id,$comment_text);

            echo json_encode($result);

            break;
        case 'TeacherCommentOnAssSubmission':

            $comment_text = $_POST['comment'];
            $submission_id = $_POST['id'];
            $acc_id = $_SESSION['admin_acc_id'];

            $result = CommentHandler::TeacherCommentOnAssSubmission($submission_id,$acc_id,$comment_text);

            echo json_encode($result);

            break;
        case 'StudentIdExists':
            break;
    default:
            // echo "invalid get request";
            return null;
            break;
    }

} else {
    return null;
}
