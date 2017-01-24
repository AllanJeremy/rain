<?php
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php"));#Used to retrieve information from the database
require_once(realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php")); #Used for password encryption


#HANDLES DATABASE FUNCTIONS THAT INVOLVE UPDATING/DELETING RECORDS IN THE DATABASE
class DbHandler extends DbInfo
{
//TODO Refactor this whole class into interfaces to restrict who can call functions once we determine if it's necessary
/*
-----------------------------------------------------------------------------------------
                                    RESETTING ACCOUNTS
-----------------------------------------------------------------------------------------
*/
    //Reset the password of a student account : takes student acc_id as a parameter
    public static function ResetStudentAccount($acc_id)
    {
        global $dbCon;#db connection string (mysqli object)
        $prepare_error = "Couldn't prepare query to reset the student account. <br><br> Technical information : ";

        if($student = DbInfo::GetStudentById($acc_id))#if the student exists
        {
            $new_password = PasswordEncrypt::EncryptPass($student["username"]);#set the new password to be equal to the username

            $reset_query = "UPDATE student_accounts SET password=$new_password WHERE student_accounts.acc_id=?";

            if ($reset_stmt = $dbCon->prepare($reset_query))
            {
                $reset_stmt->bind_param("i",$acc_id);

                if($reset_stmt->execute())#run the query to reset the account
                {
                    return $reset_stmt->get_result();
                }
                else
                {
                    return false;
                }
            }
            else #failed to prepare the query for data retrieval
            {
                ErrorHandler::PrintError($prepare_error . $dbCon->error);
                return null;
            }
        }
        else
        {
            return false;
        }

    }

    //Reset the password of an admin account : takes admin acc_id and acc_type as parameters
    protected static function ResetAdminAccount($acc_id,$acc_type="teacher")#protected to avoid calling it manually which may lead to typos
    {
        global $dbCon;#db connection string (mysqli object)
        $prepare_error = "Couldn't prepare query to reset the admin (" . $acc_type . ") account. <br><br> Technical information : ";

        if($admin = self::GetAdminById($acc_id,$acc_type))#if the admin exists
        {
            $new_password = PasswordEncrypt::EncryptPass($admin["username"]);#set the new password to be equal to the username

            $reset_query = "UPDATE admin_accounts SET password=$new_password WHERE admin_accounts.acc_id=? AND admin_accounts.account_type=?";

            if ($reset_stmt = $dbCon->prepare($reset_query))
            {
                $reset_stmt->bind_param("is",$acc_id,$acc_type);

                if($reset_stmt->execute())#run the query to reset the account
                {
                    return $reset_stmt->get_result();
                }
                else
                {
                    return false;
                }
            }
            else #failed to prepare the query for data retrieval
            {
                ErrorHandler::PrintError($prepare_error . $dbCon->error);
                return null;
            }
        }
        else
        {
            return false;
        }

    }

        #Reset the password of an teacher account : takes teacher acc_id as a parameter : convenience function
        public static function ResetTeacherAccount($acc_id)
        {
            return self::ResetAdminAccount($acc_id,"teacher");
        }
        
        #Reset the password of an principal account : takes principal acc_id as a parameter : convenience function
        public static function ResetPrincipalAccount($acc_id)
        {
            return self::ResetAdminAccount($acc_id,"principal");
        }
    
        #Reset the password of an superuser account : takes superuser acc_id as a parameter : convenience function
        public static function ResetSuperuserAccount($acc_id)
        {
            return self::ResetAdminAccount($acc_id,"superuser");
        }

/*
-----------------------------------------------------------------------------------------
                                    DELETING ACCOUNTS
-----------------------------------------------------------------------------------------
*/
    //Delete a student account : takes student acc_id as a parameter
    public static function DeleteStudentAccount($acc_id)
    {
        global $dbCon;#db connection string (mysqli object)
        $prepare_error = "Couldn't prepare query to reset the student account. <br><br> Technical information : ";

        $delete_query = "DELETE FROM student_accounts WHERE student_accounts.acc_id=?";
        
        if($student = self::GetStudentById($acc_id,$acc_type))#if the student exists: $student can be used to print info on deleted account
        {
            if($delete_stmt = $dbCon->prepare($delete_query))
            {
                $delete_stmt->bind_param("i",$acc_id);

                if($delete_stmt->execute())#if the query successfully executed
                {
                    return true; #successfully deleted the record
                }
                else
                {
                    return false; #failed to delete the record
                }
            }
            else
            {
                return null;
            }
        }
    }

    //Delete an admin account : takes admin acc_id and acc_type as parameters
    public static function DeleteAdminAccount($acc_id,$acc_type="teacher")
    {
        global $dbCon;#db connection string (mysqli object)
        $prepare_error = "Couldn't prepare query to reset the admin (" . $acc_type . ") account. <br><br> Technical information : ";
        
        $delete_query = "DELETE FROM admin_accounts WHERE admin_accounts.acc_id=? AND admin_accounts.account_type=?";
        
        if($admin = self::GetAdminById($acc_id,$acc_type))#if the admin exists : $admin can be used to print info on deleted account
        {
            if($delete_stmt = $dbCon->prepare($delete_query))
            {
                $delete_stmt->bind_param("is",$acc_id,$acc_type);

                if($delete_stmt->execute())
                {
                    return true; #successfully deleted the record
                }
                else
                {
                    return false; #failed to delete the record
                }
            }
            else
            {
                ErrorHandler::PrintError($prepare_error . $dbCon->error);
                return null;
            }
        }
    }

        #Delete a teacher account : takes teacher acc_id as a parameter : convenience function
        public static function DeleteTeacherAccount($acc_id)
        {
            return self::DeleteAdminAccount($acc_id,"teacher");
        }
        
        #Delete a teacher account : takes teacher acc_id as a parameter : convenience function
        public static function DeletePrincipalAccount($acc_id)
        {
            return self::DeleteAdminAccount($acc_id,"principal");
        }
        
        #Delete a teacher account : takes teacher acc_id as a parameter : convenience function
        public static function DeleteSuperuserAccount($acc_id)
        {
            return self::DeleteAdminAccount($acc_id,"superuser");
        }

/*
-----------------------------------------------------------------------------------------
    CONVENIENCE FUNCTION FOR DELETING RECORD IN TABLE BASED ON SINGLE PROPERTY
-----------------------------------------------------------------------------------------
*/

//Delete a row from a table based on a single property : returns true on success | false on fail | null if query couldn't execute
public static function DeleteBasedOnSingleProperty($table_name,$column_name,$prop_name,$prop_type,$prepare_error="Error preparing  delete based on single property query. <br>Technical information :")
{
    global $dbCon;#Connection string mysqli object
        
    $delete_query = "DELETE FROM $table_name WHERE $column_name=?";

    if($delete_stmt = $dbCon->prepare($delete_query))
    {
        $delete_stmt->bind_param($prop_type,$prop_name);

        if($delete_stmt->execute())#if it runs, means records were successfully deleted
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        ErrorHandler::PrintError($prepare_error . $dbCon->error);
        return null;
    }
}

/*
-----------------------------------------------------------------------------------------
                                    UPDATING AND DELETING CLASSROOMS
-----------------------------------------------------------------------------------------
*/
    //Update Classroom information
    //TODO Test implementation
    public static function UpdateClassroomInfo($class_id,$class_name,$stream_id,$subject_id,$student_ids,$classes)
    {
        global $dbCon;#Connection string mysqli object
        
        if(DbInfo::ClassroomExists($class_id))#if the classroom exists - safety check
        {
            $update_query = "UPDATE classrooms SET class_name=?, student_ids=?, stream_id=?, subject_id=?, classes=? WHERE class_id=?";
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("ssiisi",$class_name,$student_ids,$stream_id,$subject_id,$classes,$class_id);
                
                if($update_stmt->execute())
                {
                    return 'true';#successfully updated the classroom details
                }
                else
                {
                    return 'null';#failed to update the classroom details
                }
            }
            else#failed to prepare query
            {
                return $dbCon->error;
            }
        }
        else
        {
            return false;
        }
    }

    #Delete Classroom : returns true on success | false on fail | null if query couldn't execute
    public static function DeleteClassroom($class_id)
    {
        
        #if the classroom exists - safety check
        if(DbInfo::ClassroomExists($class_id))
        {
            echo self::DeleteBasedOnSingleProperty("classrooms","class_id",$class_id,"i");
        }
        else
        {
            echo 'false';
        }       
    }

/*
-----------------------------------------------------------------------------------------
                                    UPDATING AND DELETING ASSIGNMENTS
-----------------------------------------------------------------------------------------
*/
    //Update Assignment information
    public static function UpdateAssignmentInfo($args=array(    
            "ass_id"=>0,
            "teacher_id"=>0,
            "ass_title"=>"",
            "ass_description"=>"",
            "class_id"=>0,
            "due_date"=>"",
            "attachments"=>"",
            "file_option"=>"view",
            "max_grade"=>100,
            "comments_enabled"=>true
            )
    )//TO TEST
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::AssignmentExists($args["ass_id"]))#if the assignment exists - safety check
        {
            $update_query = "UPDATE assignments SET ass_title=? ass_description=?,class_id=?,due_date=?,attachments=?,file_option=?,max_grade=?,comments_enabled=? WHERE teacher_id=? AND ass_id=?";

            if($update_stmt = $dbCon->prepare())
            {
                $update_stmt->bind_param("ssisssiiii",$args["ass_title"],$args["ass_description"],$args["class_id"],$args["due_date"],$args["attachments"],$args["file_option"],$args["max_grade"],$args["comments_enabled"],$args["teacher_id"],$args["ass_id"]);

                if($update_stmt->execute())
                {
                    return true;#successfully updated the assignment
                }
                else
                {
                    return false;#failed to execute query
                }
            }
            else
            {
                return null;#failed to prepare query
            }
        }
        else
        {
            return false;
        }
    }

    #Delete Assignment : returns true on success | false on fail | null if query couldn't execute
    public static function DeleteAssignment($ass_id)
    {
        #if the assignment exists - safety check
        if(DbInfo::AssignmentExists($ass_id))
        {
            return
            (
                self::DeleteBasedOnSingleProperty("assignments","ass_id",$ass_id,"i") && #delete the assignment
                self::DeleteBasedOnSingleProperty("ass_comments","ass_id",$ass_id,"i") && #delete assignment comments
                self::DeleteBasedOnSingleProperty("ass_submissions","ass_id",$ass_id,"i") #delete assignment submissions
                //TODO Delete the assignment submission comments as well, make the submission_comments have an inner join with the ass_submission table they are a child of
            );
        }
        else
        {
            return false;
        }       
    }
/*
-----------------------------------------------------------------------------------------
                        UPDATING AND DELETING ASSIGNMENT SUBMISSIONS
-----------------------------------------------------------------------------------------
*/
    //Update Assignment submission information
    public static function UpdateAssignmentSubmissionInfo($submission_id,$student_id,$attachments,$submission_text,$submitted=true)//TO TEST
    {
        global $dbCon;

        #if the assignment exists - safety check
        if(DbInfo::AssSubmissionExists($submission_id))
        {
            $update_query="UPDATE ass_submissions SET attachments=? submitted=? submission_text=? WHERE submission_id=? AND student_id=?";

            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("sisii",$attachments,$submitted,$submission_text,$submission_id,$student_id);
                
                if($update_stmt->execute())
                {
                    return true;#successfully updated the assignment submission
                }
                else
                {
                    return false;#failed to execute the query
                }
            }
            else
            {
                return null;#failed to prepare query
            }
        }
        else
        {
            return false;
        }   
    }    

    #Delete Assignment : returns true on success | false on fail | null if query couldn't execute
    public static function DeleteAssignmentSubmission($submission_id)
    {
        #if the assignment exists - safety check
        if(DbInfo::AssSubmissionExists($submission_id))
        {
            return self::DeleteBasedOnSingleProperty("ass_submissions","submission_id",$submission_id,"i");
        }
        else
        {
            return false;
        }    
    }
    
/*
-----------------------------------------------------------------------------------------
                        UPDATING  COMMENTS - CONVENIENCE FUNCTIONS 
-----------------------------------------------------------------------------------------
*/  
protected static function UpdateComment($table_name,$comment_id,$comment_text)
{
    global $dbCon;

    $prepare_error="Error preparing  update comment query. <br>Technical information :";#error shown when preparing the query fails

    $update_query = "UPDATE $table_name SET comment_text=? WHERE comment_id=?";

    if($update_stmt = $dbCon->prepare($update_query))
    {
        $update_stmt->bind_param("si",$comment_id,$comment_text);

        #if we successfully update the comment
        if($update_stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        ErrorHandler::PrintError($prepare_error . $dbCon->error);
        return null;
    }
}

/*
-----------------------------------------------------------------------------------------
                              UPDATING AND DELETING ASSIGNMENT COMMENTS
-----------------------------------------------------------------------------------------
*/  
    #Teacher update ass. comment
    public static function UpdateAssComment($comment_id,$comment_text)
    {
        return self::UpdateComment("ass_comments",$comment_id,$comment_text);
    }
    
    #Teacher delete ass. comment
    public static function DeleteAssComment($comment_id)
    {
       return self::DeleteBasedOnSingleProperty("ass_comments","comment_id",$comment_id,"i");
    }

/*
-----------------------------------------------------------------------------------------
                    UPDATING AND DELETING ASSIGNMENT SUBMISSION COMMENTS
-----------------------------------------------------------------------------------------
*/  
    #Teacher update ass. submission comment
    public static function UpdateAssSubmissionComment($comment_id,$comment_text)
    {
        return self::UpdateComment("ass_submission_comments",$comment_id,$comment_text);
    }

    #Teacher delete ass. submission comment
    public static function DeleteAssSubmissionComment($comment_id)
    {
        return self::DeleteBasedOnSingleProperty("ass_submission_comments","comment_id",$comment_id,"i");
    }

/*
-----------------------------------------------------------------------------------------
                         UPDATING AND DELETING SCHEDULES
-----------------------------------------------------------------------------------------
*/

    //Update Schedule information
    public static function UpdateScheduleInfo($schedule_id,$schedule_title,$schedule_description,$teacher_id,$class_id,$schedule_date,$schedule_time)
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::ScheduleExists($schedule_id))#if the schedule exists - safety check
        {
                $update_query = "UPDATE schedules SET schedule_title=? schedule_description=? class_id=? schedule_date=? schedule_time=? WHERE teacher_id=? AND schedule_id=?";
                if($update_stmt = $dbCon->prepare($update_query))
                {
                    $update_stmt->bind_param("ssissii",$schedule_title,$schedule_description,$class_id,$schedule_date,$schedule_time,$teacher_id,$schedule_id);

                    if($update_stmt->execute())
                    {
                        return true;#successfully updated the schedule
                    }
                    else
                    {
                        return false;;
                    }
                }
                else
                {
                    return null;
                } 
        }
        else
        {
            return false;
        }
    }

    #Delete Schedule : returns true on success | false on fail | null if query couldn't execute
    public static function DeleteSchedule($schedule_id)
    {
        #if the schedule exists - safety check
        if(DbInfo::ScheduleExists($schedule_id))
        {
            return
            (
                self::DeleteBasedOnSingleProperty("schedules","schedule_id",$schedule_id,"i") && #delete the schedule
                self::DeleteBasedOnSingleProperty("schedule_comments","schedule_id",$schedule_id,"i") #delete the schedule comments               
            );
        }
        else
        {
            return false;
        }       
    }
/*
-----------------------------------------------------------------------------------------
                             UPDATING AND DELETING SCHEDULE COMMENTS
-----------------------------------------------------------------------------------------
*/
    #Teacher update ass. comment
    public static function UpdateScheduleComment($comment_id,$comment_text)
    {
        return self::UpdateComment("schedule_comments",$comment_id,$comment_text);
    }
    #Teacher delete ass. comment
    public static function DeleteScheduleComment($comment_id)
    {
       return self::DeleteBasedOnSingleProperty("schedule_comments","comment_id",$comment_id,"i");
    }
/*
-----------------------------------------------------------------------------------------
                              UPDATING AND DELETING TESTS
-----------------------------------------------------------------------------------------
*/

    //Update Test information
    //TODO Add parameters for UpdateTestInfo based on what kind of information will be updated - refer to UpdateClassroomInfo() function for example parameters
    public static function UpdateTestInfo()
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::TestExists($test_id))#if the test exists - safety check
        {

        }
        else
        {
            return false;
        }
    }

    #Delete Schedule : returns true on success | false on fail | null if query couldn't execute
    public static function DeleteTest($test_id)
    {
        #if the schedule exists - safety check
        if(DbInfo::TestExists($test_id))
        {
            return
            (
                self::DeleteBasedOnSingleProperty("tests","test_id",$test_id,"i") && #delete the test
                self::DeleteBasedOnSingleProperty("test_questions","test_id",$test_id,"i") #delete the test questions
                
                //TODO Delete the test questions, answers and submissions as well, consider inner join for the test answers 
            );
        }
        else
        {
            return false;
        }       
    }
/*
-----------------------------------------------------------------------------------------
                    UPDATING AND DELETING TEST QUESTIONS AND ANSWERS
-----------------------------------------------------------------------------------------
*/
    

    #Update the question in the database if it exists | Add the question to the database if it does not exist
    public static function UpdateQuestion($test_id,$q_index,$q_data)
    {

    }

    #Update the question in the database if it exists | Add the question to the database if it does not exist
    public static function UpdateAnswers($q_id,$answers)
    {

    }
};#END OF CLASS

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_POST['action'])) {
    
    switch($_POST['action']) {
        case 'UpdateClassroomInfo':
            
            $args = array(
                'class_id' => $_POST['classroomid'],
                'class_name' => $_POST['classroomtitle'],
                'stream_id' => $_POST['classroomstream'],
                'subject_id' => $_POST['classroomsubject'],
                'classes' => $_POST['classes']
            );
            
            if(isset($_POST['studentids'])) {
                
                $args['student_ids'] = $_POST['studentids'];
                
            } else {
                
                $args['student_ids'] = 0;
                
            }
            
            $result = DbHandler::UpdateClassroomInfo($args['class_id'],$args['class_name'],$args['stream_id'],$args['subject_id'],$args['student_ids'],$args['classes']);
            
            echo $result;
            
            break;
        case 'RemoveStudent':
            
            
            //Remove a student
            break;
        case 'DeleteClassroom':
          
            $class_id = $_POST['classroomid'];
            
            if(isset($class_id)) {
                
                $result = DbHandler::DeleteClassroom($class_id);
            
                echo $result;
                
            }
            
            //dddd
            break;
        case 'UpdateTestQuestion': //AJ ~ may be broken 
            
            //~Computational delay to prevent bots from spamming and DDOS
            sleep(200);
            $q_data = htmlspecialchars($_POST["q_data"]);
            var_dump($q_data);
            //file_put_contents("AJ_TestDebug.txt",$q_data["question_text"]);
            /*
            $q_data["question_index"];#question id
            $q_data["question_text"];#question text
            $q_data["question_type"];#question type
            $q_data["no_of_choices"];#number of choices
            $q_data["marks_attainable"];#marks attainable for the question
            $answers = $q_data["answers"];#array containing all the answers
            
            /*
        break;
        default:
            return null;
            break;
    }

} else {
    return null;
}







