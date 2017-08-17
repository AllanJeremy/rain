<?php
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php"));#Used to retrieve information from the database
require_once(realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php")); #Used for password encryption

require_once(realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Session related functions ~ eg. login info
require_once(realpath(dirname(__FILE__). "/../handlers/grade_handler.php")); #Handles grade related functions
require_once(realpath(dirname(__FILE__). "/../handlers/date_handler.php")); #Handles date related functions
require_once(realpath(dirname(__FILE__) . "/../classes/uploader.php")); #Handles upload related functions
require_once(realpath(dirname(__FILE__). "/../classes/timer.php")); #Handles date related functions


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

        if($student = DbInfo::GetStudentByAccId($acc_id))#if the student exists
        {
            $new_password = PasswordEncrypt::EncryptPass($student["username"]);#set the new password to be equal to the username

            $reset_query = "UPDATE student_accounts SET password=? WHERE student_accounts.acc_id=?";

            if ($reset_stmt = $dbCon->prepare($reset_query))
            {
                $reset_stmt->bind_param("si",$new_password,$acc_id);

                return($reset_stmt->execute());#run the query to reset the account
            }
            else #failed to prepare the query for data retrieval
            {
                // ErrorHandler::PrintError($prepare_error . $dbCon->error);
                return null;
            }
        }
        else
        {
            return false;
        }

    }

    //Update password
    public static function UpdateAccountPassword($user_info,$data)
    {
        //If the old password provided does not match the current password in the database, return false
        $old_pass = $data["old_password"];

        global $dbCon;

        $acc_id = $user_info["user_id"];
        $account_type = $user_info["account_type"];

        //Check what type of account it is
        switch($account_type)
        {
            case "student":
                $update_query = "UPDATE student_accounts SET password=?";

                $student_acc = DbInfo::GetStudentByAccId($acc_id);

                //If the admin account can be found
                if($student_acc)
                {
                    //Check if the old password provided matches the admin password in the database
                    $old_pass_valid = PasswordEncrypt::Verify($old_pass,$student_acc["password"]);

                    //If the old password is not valid ~ return false
                    if(!$old_pass_valid)
                    {
                        echo "<p>Wrong old password provided</p>";
                        return false;
                    }
                }
                else #Admin account could not be found ~ return false
                {
                    echo "<p>Student account you requested could not be found</p>";
                    return false;
                }
            break;

            default:#Admin account type `POSSIBLY HACKABLE, CONSIDER prepareing the account_type as well
                $update_query = "UPDATE admin_accounts SET password=? WHERE account_type='".htmlspecialchars($account_type)."'";
                $admin_acc = DbInfo::GetAdminById($acc_id,$account_type);

                //If the admin account can be found
                if($admin_acc)
                {   
                    //Check if the old password provided matches the admin password in the database
                    $old_pass_valid = PasswordEncrypt::Verify($old_pass,$admin_acc["password"]);

                    //If the old password is not valid ~ return false
                    if(!$old_pass_valid)
                    {
                        echo "<p>Wrong old password provided</p>";
                        return 0;
                    }
                }
                else #Admin account could not be found ~ return false
                {
                    echo "<p>Admin account you requested could not be found</p>";
                    return 0;
                }
        }

        //Attempt to prepare query
        if($update_stmt = $dbCon->prepare($update_query))
        {
            //New password is the encrypted form of the new password
            $new_password = PasswordEncrypt::EncryptPass($data["new_password"]);

            $update_stmt->bind_param("s",$new_password);
            $update_status = $update_stmt->execute();
            
            return $update_status;
        }
        else #Failed to prepare the query
        {
            return 0;
        }
    }

    //Reset the password of an admin account : takes admin acc_id and acc_type as parameters
    protected static function ResetAdminAccount($acc_id,$acc_type="teacher")#protected to avoid calling it manually which may lead to typos
    {
        global $dbCon;#db connection string (mysqli object)
        $prepare_error = "Couldn't prepare query to reset the admin (" . $acc_type . ") account. <br><br> Technical information : ";

        if($admin = self::GetAdminById($acc_id,$acc_type))#if the admin exists
        {
            $admin_acc = $admin->fetch_array();

            $new_password = PasswordEncrypt::EncryptPass($admin_acc["username"]);#set the new password to be equal to the username

            $reset_query = "UPDATE admin_accounts SET password=? WHERE admin_accounts.acc_id=? AND admin_accounts.account_type=?";

            if ($reset_stmt = $dbCon->prepare($reset_query))
            {
                $reset_stmt->bind_param("sis",$new_password,$acc_id,$acc_type);

                $reset_status = ($reset_stmt->execute());#run the query to reset the account
   
                return $reset_status;
            }
            else #failed to prepare the query for data retrieval
            {
                // ErrorHandler::PrintError($prepare_error . $dbCon->error);
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
        
        if($student = DbInfo::GetStudentByAccId($acc_id))#if the student exists: $student can be used to print info on deleted account
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
        else
        {
            return false;
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
                $update_stmt->bind_param("ssssssiiii",$args["ass_title"],$args["ass_description"],$args["class_id"],$args["due_date"],$args["attachments"],$args["file_option"],$args["max_grade"],$args["comments_enabled"],$args["teacher_id"],$args["ass_id"]);

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
        else#assignment does not exist ~ create it
        {
            $update_query = "INSERT INTO assignments(ass_title,ass_description,class_id,due_date,attachments,file_option,max_grade,comments_enabled,teacher_id) VALUES(?,?,?,?,?,?,?,?,?)";

            //Prepare query for creating assignment
            if($update_stmt = $dbCon->prepare($update_query))
            {
                 $update_stmt->bind_param("ssissssii",$args["ass_title"],$args["ass_description"],$args["class_id"],$args["due_date"],$args["attachments"],$args["file_option"],$args["max_grade"],$args["comments_enabled"],$args["teacher_id"])   ;

                 #if the create query ran successfully
                 if($update_stmt->execute())
                 {
                     return true;
                 }
                 else #create query failed to run
                 {
                    return $dbCon->error;
                 }
            }
            else
            {
                return null;
            }
            
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
    public static function UpdateAssignmentSubmissionInfo($submission_title = '',$submission_id,$student_id,$attachments,$submission_text,$submitted=true,$ass_id)//TO TEST
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
        else#assignment submission does not exist ~ Create it
        {
            $update_query = "INSERT INTO ass_submissions(submission_title,attachments, submitted, submission_text, student_id,ass_id) VALUES(?,?,?,?,?,?)";

            //Prepare query to create assignment submission
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("ssisii",$submission_title,$attachments,$submitted,$submission_text,$student_id,$ass_id);
                
                #create assignment submission query ran successfully
                if($update_stmt->execute())
                {
                    return true;
                }
                else #failed to run create ass_submission query
                {
                    echo $update_stmt->error;
                }
            }
            else #failed to prepare query to create assignment submission
            {
                return null;
            }
            
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
        $update_stmt->bind_param("si",$comment_text,$comment_id);

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
    public static function UpdateScheduleInfo($schedule_id,$schedule_title,$schedule_description,$schedule_objectives,$class_id,$teacher_id,$due_date)
    {
        global $dbCon;#Connection string mysqli object

        if(DbInfo::ScheduleExists($schedule_id))#if the schedule exists - safety check
        {
                $update_query = "UPDATE schedules SET schedule_title=?, schedule_description=?, schedule_objectives=?, class_id=?, due_date=? WHERE teacher_id=? AND schedule_id=?";
                if($update_stmt = $dbCon->prepare($update_query))
                {
                    $update_stmt->bind_param("sssisii",$schedule_title,$schedule_description,$schedule_objectives,$class_id,$due_date,$teacher_id,$schedule_id);

                    if($update_stmt->execute())
                    {
                        return 'true';#successfully updated the schedule
                    }
                    else
                    {
                        return 'false';
                    }
                }
                else
                {
                    return $dbCon->error;
                } 
        }
        else
        {
            return 'false - null';
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
    #Teacher update sch. comment
    public static function UpdateScheduleComment($comment_id,$comment_text)
    {
        return self::UpdateComment("schedule_comments",$comment_id,$comment_text);
    }
    #Teacher delete sch. comment
    public static function DeleteScheduleComment($comment_id)
    {
       return self::DeleteBasedOnSingleProperty("schedule_comments","comment_id",$comment_id,"i");
    }
/*
-----------------------------------------------------------------------------------------
                              UPDATING AND DELETING TESTS
-----------------------------------------------------------------------------------------
*/
    //Create a Test
    public static function CreateTest($test_data)
    {
        global $dbCon;
        $response = array("message"=>"","redirect_url"=>"","Error"=>"");
        if($test_data["test_title"] || $test_data["test_instructions"])
        {
            $insert_query = "INSERT INTO tests(test_title,test_description,number_of_questions,teacher_id,time_to_complete,subject_id,difficulty,max_grade,passing_grade) VALUES(?,?,?,?,?,?,?,?,?)";

            if($insert_stmt = $dbCon->prepare($insert_query))
            {
                $insert_stmt->bind_param("ssiiiisii",$test_data["test_title"],$test_data["test_instructions"],$test_data["test_question_count"],$_SESSION["admin_acc_id"],$test_data["test_completion_time"],$test_data["test_subject_id"],$test_data["test_difficulty"],$test_data["test_max_grade"],$test_data["test_pass_grade"]);

                if($insert_stmt->execute())
                {
                    $response["message"] = "success";
                    $response["redirect_url"] = "tests.php?tid=".$insert_stmt->insert_id."&edit=1";
                    echo json_encode($response);
                    //return true
                }
                else
                {
                    $response["message"] = "failure";
                    $response["error"] = $dbCon->error;
                    echo json_encode($response);
                    //return false
                }
            }
            else
            {
                $response["message"] = "failure";
                echo json_encode($response);
                //return null
            }
        }
        else//Missing values
        {
            $response["message"] = "failure";
            $response["error"] = "Failed to create test. Missing test title and/or instructions";
            echo $response;
        }

    }

    //Update Test information
    //TODO Add parameters for UpdateTestInfo based on what kind of information will be updated - refer to UpdateClassroomInfo() function for example parameters
    public static function UpdateTest($update_data,$user_info)
    {
        global $dbCon;#Connection string mysqli object
        $test = DbInfo::TestExists($update_data["test_id"]);
        

        if($test)#if the test exists - safety check
        {
            $test_id = &$test["test_id"];#test_id

            #query for updating the test
            $update_query = "UPDATE tests SET test_title=?, test_description=?, number_of_questions=?, teacher_id=?,time_to_complete=?, subject_id=?,difficulty=?,max_grade=?,passing_grade=?  WHERE test_id=".$test["test_id"];

            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("ssiiiisii",
                                        $update_data["test_title"],
                                        $update_data["test_description"],
                                        $update_data["number_of_questions"],
                                        $user_info["user_id"],
                                        $update_data["time_to_complete"],
                                        $update_data["subject_id"],
                                        $update_data["difficulty"],
                                        $update_data["max_grade"],
                                        $update_data["passing_grade"]
                );

                if($update_stmt->execute())
                {
                    // echo "<p>Successfully updated the test</p>";
                    return true;                    
                }
                else
                {
                    // echo "<p>Error executing update test query</p>";
                    return false;
                }
            }
        }
        else
        {
            return $test;
        }
    }

    #Delete Schedule : returns true on success | false on fail | null if query couldn't execute
    public static function DeleteTest($test_id)
    {
        #if the schedule exists - safety check
        if(DbInfo::TestExists($test_id))
        {
                //Delete the test
                $delete_test = self::DeleteBasedOnSingleProperty("tests","test_id",$test_id,"i"); #delete the test
                $delete_questions = true;
                $delete_answers = true;

                #Get all the questions for this test
                $questions = DbInfo::GetTestQuestions($test_id);
                if($questions)#if questions were found
                {
                    #Check each question and delete the answers belonging to it
                    foreach($questions as $question)
                    {
                        //Delete all the answers to the currently looped question in the database
                        $delete_answers = self::DeleteBasedOnSingleProperty("test_answers","question_id",$question["question_id"],"i"); 
                    }       
                        //Delete the questions
                    $delete_questions = self::DeleteBasedOnSingleProperty("test_questions","test_id",$test_id,"i"); #delete the test questions
                }
                
                /*  Note : 
                    The test submissions will be retained as reference for test performance even after the test has been deleted.
                    Students and teachers can still view their results even if the test is deleted
                */
                return ($delete_test && $delete_questions && $delete_answers);
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
    //Update the question in the database if it exists | Add the question to the database if it does not exist
    public static function UpdateQuestion($q_data)
    {
        global $dbCon;#db connection string
        $update_query = "";
        $result_question_id=0;
        $is_insert_op = false;#true if it is an insert operation

        //Question Exists in the database
        if($question = DbInfo::TestQuestionExists($q_data["test_id"],$q_data["question_index"]))
        {
            $result_question_id = $question["question_id"];
            $update_query = "UPDATE test_questions SET test_id=?,question_text=?,question_type=?,number_of_options=?,marks_attainable=?,question_index=? WHERE question_id=".$question["question_id"];
        }
        else//Question does not exist in the database ~ Insert records
        {
            $update_query = "INSERT INTO test_questions(test_id,question_text,question_type,number_of_options,marks_attainable,question_index) VALUES (?,?,?,?,?,?)";
            $is_insert_op = true;
        }

        //Try preparing the query
        if($update_stmt = $dbCon->prepare($update_query))
        {
            $update_stmt->bind_param("issiii",$q_data["test_id"],$q_data["question_text"],$q_data["question_type"],$q_data["no_of_choices"],$q_data["marks_attainable"],$q_data["question_index"]);

            //If the query successfully executes
            if($update_stmt->execute())
            {
                //If it was an insert operation, then get the id of the last inserted value
                if($is_insert_op)
                {
                    $result_question_id = $update_stmt->insert_id;
                }
                echo "success updating the question";
                #Run update answers here
                echo "<br>Question ID : ".$result_question_id;
                return self::UpdateAnswers($result_question_id,$q_data["answers"]);#update the answers;
            }
            else #failed to execute the query
            {
                return false;
            }
        }
        else #failed to prepare the query
        {
            return null;
        }
    }

    //Update the answer in the database if it exists | Add the question to the database if it does not exist
    public static function UpdateAnswers($q_id,$answers_data)
    {
        global $dbCon; #db connection string
        $update_query = "";#the query that is used to update/insert records into the database. By default, blank

        foreach($answers_data as $ans_data)
        {
            #if the answer already exists in the database
            if($ans_found = DbInfo::QuestionAnswerExists($q_id,$ans_data["answer_index"]))#$ans_found is available incase we need it later ~ if not - to be deleted
            {
                $update_query = "UPDATE test_answers SET question_id=?,answer_text=?,right_answer=?,marks_attainable=?,answer_index=? WHERE question_id=$q_id AND answer_index=".$ans_data["answer_index"];
            }
            else#If the answer does not exist in the database
            {
                $update_query = "INSERT INTO test_answers(question_id,answer_text,right_answer,marks_attainable,answer_index) VALUES(?,?,?,?,?)";
            }

            //Attempt to prepare the query
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $ans_data["answer_index"] = (int)$ans_data["answer_index"];
                $update_stmt->bind_param("isiii",$q_id,$ans_data["answer_text"],$ans_data["right_answer"],$ans_data["marks_attainable"],$ans_data["answer_index"]);
               // echo "<br>Answer index:".$ans_data["answer_index"];

                if($update_stmt->execute())
                {
                    echo "<p>Success updating the answer</p>";
                }
                else #failed to execute the query
                {
                    echo "<p>Failed to execute the update answer query</p>";
                }
            }
            else #failed to prepare the query
            {
                echo "<p>Failed to prepare the update answer query</p>";
            }
        }
    }

        //Delete an answer from the database
        public static function DeleteQuestionAnswer($q_index,$answer_index)
        {
            global $dbCon;
            $delete_query = "DELETE FROM test_answers WHERE question_id=? AND answer_index=?";

            if($delete_stmt = $dbCon->prepare($delete_query))
            {
                $delete_stmt->bind_param("ii",$q_index,$answer_index);
                if($delete_stmt->execute())
                {
                    echo "<br>Successfully removed answer";
                }
                else
                {
                    echo "<br><b>Failed to remove answer.</b><br> Remove query failed to execute";
                }
            }
            else
            {
                echo "<br><b>Error :</b> Failed to prepare the delete answer query";
                return null;
            }
        }

        //Get the total number of marks used
        public static function GetMarksUsed($test,$question_index)
        {
            if($questions = DbInfo::GetTestQuestions($test["test_id"]))
            {
                $marks_allocated = 0;
                foreach($questions as $question)
                {
                     $marks_allocated += $question["marks_attainable"];
                }
                return $marks_allocated;
            }
            return false;
        }
    /*
    -----------------------------------------------------------------------------------------
                                UPDATING TEST QUESTION SUBMISSIONS
    -----------------------------------------------------------------------------------------
    */
    //Update a test question submission if it exists or add a new one if it does not exist;
    public static function UpdateTestQuestionSubmission($q_data)
    {
        global $dbCon;#db connection string

        //Returns user info if there is a logged in user ~ returns false if no user is logged in
        if($user_info = MySessionHandler::GetLoggedUserInfo())
        {
            $sub_info = array(
                "taker_id"=>$user_info["user_id"],
                "taker_type"=>$user_info["account_type"],
                "test_id"=>$q_data["test_id"],
                "question_index"=>$q_data["question_index"],
                "answers_provided"=>"",#to be updated below
                "skipped"=>$q_data["skipped"]
            );

            //Add answers provided into a string of concatenated comma separated values
            foreach($q_data["answers_provided"] as $answer_provided)
            {
                $sub_info["answers_provided"] .= $answer_provided.",";#concatenate the answer_index
            }

            //Query used to update/insert records into the database
            $update_query = "";

            if($submission_found = DbInfo::TestQueSubmissionExists($sub_info["taker_id"],$sub_info["taker_type"],$sub_info["test_id"],$sub_info["question_index"]))
            {
                $update_query = "UPDATE test_submissions SET taker_id=?,taker_type=?,test_id=?,question_index=?,answers_provided=?,skipped=? WHERE submission_id=".$submission_found["submission_id"];
            }
            else
            {
                $update_query = "INSERT INTO test_submissions(taker_id,taker_type,test_id,question_index,answers_provided,skipped) VALUES(?,?,?,?,?,?)";
            }

            //Attempt to prepare the query
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("isiisi",$sub_info["taker_id"],$sub_info["taker_type"],$sub_info["test_id"],$sub_info["question_index"],$sub_info["answers_provided"],$sub_info["skipped"]);

                if($update_stmt->execute())
                {
                    // echo "<p>Successfully updated question submission</p>";
                    return true;
                }
                else
                {
                    echo "<p>Failed to <b>run</b> query.<br>Error : ".$dbCon->error."</p>";
                    return false;
                }
            }
            else #failed to prepare query
            {
                echo "<p>Failed to <b>prepare</b> query.<br>Error : ".$dbCon->error."</p>";
                return null;
            }
        }
        else
        {
            echo "<p>No logged in user. Failed to update Test Question Submissions</p>";
            return false;
        }
    }
    /*
    -----------------------------------------------------------------------------------------
                               MANAGING TEST RETAKES
    -----------------------------------------------------------------------------------------
    */

    //Update Test retake ~ only used internally by other functions (private function)
    private static function UpdateTestRetake($test_id,$user_info)
    {
        global $dbCon;
        $date_taken = NULL; $retake_date=NULL; #Initialization to make the variables accessible in the scope of the function

        if($test = DbInfo::TestExists($test_id))
        {
            //Dates
            $date_taken = EsomoDate::GetCurrentDate();
            $retake_date = EsomoDate::GetDateSum($date_taken,array("days"=>$test["retake_delay_days"],"hours"=>$test["retake_delay_hours"],"min"=>$test["retake_delay_min"]));

        }
        else
        {
            echo "<p>Test with the id provided could not be found in database.<br><b>Accessed from UpdateTestRetake</b>, terminating execution of the function</p>";
            return false;
        }

        //Update query
        $update_query = "";

        //If the retake information exists in the database, update it, otherwise, add it to the database
        if($retake_info = DbInfo::GetTestRetake($test_id,$user_info))
        {
            $update_query = "UPDATE test_retakes SET test_id=?,taker_id=?,taker_type=?,date_taken=?,retake_date=? WHERE retake_id=".$retake_info["retake_id"];
        }
        else
        {
            $update_query = "INSERT INTO test_retakes(test_id,taker_id,taker_type,date_taken,retake_date) VALUES(?,?,?,?,?)";
        }

        //Prepare the query
        if ($update_stmt = $dbCon->prepare($update_query))
        {
            $update_stmt->bind_param("iisss",$test_id,$user_info["user_id"],$user_info["account_type"],$date_taken,$retake_date);

            //Try executing the update statement
            if($update_stmt->execute())
            {
                // echo "<p>Updated test retake info</p>";
                return true;
            }
            else #failed to execute the query
            {
                echo "<p>Failed to run query to update test retake info</p>";
                return false;
            }
        }
        else #failed to prepare the query
        {
            echo "<p>Failed to prepare query to update test retake info</p>";
            return null;
        }
    }

    /*
    -----------------------------------------------------------------------------------------
                               MARKING TESTS
    -----------------------------------------------------------------------------------------
    */
    //Enters the results into the database
    private static function StoreTestResults($results)
    {
        global $dbCon;

        $insert_query = "INSERT INTO test_results(test_id,taker_id,taker_type,grade,percentage,grade_text,verdict,answers_right,answers_wrong,completion_time) VALUES(?,?,?,?,?,?,?,?,?,?)";

        //Prepare the query for storing results
        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            $insert_stmt->bind_param("iisiissiii",$results['test_id'],$results['taker_id'],$results['taker_type'],$results['grade'],$results['percentage'],$results['grade_text'],$results['verdict'],$results['answers_right'],$results['answers_wrong'],$results['completion_time']);

            $insert_status = $insert_stmt->execute();

            //Failed to insert the records into the database
            if(!$insert_status)
            {
                echo "<p>Failed to store the test results in the database</p>";
            }

            return $insert_status;
        }
        else #Failed to prepare the query
        {
            return null;
        }
    }

    //Marks the test and returns an associative array containing the results information
    public static function MarkTest($test_id,$user_info)
    {
        $max_grade = 100; #default max grade for the test if we cannot find it in the database

        if($test = DbInfo::TestExists($test_id))
        {
            $max_grade = (int)$test["max_grade"];
        }


        //Get the question submissions for the currently test taker
        if($submissions = DbInfo::GetSpecificTestSubmissions($test_id,$user_info))
        {
            $test = DbInfo::TestExists($test_id);

            //Associative array storing results information. Db info to be printed in a pdf
            $results = array("test_id"=>$test_id,"taker_id"=>"","taker_type"=>"","first_name"=>$user_info["first_name"],"last_name"=>$user_info["last_name"],"full_name"=>$user_info["full_name"],"grade"=>"","max_grade"=>$max_grade,"percentage"=>"","grade_text"=>"","answers_right"=>0,"answers_wrong"=>0,"date_generated"=>"","completion_time"=>"","verdict"=>"PASS");

            #Variables for storing the information on the various questions
            $total_marks = 0;
            $answers_right = 0;
            $answers_wrong = 0;
            $question_id = 1;

            //TODO : Add loading bar and variable to keep track of result generation progress


            //Check every submission and calculate performance
            foreach($submissions as $sub)
            {
                //Check if the question exists. If it does, set the question_id
                if($question = DbInfo::TestQuestionExists($test_id,$sub["question_index"]))
                {
                    $question_id = $question["question_id"];

                    // Create  an array from csv answers in db
                    if($answers = DbInfo::GetArrayFromList($sub["answers_provided"]))
                    {
                        foreach($answers as $answer_index)
                        {
                            //If we found the answer in teh database
                            if($answer_found = DbInfo::QuestionAnswerExists($question_id,$answer_index))
                            {
                                //Check if it is the correct answer, if it is, ++marks and ++answers_right else ++answers_wrong
                                if($answer_found["right_answer"])
                                {
                                    $total_marks += $answer_found["marks_attainable"];
                                    $answers_right++;
                                }
                                else
                                {
                                    $answers_wrong++;
                                    continue 1;#check the next answer
                                }
                            }
                            else #Answer was not found in the database
                            {
                                echo "Answer was not found in the database";
                            }
                        }
                    }
                    else # Answers for this question could not be found
                    {
                        continue 1; #next loop iteration ~ Check the next question (submission)
                    }
                }
                else
                {
                    echo "Could not find the question in the database";
                    continue 1;
                }

            }#end of foreach $submissions

            #get the DateInterval representing the amount of time elapsed
            $time_elapsed = EsomoTimer::GetTimeElapsed($test,$user_info);
            $time_elapsed = (int)($time_elapsed->format("%s"));#get the number of minutes elapsed
            
            //Update some result values
            $grade_info = GradeHandler::GetGradeInfo($total_marks,$max_grade);

            $results["taker_id"] = $user_info["user_id"];
            $results["taker_type"] = $user_info["account_type"];

            $results["grade"] = $total_marks;
            $results["percentage"] = $grade_info["percentage"]."% ";
            $results["grade_text"] = $grade_info["grade_text"];
            $results["answers_right"] = $answers_right;
            $results["answers_wrong"] = $answers_wrong;
            $results["date_generated"] = "";
            $results["completion_time"] = $time_elapsed;


            //Determine the verdict of test results
            if($pass_grade = $test["passing_grade"])
            {
                if($results["grade"]>=$pass_grade)
                {
                    $results["verdict"] = "PASS";
                }
                else
                {
                    $results["verdict"] = "FAIL";
                }
            }
            self::UpdateTestRetake($test_id,$user_info);
            self::StoreTestResults($results); # Store the test results in the database
            return $results;
        }
        else
        {
            return false;
        }

    }

    //Delete a test's and user's submission ~ typically used once the submission data has been used to mark the exam
    public static function DeleteSpecificSubmission($test_id,$user_info)
    {
        global $dbCon; #db connection string
        $delete_query = "DELETE * FROM test_submissions WHERE test_id=? AND taker_id=? AND taker_type=?";

        if($delete_stmt = $dbCon->prepare($delete_query))
        {
            $delete_stmt->bind_param("iis",$test_id,$user_info["user_id"],$user_info["account_type"]);
            if($delete_stmt->execute())
            {
                echo "Deleted ".$delete_stmt->affected_rows." specified submissions";
                return true;
            }
            else
            {
                echo "Failed to run query to delete specific submissions";
                return false;
            }
        }
        else
        {
            echo "Failed to prepare query to delete specific submissions";
            return null;
        }

    }

    /*
    -----------------------------------------------------------------------------------------
                               PROCESSING TEST RESULTS
    -----------------------------------------------------------------------------------------
    */

    //Generates a report for the results as well as writes to pdf
    public static function GenerateTestResultsReport($results)
    {
        $report = "";
        $report .= "<h3>Test results Report</h3><ul>";
            $report .= "<li>Full Name : ".$results["full_name"]."</li>";
            $report .= "<li>Grade (marks) :".$results["grade"]." out of ".$results["max_grade"]."</li>";
            $report .= "<li>Percentage : ".$results["percentage"]."</li>";
            $report .= "<li>Grade Achieved : ".$results["grade_text"]."</li>";
            $report .= "<li>Verdict : ".$results["verdict"]."</li>";
            $report .= "<li>Answers right : ".$results["answers_right"]."</li>";
            $report .= "<li>Answers wrong : ".$results["answers_wrong"]."</li>";
        $report .= "</ul>";

        return $report;
    }

    /*
    -----------------------------------------------------------------------------------------
                               RESOURCES FUNCTIONS
    -----------------------------------------------------------------------------------------
    */

    //Upload resource information to the database
    public static function ResourcesDbUpload($resource_name,$subject_id,$description,$file_type,$file_link,$teacher_id)//TO TEST
    {
        global $dbCon;

        #if the assignment exists - safety check
        $insert_query = "INSERT INTO resources(resource_name, subject_id, description, file_type, file_link, teacher_id) VALUES(?,?,?,?,?,?)";

            //Prepare query to create assignment submission
            if($insert_stmt = $dbCon->prepare($insert_query))
            {
                $insert_stmt->bind_param("sisssi",$resource_name,$subject_id,$description,$file_type,$file_link,$teacher_id);

                #create assignment submission query ran successfully
                if($insert_stmt->execute())
                {
                    return true;
                }
                else #failed to run create ass_submission query
                {
                    return false;
                }
            }
            else #failed to prepare query to create assignment submission
            {
                return null;
            }

        }

    //Update resource in the database
    public static function UpdateResource($resource_id,$description,$subject_id,$teacher_id)//TO TEST
    {
        global $dbCon;

        $update_query = "UPDATE resources SET description=?, subject_id=? WHERE teacher_id=? AND resource_id=?";

            //Prepare query to update resource submission
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("siii",$description,$subject_id,$teacher_id,$resource_id);

                #update resource query ran successfully
                if($update_stmt->execute())
                {
                    return true;
                }
                else #failed to run update resource query
                {
                    echo $dbCon->error;//TODO: REMOVE THIS LATER #DEBUG ONLY
                    return false;
                }
            }
            else #failed to prepare query to create resource submission
            {
                return null;
            }

        }
    //Delete resource from database
    public static function DeleteResource($resource_id)
    {
        
        $resource = DbInfo::ResourceExists($resource_id);
        
        if($resource)
        {
            $uploader = new EsomoUploader();
            $del_resource = self::DeleteBasedOnSingleProperty("resources","resource_id",$resource_id,"i");
            
            $del_resource_file = $uploader->DeleteResourceFile($resource["resource_name"]);
            
            return ($del_resource && $del_resource_file);#return the status based on whether or not it could delete the resource or not.
        }
        else #failed to find the resource
        {
            return $resource;
        }

    }
};#END OF CLASS

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_POST['action'])) {
    
    sleep(1);//Sleep for  ashort amount of time, to reduce odds of a DDOS working.
    
    $user_info = MySessionHandler::GetLoggedUserInfo();#store the logged in user info anytime an AJAX call is made

    //Mail related files
    require_once("../classes/mail_generator.php");
    require_once("email_handler.php");

    switch($_POST['action']) {

        case 'CreateStudentAccount': #Create a student account
            require_once("../classes/student.php");
            $data = $_POST["data"];

            $student = new Student();
            $create_status = $student->CreateStudentAccount($data);

            // //Generate email for the account created
            // $email = EsomoMailGenerator::NewSuperuserAccEmail($data["first_name"],$data["last_name"],$data["username"],$data["email"]);
            // $email_status = EmailHandler::EsomoSendEmail($email);

            echo ($create_status);#Print out the create status for feedback handling by javascript
        break;

        case 'CreateTeacherAccount': #Create a teacher account
            require_once("../classes/teacher.php");
            $data = $_POST["data"];
            $create_status = false;#The create status for the account ~ true on success | false or null on failure

            $teacher = new Teacher();
            $create_status = $teacher->CreateTeacher($data);

            //Generate email for the account created
            $email = EsomoMailGenerator::NewTeacherAccEmail($data["first_name"],$data["last_name"],$data["username"],$data["email"]);
            $email_status = EmailHandler::EsomoSendEmail($email);

            echo ($create_status);#Print out the create status for feedback handling by javascript
        break;

        case 'CreatePrincipalAccount': #Create a principal account
            require_once("../classes/principal.php");
            $data = $_POST["data"];
            $create_teacher_acc = $_POST["create_teacher_acc"];
            $principal = new Principal();

            $create_status = false;#The create status for the account ~ true on success | false or null on
            //If create a corresponding teacher account is selected
            if($create_teacher_acc)
            {
                require_once("../classes/teacher.php");#include teacher class
                $create_status = $principal->CreatePrincipalTeacherAccount($data);
            }
            else #Only create principal account
            {
                echo "Only create the principal";
                $create_status = $principal->CreatePrincipal($data);
            }

            //Generate email for the account created
            $email = EsomoMailGenerator::NewPrincipalAccEmail($data["first_name"],$data["last_name"],$data["username"],$data["email"]);
            $email_status = EmailHandler::EsomoSendEmail($email);
            
            echo ($create_status);#Print out the create status for feedback handling by javascript
        break;

        case 'CreateSuperuserAccount': #Create a superuser account
            require_once("../classes/superuser.php");
            $data = $_POST["data"];

            $superuser = new Superuser();
            $create_status = $superuser->CreateSuperuser($data);
            
            //Generate email for the account created
            $email = EsomoMailGenerator::NewSuperuserAccEmail($data["first_name"],$data["last_name"],$data["username"],$data["email"]);
            $email_status = EmailHandler::EsomoSendEmail($email);
            
            echo ($create_status);#Print out the create status for feedback handling by javascript
        break;

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
        case 'UpdateScheduleInfo':

            $args = array(
                'schedule_id' => $_POST['scheduleid'],
                'schedule_title' => $_POST['scheduletitle'],
                'schedule_description' => $_POST['scheduledescription'],
                'schedule_objectives' => $_POST['scheduleobjectives'],
                'class_id' => $_POST['scheduleclassroom'],
                'teacher_id' => $_SESSION['admin_acc_id'],
                'due_date' => $_POST['duedate']
            );

            $result = DbHandler::UpdateScheduleInfo($args['schedule_id'],$args['schedule_title'],$args['schedule_description'],$args['schedule_objectives'],$args['class_id'],$args['teacher_id'],$args['due_date']);

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

            break;
        case 'DeleteSchedule':

            $schedule_id = $_POST['scheduleid'];

            if(isset($schedule_id)) {

                $result = DbHandler::DeleteSchedule($schedule_id);

                echo $result;

            }

            break;
        //Create a Test
        case 'CreateTest':
            $test_data = &$_POST["test_data"];
            echo DbHandler::CreateTest($test_data);
        break;
        
        //Delete a test
        case 'DeleteTest':
            $test_id = &$_POST["test_id"];
            $delete_test = DbHandler::DeleteTest($test_id);
            if($delete_test)
            {
                echo "Deleted the test";
            }
            else
            {
                echo "<p>Failed to fully delete the test. <br><b>View Debug</b> for more info</p>";
            }
            

        //Delete question answer
        case 'DeleteQuestionAnswer':
            $answers_data = @$_POST["answers_data"];

            #If the answers data is set and is not false
            if(isset($answers_data) && $answers_data)
            {
                foreach($answers_data as $ans_data)
                {
                    DbHandler::DeleteQuestionAnswer($ans_data["question_index"],$ans_data["answer_index"]);
                }
            }
        break;
        
        //Update the test
        case 'UpdateEditTest':
            $edit_data = ($_POST["data"]);
            $update_test = DbHandler::UpdateTest($edit_data,$user_info);
            echo $update_test;
        break;

        //Update a question in the test
        case 'UpdateTestQuestion':
            //~Computational delay to prevent bots from spamming and DDOS
            //sleep(200);
            $q_data = &$_POST["q_data"];
            DbHandler::UpdateQuestion($q_data);

            #once this is done redirect the user to the redirect page as soon as the data is updated
        break;

        //Update a test submission ~ Add submission info into the database
        case 'UpdateTestSubmission':
            //~Computational delay to prevent bots from spamming and DDOS
            //sleep(200);
            $q_data = &$_POST["q_data"];
            DbHandler::UpdateTestQuestionSubmission($q_data);
        break;

        //Complete a test ~ Mark the test and return results
        case 'CompleteTakingTest':
            $q_data = &$_POST["q_data"]; #question data
            $test_id = htmlspecialchars($q_data["test_id"]); #current test id

            DbHandler::UpdateTestQuestionSubmission($q_data);//Add the current question submission

            //If the test has already been taken
            if($retake_info = Dbinfo::GetTestRetake($test_id,$user_info))
            {
                $retake_date_time = strtotime($retake_info["retake_date"]);
                $time_has_elapsed = EsomoDate::DateTimeHasElapsed($retake_date_time);

                //If the current time is a time past the retake time ~ allow for retake
                if($time_has_elapsed)
                {
                    $test_results = DbHandler::MarkTest($test_id,$user_info);
                    DbHandler::GenerateTestResultsReport($test_results);
                }
                else
                {
                    echo "<p>You need to wait before you can retake the exam</p>";
                }
            }
            else # the test does not exist in the database
            {
                $test_results = DbHandler::MarkTest($test_id,$user_info);
                DbHandler::GenerateTestResultsReport($test_results);
            }

        break;

        case 'ResourcesUpload':

            $data = json_decode($_POST['data']);

            //Attempt uploading the files first
            $uploader = new EsomoUploader();
            $failed_files = $uploader->UploadFile('resource');     
            
            $file_name = "";

            for($f = 0; $f < count($data); $f++) 
            {
                $is_failed_file = in_array($f,$failed_files);
                $result = false;
                //If the file did not fail to upload, add it to the database
                if(!$is_failed_file)
                {
                    $file_name = $_FILES['file-'.$f]['name'];
                    $args = array(
                        'resource_name' => $file_name,
                        'subject_id' => $data[$f]->subjectid,
                        'description' => $data[$f]->description,
                        'file_type' => $_FILES['file-'.$f]['type'],
                        'file_link' => ('./uploads/resources/'.$file_name),
                        'teacher_id' => $_SESSION['admin_acc_id']
                    );

                    $result = DbHandler::ResourcesDbUpload($args['resource_name'],$args['subject_id'],$args['description'],$args['file_type'],$args['file_link'],$args['teacher_id']);

                    $result = true;
                }
            }
            //$failed_files = ['file' => 'ddd'];

            if(count($failed_files) > 0) {//If inserting the data to the database is true, upload file
                echo json_encode(['failed_files' => $failed_files, 'status' => false]);

            } else {
                echo json_encode(['failed_files' => $failed_files, 'status' => true]);
            }

            unset($_FILES);

        break;

        case 'AssignmentSubmit':

            $data = json_decode($_POST['data']);

            //Attempt uploading the files first
            if (count($_FILES) == 0 && $data->attachments == '') {

                $is_failed_file = 0;
            } else {
                $uploader = new EsomoUploader();
                $failed_files = $uploader->UploadFile('ass_submission');
                $is_failed_file = count($failed_files);
            }

            $result = false;
            //If the file did not fail to upload, add it to the database
            if($is_failed_file < 1)
            {

                $args = array(
                    'submission_title' => $data->submissiontitle,
                    'ass_id' => $data->assid,
                    'student_id' => $data->studentid,
                    'submission_text' => $data->submissiontext,
                    'attachments' => $data->attachments,
                    'submitted' => $data->submitted
                );

                $result = DbHandler::UpdateAssignmentSubmissionInfo($args['submission_title'],null,$args['student_id'],$args['attachments'],$args['submission_text'],$args['submitted'],$args['ass_id']);

                $result = true;
            }

            if(count($failed_files) > 0) {//If inserting the data to the database is true, upload file
                echo json_encode(['failed_files' => $failed_files, 'status' => false]);

            } else {
                echo json_encode(['failed_files' => $failed_files, 'status' => true]);
            }

            unset($_FILES);

        break;

        case 'UpdateResource':
            $args = array(
                'resource_id' => $_POST['resource_id'],
                'description' => $_POST['description'],
                'subject_id' => $_POST['subject_id'],
                'teacher_id' => $_SESSION['admin_acc_id']
            );

            $result = DbHandler::UpdateResource($args['resource_id'], $args['description'], $args['subject_id'], $args['teacher_id']);

            if($result) {
                echo json_encode($args);
            } else {
                echo json_encode(['error_message' => $result]);
            }

            break;
        case 'DeleteResource':

            $result = DbHandler::DeleteResource($_POST['resourceid']);

            if($result) {
                echo '1';
            } else {
                echo '0';
            }

            break;

        case 'UpdateAssignmentInfo':

            $postData = json_decode($_POST['data']);

            $attachments = '';
            $result = array();

            //var_dump($postData);
            if (count($_FILES) > 0) {

                for($g = 0; $g < count($_FILES); $g++){
                    $attachments .= $_FILES['file-'.$g]['name'] . ',';
                }
            }


            for($h = 0; $h < count($postData->classids); $h++) {

                $args = array (
                    "ass_id" => (empty($_POST['assignmentid']) ? 0 : $_POST['assignmentid']),
                    "teacher_id"=>$_SESSION['admin_acc_id'],
                    "ass_title"=> $postData->assignmenttitle,
                    "ass_description"=>$postData->assignmentdescription,
                    "class_id"=>$postData->classids[$h],
                    "due_date"=>$postData->duedate,
                    "attachments"=>$attachments,
                    "max_grade"=>$postData->maxgrade,
                    "comments_enabled"=>$postData->cancomment
                );

                $r = DbHandler::UpdateAssignmentInfo($args);

                array_push($result,$r);
            }

            if (count($_FILES) > 0) {
                //Attempt uploading the files first
                $uploader = new EsomoUploader();
                $failed_files = $uploader->UploadFile('assignment');
            } else {
                $failed_files = array();
            }

            $returnresult = array (
                'failedFiles' => $failed_files,
                'result' => $result
            );

            echo json_encode($returnresult);

        break;

        case 'SuperuserDeleteStudents':
            $data = ($_POST["data"]);
            $acc_ids = null;
            if(isset($data))
            {
                $acc_ids = $data["acc_ids"];
            }

            if(@$acc_ids && isset($acc_ids))
            {
                $delete_status = true;

                //For each account, delete that account
                foreach($acc_ids as $acc_id)
                {
                    $delete_status = $delete_status && DbHandler::DeleteStudentAccount($acc_id);
                }

                echo $delete_status;
            }
            else
            {
                echo false;
            }
        break;

        case 'SuperuserResetStudents':
            $data = ($_POST["data"]);
            $acc_ids = null;
            if(isset($data))
            {
                $acc_ids = $data["acc_ids"];
            }

            if(@$acc_ids && isset($acc_ids))
            {
                $reset_status = true;

                //For each account, delete that account
                foreach($acc_ids as $acc_id)
                {
                    $reset_status = $reset_status && DbHandler::ResetStudentAccount($acc_id);
                }

                echo $reset_status;
            }
            else
            {
                echo false;
            }
        break;

        case 'SuperuserDeleteTeachers':
            $data = ($_POST["data"]);
            $acc_ids = null;
            if(isset($data))
            {
                $acc_ids = $data["acc_ids"];
            }

            if(@$acc_ids && isset($acc_ids))
            {
                $delete_status = true;

                //For each account, delete that account
                foreach($acc_ids as $acc_id)
                {
                    $delete_status = $delete_status && DbHandler::DeleteTeacherAccount($acc_id);
                }

                echo $delete_status;
            }
            else
            {
                echo false;
            }
        break;

        case 'SuperuserResetTeachers':
            $data = ($_POST["data"]);
            $acc_ids = null;
            if(isset($data))
            {
                $acc_ids = $data["acc_ids"];
            }

            if(@$acc_ids && isset($acc_ids))
            {
                $reset_status = true;

                //For each account, delete that account
                foreach($acc_ids as $acc_id)
                {
                    $reset_status = $reset_status && DbHandler::ResetTeacherAccount($acc_id);
                }

                echo $reset_status;
            }
            else
            {
                echo false;
            }
        break;
        case 'SuperuserDeletePrincipals':
            $data = ($_POST["data"]);
            $acc_ids = null;
            if(isset($data))
            {
                $acc_ids = $data["acc_ids"];
            }

            if(@$acc_ids && isset($acc_ids))
            {
                $delete_status = true;

                //For each account, delete that account
                foreach($acc_ids as $acc_id)
                {
                    $delete_status = $delete_status && DbHandler::DeletePrincipalAccount($acc_id);
                }

                echo $delete_status;
            }
            else
            {
                echo false;
            }
        break;

        case 'SuperuserResetPrincipals':
            $data = ($_POST["data"]);
            $acc_ids = null;
            if(isset($data))
            {
                $acc_ids = $data["acc_ids"];
            }

            if(@$acc_ids && isset($acc_ids))
            {
                $reset_status = true;

                //For each account, delete that account
                foreach($acc_ids as $acc_id)
                {
                    $reset_status = $reset_status && DbHandler::ResetPrincipalAccount($acc_id);
                }

                echo $reset_status;
            }
            else
            {
                echo false;
            }
            break;

        //Update and delete assignment comments
        case 'UpdateAssComment':
            $comment_id = $_POST['id'];
            $comment_text = $_POST['comment_text'];

            $result = DbHandler::UpdateAssComment($comment_id, $comment_text);

            echo json_encode($result);

            break;
        case 'DeleteAssComment':
            $result = DbHandler::DeleteAssComment($_POST['id']);

            echo json_encode($result);

            break;
        //Update and delete assSubmission comments
        case 'UpdateAssSubmissionComment':
            $comment_id = $_POST['id'];
            $comment_text = $_POST['comment_text'];

            $result = DbHandler::UpdateAssSubmissionComment($comment_id, $comment_text);

            echo json_encode($result);

            break;
        case 'DeleteAssSubmissionComment':
            $result = DbHandler::DeleteAssSubmissionComment($_POST['id']);

            echo json_encode($result);

            break;
        //Update and delete schedule comments
        case 'UpdateScheduleComment':
            $comment_id = $_POST['id'];
            $comment_text = $_POST['comment_text'];

            $result = DbHandler::UpdateScheduleComment($comment_id, $comment_text);

            echo json_encode($result);

            break;
        case 'DeleteScheduleComment':
            $result = DbHandler::DeleteScheduleComment($_POST['id']);

            echo json_encode($result);

            break;

        //Update account passwords
        case "UpdateAccountPassword":
            $data = $_POST["data"];
            $update_status = DbHandler::UpdateAccountPassword($user_info,$data);

            echo $update_status;
        break;
        
        //Report problem
        case "ReportProblem":

            break;
        default:
            return null;
            break;
    }

} else {
    return null;
}







