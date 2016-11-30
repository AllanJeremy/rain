<?php
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php"));#Used to retrieve information from the database
require_once(realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php")); #Used for password encryption


#HANDLES DATABASE FUNCTIONS THAT INVOLVE ALTERING RECORDS IN THE DATABASE
class DbHandler extends DbInfo
{

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
private static function DeleteBasedOnSingleProperty($table_name,$column_name,$prop_name,$prop_type,$prepare_error="Error preparing  delete based on single property query. <br>Technical information :")
{
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
    public static function UpdateClassroomInfo($class_id,$class_name,$stream_id,$subject_id,$student_ids)
    {
        global $dbCon;#Connection string mysqli object
        
        if(DbInfo::ClassroomExists($class_id))#if the classroom exists - safety check
        {
            $update_query = "UPDATE classrooms SET class_name=? stream_id=? subject_id=? student_ids=? WHERE class_id=?";
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("siisi",$class_name,$class_name,$stream_id,$subject_id,$student_ids,$class_id);
                
                if($update_stmt->execute())
                {
                    return true;#successfully updated the classroom details
                }
                else
                {
                    return false;#failed to update the classroom details
                }
            }
            else#failed to prepare query
            {
                return null;
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
            return self::DeleteBasedOnSingleProperty("classrooms","class_id",$class_id,"i");
        }
        else
        {
            return false;
        }       
    }

/*
-----------------------------------------------------------------------------------------
                                    UPDATING AND DELETING ASSIGNMENTS
-----------------------------------------------------------------------------------------
*/
    //Update Assignment information
    //TODO Add parameters for UpdateAssignmentInfo based on what kind of information will be updated - refer to UpdateClassroomInfo() function for example parameters
    public static function UpdateAssignmentInfo()
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::AssignmentExists($ass_id))#if the assignment exists - safety check
        {

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
                         UPDATING AND DELETING SCHEDULES
-----------------------------------------------------------------------------------------
*/

    //Update Schedule information
    //TODO Add parameters for UpdateScheduleInfo based on what kind of information will be updated - refer to UpdateClassroomInfo() function for example parameters
    public static function UpdateScheduleInfo()
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::ScheduleExists($schedule_id))#if the schedule exists - safety check
        {

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

};