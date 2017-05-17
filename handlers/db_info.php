<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Connection to the database
include_once(realpath(dirname(__FILE__) . "/../handlers/error_handler.php")); #Printing error messages
require_once (realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Allows connection to database
require_once(realpath(dirname(__FILE__). "/../handlers/date_handler.php")); #Handles date related functions

//Time constants for use in switch statements dealing with timeframes
const ALL_TIME = "all";
const TODAY = "today";
const YESTERDAY = "yesterday";
const LAST_7_DAYS = "last7days";
const THIS_MONTH = "this_month";
const LAST_MONTH = "last_month";
const LAST_30_DAYS = "last30days";

#USED TO RETRIEVE INFORMATION FROM THE DATABASE
class DbInfo
{
    //Constants for the different admin account types
    const TEACHER_ACCOUNT = "teacher";
    const PRINCIPAL_ACCOUNT = "principal";
    const SUPERUSER_ACCOUNT = "superuser";

    //Retrieves and returns the first admin account found based on the criteria found
    public static function GetAdminAccount($username_input,$acc_type_input)
    {
        global $dbCon;

        $prepare_error = "Couldn't prepare query to retrieve admin account information. <br><br> Technical information : ";

        $query = "SELECT * FROM admin_accounts WHERE username=? AND account_type=?";

        if($stmt = $dbCon->prepare($query))
        {
            $stmt->bind_param("ss",$username_input,$acc_type_input);
            $stmt->execute();#retrieve records from the database

            $result = $stmt->get_result();

            //If we found any records
            if($result->num_rows>0)
            {
                foreach ($result as $admin_account)
                {
                    return $admin_account;
                }
            }
            else //no records were found - return null
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

    //Retrieves and returns the first admin account found based on the criteria found
    public static function GetStudentAccount($username_input)
    {
        global $dbCon;

        $prepare_error = "Couldn't prepare query to retrieve student account information. <br><br> Technical information : ";

        $query = "SELECT * FROM student_accounts WHERE username=?";

        if($stmt = $dbCon->prepare($query))
        {
            $stmt->bind_param("s",$username_input);
            $stmt->execute();#retrieve records from the database
        
            $result = $stmt->get_result();

            //If we found any records
            if($result->num_rows>0)
            {
                foreach ($result as $student_account)
                {
                    return $student_account;
                }
            }
            else //no records were found - return null
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

    //Gets all records from a given table and returns them if the query was successful, returns null if query failed and false if no records were found
    private static function GetAllRecordsFromTable($table_name)#WORKING
    {
        global $dbCon;#database connection
        $select_query = "SELECT * FROM $table_name";
        if($result = $dbCon->query($select_query))#run the query, returns false if it fails
        {
            if ($result->num_rows == 0)#if the number of students found was 0, return false
            {
                return false;
            }
            return $result;
        }
        else
        {
            return null;
        }
    }
    #Get all students
    public static function GetAllStudents()
    {
        return self::GetAllRecordsFromTable("student_accounts");
    }

//Gets all records from the admin_accounts table and returns them if the query was successful, returns null if query failed and false if no records were found - 
    private static function GetAllAdminRecordsFromTable($acc_type)
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM admin_accounts WHERE account_type='$acc_type'";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails
    
        if ($result->num_rows == 0)#if the number of students found was 0, return false
        {
            return false;
        }
        return $result;        
    }

        #Get all teachers
        public static function GetAllTeachers()
        {
            return self::GetAllAdminRecordsFromTable("teacher");
        }

        #Get all principals
        public static function GetAllPrincipals()
        {
            return self::GetAllAdminRecordsFromTable("principal"); 
        }

        #Get all superusers
        public static function GetAllSuperusers()
        {
            return self::GetAllAdminRecordsFromTable("superuser");
        }

    //Get the student search result
    public static function GetStudentSearchResult($searchQuery,$filters = array("adm_no"=>false,"first_name"=>false,"last_name"=>false))
    {
        global $dbCon;#database connection

        #Table column names : adm_no first_name last_name
        $search_query = "SELECT * FROM student_accounts WHERE adm_no=? OR first_name=? OR last_name=?";

        $search_stmt;#statement used to store prepared query

        #if Either all the filters are selected or no filter is selected - search using the general scope
        if (
            ($filters["adm_no"] && $filters["first_name"] && $filters["last_name"]) ||
            (!$filters["adm_no"] && !$filters["first_name"] && !$filters["last_name"])
        )
        {
            $search_query = "SELECT * FROM student_accounts WHERE adm_no=LIKE(%?%) OR first_name=LIKE(%?%) OR last_name=LIKE(%?%)";
        }
        
        elseif ($filters["adm_no"])#if adm_no is checked as a filter - value is not the default false
        {
            
            if(!$filters["first_name"] && !$filters["last_name"])
            {
                $search_query = "SELECT * FROM student_accounts WHERE adm_no=LIKE(%?%)"; 
            }
            if ($filters["first_name"])
            {

            }
            elseif ($filters["last_name"]) {
                # code...
            }

        }
        elseif ($filters["first_name"])#first name and/or last name but no adm_no
        {
            $search_query = "SELECT * FROM student_accounts WHERE adm_no=LIKE(%?%) OR first_name=LIKE(%?%) OR last_name=LIKE(%?%)";            
        }
        elseif ($filters["last_name"])#last name only
        {
            $search_query = "SELECT * FROM student_accounts WHERE adm_no=LIKE(%?%) OR first_name=LIKE(%?%) OR last_name=LIKE(%?%)";            
        }
        
    }    

    //Get single admin account by ID , default acc type is teacher but this can be passed in as different parameter
    protected static function GetAdminById($acc_id,$acc_type="teacher")#protected to avoid random calls which may lead to typos
    {
        global $dbCon;
        $select_query = "SELECT * FROM admin_accounts WHERE acc_id=? AND account_type=?";

        $prepare_error = "Couldn't prepare query to retrieve admin account information by id. <br><br> Technical information : ";
        
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("is",$acc_id,$acc_type);
            $select_stmt->execute();

            $select_result = $select_stmt->get_result();

            #if records could be found
            if($select_result->num_rows > 0)
            {
                
                foreach($select_result as $result)
                {
                    return $select_result;#return the records
                }
            }   
            else #if no records were found
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

        #Get teacher account by ID : convenience function
        public static function GetTeacherById($acc_id)
        {
            if($teachers = self::GetAdminById($acc_id,$acc_type="teacher"))
            {
                if($teachers->num_rows>0)
                {
                    foreach($teachers as $teacher)
                    {
                        return $teacher;
                    }
                }
                else
                {
                    return self::GetAdminById($acc_id,$acc_type="teacher");
                }

            }
            else
            {
                return self::GetAdminById($acc_id,$acc_type="teacher");
            }
        }
    
        #Get principal account by ID : convenience function
        public static function GetPrincipalById($acc_id)
        {
            return self::GetAdminById($acc_id,$acc_type="principal");
        }

        #Get superuser account by ID : convenience function
        public static function GetSuperuserById($acc_id)
        {
            return self::GetAdminById($acc_id,$acc_type="superuser");
        }
    
    //Get single student account by ID
    public static function GetStudentByAccId($acc_id)
    {
        $students =  self::SinglePropertyExists("student_accounts","acc_id",$acc_id,"i");

       if(!empty($students) && isset($students))
       {
            foreach($students as $student)
            {
                return $student;
            }
       } 
       else
       {
           return $students;
       }
    }
/*----------------------------------------------------------------------------------------------------------
                    STUDENT  AND OTHER SINGLE PROPERTY VALIDATION QUERIES
----------------------------------------------------------------------------------------------------------*/

    //Checks if a single property exists. Private function - only used as convenience by other functions
    private static function SinglePropertyExists($table_name,$column_name,$prop_name,$prop_type,$prepare_error="Error preparing  info query. <br>Technical information :")#prop type is string used for bind_params
    {
        global $dbCon;

        $select_query = "SELECT * FROM $table_name WHERE $column_name=?";
        if ($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param($prop_type,$prop_name);
            
            if($select_stmt->execute())
            {
                $result = $select_stmt->get_result();
                if($result->num_rows>0)#found records
                {
                    return $result;
                }   
                else
                {
                    return false;
                }
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

    #Check if an student account with that student_id exists
    public static function StudentIdExists($std_id)
    {
        $students =  self::SinglePropertyExists("student_accounts","adm_no",$std_id,"i");

       if(!empty($students) && isset($students))
       {
            foreach($students as $student)
            {
                return $student;
            }
       } 
       else
       {
           return $students;
       }
    }

    #Check if an student account with that username exists
    public static function StudentUsernameExists($std_username)
    {
        return self::SinglePropertyExists("student_accounts","username",$std_username,"s");
    }

/*----------------------------------------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------------------------------------
                    ADMIN SINGLE PROPERTY VALIDATION QUERIES
----------------------------------------------------------------------------------------------------------*/

   //Checks if a single property exists. Private function - only used as convenience by other functions
    private static function SingleAdminPropertyExists($table_name,$column_name,$prop_name,$prop_type,$acc_type,
    $prepare_error="Error preparing admin info query. <br>Technical information : ")#prop type is string used for bind_params
    {
        global $dbCon;

        $select_query = "SELECT * FROM $table_name WHERE $column_name=? AND account_type = ?";
        if ($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param($prop_type,$prop_name,$acc_type);
            
            if($select_stmt->execute())
            {
                $result = $select_stmt->get_result();

                if($result->num_rows>0)#found records
                {
                    return $result;
                }   
                else
                {
                    return $result;
                }
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


    //Check if an admin account with that staff_id exists
    protected static function AdminStaffIdExists($admin_staff_id,$acc_type)
    {
        return self::SingleAdminPropertyExists("admin_accounts","staff_id",$admin_staff_id,"is",$acc_type);
    }
    
        #Check if the teacher staff_id exists
        public static function TeacherStaffIdExists($admin_staff_id)
        {
            return self::AdminStaffIdExists($admin_staff_id,"teacher");
        }
        
        #Check if the principal staff_id exists
        public static function PrincipalStaffIdExists($admin_staff_id)
        {
            return self::AdminStaffIdExists($admin_staff_id,"principal");
        }

        #Check if the superuser staff_id exists
        public static function SuperuserStaffIdExists($admin_staff_id)
        {
            return self::AdminStaffIdExists($admin_staff_id,"superuser");
        }
    

    //Check if an admin account with that username exists
    protected static function AdminUsernameExists($admin_username,$acc_type)
    {
        return self::SingleAdminPropertyExists("admin_accounts","username",$admin_username,"ss",$acc_type);
    }

        #Check if the teacher username exists
        public static function TeacherUsernameExists($admin_username)
        {
            return self::AdminUsernameExists($admin_username,"teacher");
        }
        
        #Check if the principal username exists
        public static function PrincipalUsernameExists($admin_username)
        {
            return self::AdminUsernameExists($admin_username,"principal");
        }
        
        #Check if the superuser username exists
        public static function SuperuserUsernameExists($admin_username)
        {
            return self::AdminUsernameExists($admin_username,"superuser");
        }
    
    //Check if an admin account with that email address exists
    protected static function AdminEmailExists($admin_email,$acc_type)
    {
        return self::SingleAdminPropertyExists("admin_accounts","email",$admin_email,"ss",$acc_type);
    }

        #Check if the teacher staff id exists
        public static function TeacherEmailExists($admin_email)
        {
            return self::AdminEmailExists($admin_email,"teacher");
        }

        #Check if the principal staff id exists
        public static function PrincipalEmailExists($admin_email)
        {
            return self::AdminEmailExists($admin_email,"principal");
        }

        #Check if the superuser staff id exists
        public static function SuperuserEmailExists($admin_email)
        {
            return self::AdminEmailExists($admin_email,"superuser");
        }
 
/*----------------------------------------------------------------------------------------------------------*/    

/*----------------------------------------------------------------------------------------------------------
                    TEACHER ACCOUNT  - CLASSROOM | ASSIGNMENT | SCHEDULES | TESTS  FUNCTIONS
----------------------------------------------------------------------------------------------------------*/

    //Checks if the classroom with the given id exists, returns result if it does, and false if it doesn't
    public static function ClassroomExists($class_id)
    {
        $classroom_found = self::SinglePropertyExists("classrooms","class_id",$class_id,"i");
        if($classroom_found)
        {
            //If we can find the classroom, then return the first instance of the found classroom.
            if($classroom_found->num_rows>0)
            {
                foreach($classroom_found as $classroom)
                {
                    return $classroom;
                }
            }
        }

        //otherwise return the value of classroom found | in this case either false or null
        return $classroom_found;
    }


    //Check if the Classroom code stated exists
    public static function ClassroomCodeExists($class_code)
    {
        return self::SinglePropertyExists("classrooms","class_code",$class_code,"s");
    }

    //Checks if a student is in a certain classroom, returns result if the student is in the classroom and false if not -Incomplete
    public static function StudentExistsInClassroom($class_id,$student_id)
    {
        global $dbCon;#connection string mysqli object

        if($classrooms = self::SinglePropertyExists("classrooms","class_id",$class_id,"i"))
        {
            foreach ($classrooms as $classroom)
            {
                $students = $classroom["student_ids"];#the student ids of the students in this class
                break;
            }

            #Check if the student exists in the student array
        }
    }
       
    //Checks if the assignment with the given id exists, returns true on success | false if no records found | null if query couldn't execute
    public static function AssignmentExists($ass_id)
    {
        
        if($assignments = self::SinglePropertyExists("assignments","ass_id",$ass_id,"i"))
        {
            if($assignments->num_rows > 0 )
            {
                foreach($assignments as $assignment)
                {
                    return $assignment;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return self::SinglePropertyExists("assignments","ass_id",$ass_id,"i");
        }
    }

    //Checks if the assignment submission with the given submission_id exists, returns true on success | false if no records found | null if query couldn't execute
    public static function AssSubmissionExists($submission_id)
    {
        if($ass_submissions = self::SinglePropertyExists("ass_submissions","submission_id",$submission_id,"i"))
        {
            if($ass_submissions->num_rows > 0 )
            {
                foreach($ass_submissions as $submission)
                {
                    return $submission;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return self::SinglePropertyExists("ass_submissions","submission_id",$submission_id,"i");
        }
    }

    //Get the returned assignments ~ returns the returned assignments
    public static function GetReturnedAssSubmissions($teacher_id)
    {
        global $dbCon;

        $select_query = "SELECT * FROM ass_submissions  
        INNER JOIN assignments
        ON ass_submissions.ass_id = assignments.ass_id 
        WHERE ass_submissions.returned=1 AND assignments.teacher_id = $teacher_id";

        if($select_result = $dbCon->query($select_query))
        {
            return $select_result;
        }
        else
        {
            return false;
        }
    }

    //Get the returned assignments ~ returns the returned assignments
    public static function GetUnreturnedAssSubmissions($teacher_id)
    {
        global $dbCon;

        $select_query = "SELECT * FROM ass_submissions  
        INNER JOIN assignments
        ON ass_submissions.ass_id = assignments.ass_id 
        WHERE ass_submissions.returned=0 AND assignments.teacher_id = $teacher_id";

        if($select_result = $dbCon->query($select_query))
        {
            return $select_result;
        }
        else
        {
            return false;
        }
    }
    //Checks if the schedule with the given id exists, returns true on success | false if no records found | null if query couldn't execute
    public static function ScheduleExists($schedule_id)
    {

        if($schedules = self::SinglePropertyExists("schedules","schedule_id",$schedule_id,"i"))
        {
            if($schedules->num_rows>0)
            {
                foreach($schedules as $schedule)
                {
                    return $schedule;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return self::SinglePropertyExists("schedules","schedule_id",$schedule_id,"i");
        }
    }
    
    //Checks if the schedule with the given guid_id exists, returns true on success | false if no records found | null if query couldn't execute
    public static function ScheduleExistsByGuid($guid_id)
    {
        return self::SinglePropertyExists("schedules","guid_id",$guid_id,"s");
    }

    //Checks if the test with the given id exists, returns true on success | false if no records found | null if query couldn't execute
    public static function TestExists($test_id)
    {
        $tests = self::SinglePropertyExists("tests","test_id",$test_id,"i");
        if($tests)
        {
            if($tests->num_rows>0)
            {
                foreach($tests as $test)
                {
                    return $test;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {

            return $tests;
        }
    }

    #Get specific teacher classrooms - returns classrooms on success | false if no records found | null if query couldn't execute
    public static function GetSpecificTeacherClassrooms($teacher_acc_id)
    {
        return self::SinglePropertyExists("classrooms","teacher_id",$teacher_acc_id,"i","Error preparing teacher classroom info query. <br>Technical information :++++++");
    }

    #Get specific teacher assignments - returns assignments on success | false if no records found | null if query couldn't execute
    public static function GetSpecificTeacherAssignments($teacher_acc_id)
    {
        return self::SinglePropertyExists("assignments","teacher_id",$teacher_acc_id,"i","Error preparing teacher classroom info query. <br>Technical information :---");
    }

    #Get specific teacher schedules - returns schedules on success | false if no records found | null if query couldn't execute
    public static function GetSpecificTeacherSchedules($teacher_acc_id)
    {
        return self::SinglePropertyExists("schedules","teacher_id",$teacher_acc_id,"i");
    }
    
    #Get specific teacher tests - returns tests on success | false if no records found | null if query couldn't execute
    public static function GetSpecificTeacherTests($teacher_acc_id)
    {
        return self::SinglePropertyExists("tests","teacher_id",$teacher_acc_id,"i");
    }

/*----------------------------------------------------------------------------------------------------------*/    
    #Get all subjects - returns subjects on success | false if no records found | null if query couldn't execute
    public static function GetAllSubjects()
    {
        return self::GetAllRecordsFromTable("subjects");        
    }

    #Get all streams - returns streams on success | false if no records found | null if query couldn't execute
    public static function GetAllStreams()
    {
        return self::GetAllRecordsFromTable("streams");        
    }

/*----------------------------------------------------------------------------------------------------------
                    PRINCIPAL ACCOUNT  - CLASSROOM | ASSIGNMENT | SCHEDULES | TESTS  FUNCTIONS
----------------------------------------------------------------------------------------------------------*/

    #Get all classrooms - returns classrooms on success | false if no records found | null if query couldn't execute
    public static function GetAllClassrooms()
    {
        return self::GetAllRecordsFromTable("classrooms");
    }

    #Get all assignments - returns assignments on success | false if no records found | null if query couldn't execute
     public static function GetAllAssignments()
    {
        return self::GetAllRecordsFromTable("assignments");
    }   
    
    #Get all schedules - returns schedules on success | false if no records found | null if query couldn't execute
    public static function GetAllSchedules()
    {
        return self::GetAllRecordsFromTable("schedules");
    }

    //Get all the students in a given classroom
    public static function GetAllStudentsInClass($class_id)
    {
        global $dbCon;
        $student_ids = array();

        $select_query = "SELECT student_ids FROM classrooms WHERE class_id=?";
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("i",$class_id);

            //If we could successfully  run the query
            if($select_stmt->execute())
            {
                $select_result = $select_stmt->get_result();
                if($select_result->num_rows > 0 ) #found some student ids
                {
                    foreach($select_result as $std_id_list)
                    {
                        $student_ids = self::GetArrayFromList($std_id_list["student_ids"]);
                        break;
                    }

                    //Extract individual student_ids
                    echo json_encode($student_ids);
                }
            }
        }
        else
        {
            ErrorHandler::PrintError("Error preparing query. <br>Technical Error :".$dbCon->error);
        }
    }

/*----------------------------------------------------------------------------------------------------------
                         NON-ACCOUNT STUDENT RELATED FUNCTIONS
----------------------------------------------------------------------------------------------------------*/
    #Get all classrooms the student with the student id of student_id is in
    public static function GetAllStudentClassrooms($student_id)
    {
        //WORKING
        if($classrooms = self::GetAllClassrooms())
        {
            $classrooms_found = array();
              foreach($classrooms as $classroom)
              {
                  $student_ids = $classroom["student_ids"];

                  //Try converting the student ids to array.
                  if($student_id_array = self::GetArrayFromList($student_ids))
                  {
                      #check for every student id found
                      foreach ($student_id_array as $student_id_found)
                      {
                          if($student_id == $student_id_found)
                          {
                              array_push($classrooms_found,$classroom);#add the classroom to the found classrooms
                          }
                      }
                  } 
              }

            #Return the classroom found if the array is not empty, otherwise return false
            if(count($classrooms_found)>0)
            {
                return $classrooms_found;
            }
            else
            {
                return false;
            }     
        }
        else
        {
            return self::GetAllClassrooms();
        }
        
    }

    #Get all assignments sent to the student with the student id of student_id
    public static function GetAllStudentAssignments($student_id)
    {
        //TODO Add implementation - look for a way of using convenience functions

        //Get the student classrooms and check for assignments that 
        if($classrooms = self::GetAllStudentClassrooms($student_id))
        {
            
            $assignments_found = array();#the assignments found
            foreach($classrooms as $classroom)
            {
                
                $cur_class_id = $classroom["class_id"];
            
                if($assignments = self::GetAllAssignments())
                {    
                    foreach($assignments as $assignment)#for every assignment 
                    {
                        // $assignment_submitted = self::GetAssSubmissionsByAssId($assignment["ass_id"]);

                        //Only retrieve sent assignments
                        if ($assignment["sent"] /*&& !$assignment_submitted*/)
                        {
                            $class_id_found = $assignment["class_id"];

                            #if the assignment classroom_id matched a classroom the student belongs to
                            if($class_id_found == $cur_class_id)
                            {
                                // $assignments_found["ass_class_id"] = $class_id_found;
                                array_push($assignments_found,$assignment);#add the assignment to assignments_found
                            }
                        }
                    }
                }
            }

            #Return the assignments found if the array is not empty, otherwise return false
            if(count($assignments_found)>0)
            {
                return $assignments_found;
            }
            else
            {
                return false;
            }    
            
        }#end of classroom check
        else#no classrooms found
        {
            return self::GetAllStudentClassrooms($student_id);
        }
    }

    #Get all student assignment submissions
    public static function GetAllStudentAssSubmissions($student_id)
    {
        return self::SinglePropertyExists("ass_submissions","student_id",$student_id,"i");#if the student id exists in the ass_submissions table
    }
    
    #Get assignment submission by ass_id
    public static function GetAssSubmissionsByAssId($ass_id)
    {
        return self::SinglePropertyExists("ass_submissions","ass_id",$ass_id,"i");#if the assignment id exists in the ass_submissions table
    }

    //Get graded and ungraded assignment submissions
    private static function GetAssSubReturnedStatus($submissions,$is_returned)
    {
        $found_ass_submissions = array();

        if($submissions)
        {
            //Foreach assignment submission, if it matches the criteria, add to the array
            foreach ($submissions as $ass_sub)
            {
                if($ass_sub["returned"]==$is_returned && !empty($ass_sub["grade"]))
                {
                    array_push($found_ass_submissions,$ass_sub);
                }
            }

            return $found_ass_submissions;
        }
        else
        {
            return $submissions;
        }
    }

    #Get graded/returned assignment submissions
    public static function GetGradedAssSubBasedOnAss($submissions)
    {
        return self::GetAssSubReturnedStatus($submissions,true);
    }

    #Get ungraded/unreturned assignment submissions
    public static function GetUnreturnedAssSubBasedOnAss($submissions)
    {
        return self::GetAssSubReturnedStatus($submissions,false);
    }

/*----------------------------------------------------------------------------------------------------------
                   COMMENTS - ASSIGNMENTS, ASSIGNMENT SUBMISSIONS & SCHEDULES
----------------------------------------------------------------------------------------------------------*/
    #Check if comment exists in a $table | return the comment if it does, false if it doesn't and null if prepare failed
    public static function CommentExists($table_name,$comment_id)
    {
        if($comments = self::SinglePropertyExists($table_name,"comment_id",$comment_id,"i"))
        {
            if($comments->num_rows > 0)
            {
                foreach($comments as $comment)
                {
                    return $comment;#return the first comment found
                }
            }
            else
            {
                return false;#if there's <=0 rows then we did not find any comments 
            }
        }
        else
        {
            return false;
        }
    }

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
        $comments = self::SinglePropertyExists("ass_comments","ass_id",$ass_id,"i");
        
        return self::GetCommentData($comments,"ass_comments");
    }

    #Get assignment submissions comments
    public static function GetAssSubmissionComments($submission_id)
    {
        $comments = self::SinglePropertyExists("ass_submission_comments","submission_id",$submission_id,"i");

        return self::GetCommentData($comments,"ass_submission_comments");
    }

    #Get schedule comments
    public static function GetScheduleComments($schedule_id)
    {
        $comments = self::SinglePropertyExists("schedule_comments","schedule_id",$schedule_id,"i");
//        var_dump($comments);
        return self::GetCommentData($comments,"schedule_comments");
    }
/*----------------------------------------------------------------------------------------------------------
                    TESTS AND ANSWERS
----------------------------------------------------------------------------------------------------------*/
    #Get all tests - returns tests on success | false if no records found | null if query couldn't execute
    public static function GetAllTests()
    {
        return self::GetAllRecordsFromTable("tests");        
    }

    #Get tests by subject ids
    public static function GetTestsBySubjectId($subject_id)
    {
        return self::SinglePropertyExists("tests","subject_id",$subject_id,"i");
    }

    #Get all questions in a Test
    public static function GetTestQuestions($test_id)
    {
        return self::SinglePropertyExists("test_questions","test_id",$test_id,"i");
    }
    
    //TODO Ensure that question_index is always unique to every question in any given test
    //If a question exists in a test
    public static function TestQuestionExists($test_id,$question_index)
    {
        global $dbCon;
        $select_query = "SELECT * FROM test_questions WHERE test_id=? AND question_index=?";

        #prepare the query - to prevent sql-injection
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("ii",$test_id,$question_index);

            #if the query successfully executes
            if($select_stmt->execute())
            {
                $result = $select_stmt->get_result();
                if($result->num_rows>0)
                {
                    foreach($result as $question_found)
                    {
                        return $question_found;
                    }
                }
                else
                {
                    return false;
                }
            }
            else#query failed to execute
            {
                return false;
            }
        }
        else#query failed to prepare
        {
            return null;
        }

    }

   //Return answers belonging to a question
    public static function GetQuestionAnswers($question_id)
    {
        return self::SinglePropertyExists("test_answers","question_id",$question_id,"i");
    }

    //Checks if an answer exists in the database based on the question_id and answer_index
    public static function QuestionAnswerExists($question_id,$answer_index)
    {
        global $dbCon;
        $select_query = "SELECT * FROM test_answers WHERE question_id=? AND answer_index=?";

        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("ii",$question_id,$answer_index);

            if($select_stmt->execute())
            {
                $result = $select_stmt->get_result();
                if($result->num_rows>0)
                {
                    foreach($result as $answer_found)
                    {
                        return $answer_found;
                    }
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return null;
        }

    }
    //Return submissions for a specific test and taker'
    public static function GetSpecificTestSubmissions($test_id,$user_info)
    {
        global $dbCon;

        $select_query = "SELECT * FROM test_submissions WHERE test_id=? AND taker_id=? AND taker_type=?";

        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("iis",$test_id,$user_info["user_id"],$user_info["account_type"]);

            if($select_stmt->execute())
            {
                $result = $select_stmt->get_result();
                if($result->num_rows>0)
                {
                    return $result;
                }
                else # no submissions found but query executed successfully
                {
                    echo "No specific test submissions found";
                    return false;
                }
            }
            else # failed to execute the query
            {
                echo "Failed to execute GetSpecificTestSubmissions query";
                return false;
            }
        }
        else #failed to prepare query
        {
            echo "Failed to prepare GetSpecificTestSubmissions query";
            return null;
        }
    }

    //Check if a test question submission exists in the database. return it if it exists | false if not and null if query couldn't prepare
    public static function TestQueSubmissionExists($taker_id,$taker_type,$test_id,$question_index)
    {
        global $dbCon;#db connection string

        $select_query = "SELECT * FROM test_submissions WHERE taker_id=? AND taker_type=? AND test_id=? AND question_index=?";

        if($select_stmt = $dbCon->prepare($select_query))
        {
            //Bind params
            $select_stmt->bind_param("isii",$taker_id,$taker_type,$test_id,$question_index);

            //Try executing the query
            if($select_stmt->execute())
            {
                $result = $select_stmt->get_result();
                if($result->num_rows>0)
                {
                    foreach($result as $test_que_submission)
                    {
                        return $test_que_submission;
                    }
                }
                else #no test question submissions found
                {
                    echo "<p>No test submissions matching those criteria found</p>";
                    return false;
                }
            }
            else #failed to execute query
            {
                echo "Database error : ".$dbCon->error;
                return false;
            }
        }
        else #failed to prepare query
        {
            return null;
        }
    }

    //Get skipped questions for a test
    public static function GetSkippedQuestions($test_id,$user_info)
    {
        global $dbCon;
        
        $select_query = "SELECT * FROM test_submissions WHERE test_id=? AND taker_id=? AND taker_type=? AND skipped=1";
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("iis",$test_id,$user_info["user_id"],$user_info["account_type"]);
            if($select_stmt->execute())
            {
                $results = $select_stmt->get_result();
                if($results->num_rows >0)
                {
                    return $results;
                }
                else
                {
                    return false;
                }
                
            }
            else #failed to execute query
            {
                return false;
            }
        }
        else #failed to prepare query
        {
            return null;
        }
    }

    //Get test results for a specific account
    public static function GetSpecificAccountResults($user_info)
    {
        global $dbCon;

        #Account type
        $select_query = "SELECT * FROM test_results WHERE taker_id=? AND taker_type=?";

        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("is",$user_info['user_id'],$user_info['account_type']);
            $select_status = $select_stmt->execute();

            //If successfully selected
            if($select_status)
            {
                $result = $select_stmt->get_result();
                return $result;
            }
            else
            {
                return $select_status;
            }
        }
        else #failed to prepare the query
        {
            return null;
        }
    }

    //Get test results for a specific test
    public static function GetSpecificTestResults($test_id)
    {
        return self::SinglePropertyExists("test_results","test_id",$test_id,"i");
    }

    //Get the test retake information for a given taker
    public static function GetTestRetake($test_id,$user_info)
    {
        global $dbCon;

        $select_query = "SELECT * FROM test_retakes WHERE test_id=? AND taker_id=? AND taker_type=?";

        //Prepare the query
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("iis",$test_id,$user_info["user_id"],$user_info["account_type"]);

            //Try to execute the query after binding the parameters
            if($select_stmt->execute())
            {
                $results = $select_stmt->get_result();

                //Check if any records were found
                if($results->num_rows>0)
                {
                    foreach($results as $result_found)
                    {
                        return $result_found; #return a single result
                    }
                }
                else
                {
                    // echo "<p>Test retake info query ran.<br>Could not find any test retake info based on the parameter values provided</p>";
                    return false;
                }
            }
            else
            {
                // echo "<p>Failed to execute query to retrieve test retake info</p>";
                return false;
            }
        }
        else # failed to prepare the query
        {
            echo "<p>Failed to prepare query to retrieve test retake information </p>";
            return null;
        }
    }
/*----------------------------------------------------------------------------------------------------------
                    EXTRA FUNCTIONALITY 
----------------------------------------------------------------------------------------------------------*/
    //Reverses a mysqli_result and returns an array with the values reversed
    public static function Paginate($listdata,$paginationtype, $numberperrows, $active, $type) {

        if(count($listdata) > $numberperrows) {

            $numberOfTbody = ceil(count($listdata) / $numberperrows);

            $tbodyNumber = 1;

            while (list($key, $val) = each($listdata)) {

                if($paginationtype == 'table') {

                    if (($key - 1) % $numberperrows == 0 || $key == 0) {

                        if(($key - 1) != 0) {

                            if ($tbodyNumber == $active ) {

                                echo '<tbody class="active" data-tbody-number="'.$tbodyNumber.'">';
                            } else {

                                echo '<tbody class="hide" data-tbody-number="'.$tbodyNumber.'">';
                            }
                        }
                    }

                    echo '<tr data-schedule-id="'.$val['schedule_id'].'">';
                    echo '<td class="js-schedule-title">'.$val['schedule_title'].'</td>';
                    echo '<td>'.$val['schedule_description'].'</td>';
                    echo '<td class="right-align" >'.$val['due_date'].'</td>';
                    echo '<td class="right-align schedule-action" width="120">';
                    echo '<a class="btn-icon"  id="openSchedule" href="javascript:void(0)"><i class="material-icons">expand_more</i></a>';
                    echo (($type == 'done') ? '<a class="btn-icon" id="unmarkdoneSchedule" href="javascript:void(0)"><i class="material-icons">undo</i></a>' : '<a class="btn-icon" id="attendedSchedule" href="#!"><i class="material-icons">done</i></a>');
                    echo '<a class="btn-icon js-get-comments" data-root-hook="schedule" href="javascript:void(0)"><i class="material-icons">comments</i></a>';
                    echo '</td>';
                    echo '</tr>';

                    if ($key % $numberperrows == 0 && $key != 0) {

                        $tbodyNumber++;

                        echo '</tbody>';
                    }
                }
            }
        } else {

            echo '<tbody class="active" data-tbody-number="1">';

            foreach($listdata as $list) {

                echo '<tr data-schedule-id="'.$list['schedule_id'].'">';
                echo '<td class="js-schedule-title">'.$list['schedule_title'].'</td>';
                echo '<td>'.$list['schedule_description'].'</td>';
                echo '<td class="right-align" >'.$list['due_date'].'</td>';
                echo '<td class="right-align schedule-action" width="120">';
                echo '<a class="btn-icon"  id="openSchedule" href="#!"><i class="material-icons">expand_more</i></a>';
                echo '<a class="btn-icon '.(($type == 'done') ? 'hide' : '').'" id="attendedSchedule" href="javascript:void(0)"><i class="material-icons">done</i></a>';
                echo (($type == 'done') ? '<a class="btn-icon" id="unmarkdoneSchedule" href="javascript:void(0)"><i class="material-icons">undo</i></a>' : '');
                echo '<a class="btn-icon js-get-comments" data-root-hook="schedule" href="javascript:void(0)"><i class="material-icons">comments</i></a>';
                echo '</td>';
                echo '</tr>';

            }

            echo '</tbody>';

        }
    }

    public static function PaginateControl($active, $position, $numberOfTbody, $tableid) {

        echo '<ul class="pagination '.$position.'" data-table-target="'.$tableid.'">';

        if ($numberOfTbody > 1) {
            //loop


            for($v= 1; $v <= $numberOfTbody; $v++ ) {

                if ($v == 1 && $active == 1) {
                    #start

                    echo '<li class="disabled"><a id="goToLeftPage" href="#!"><i class="material-icons">chevron_left</i></a></li>';

                } elseif ($v == 1 && $active != 1) {

                    echo '<li ><a id="goToLeftPage" href="#!"><i class="material-icons">chevron_left</i></a></li>';

                }

                if ($v == $active) {
                    #active-page

                    echo '<li class="active"><a href="#!">'.$v.'</a></li>';

                } else {

                    echo '<li class="waves-effect"><a href="#!">'.$v.'</a></li>';

                }

                if ($v == $numberOfTbody && $active == $numberOfTbody) {
                    #end

                    echo '<li class="disabled"><a id="goToRightPage" href="#!"><i class="material-icons">chevron_right</i></a></li>';

                } elseif ($v == $numberOfTbody && $active != $numberOfTbody) {

                    echo '<li ><a id="goToRightPage" href="#!"><i class="material-icons">chevron_right</i></a></li>';

                }

            }



        } elseif($numberOfTbody == 1 && $active == 1) {

            echo '<li class="disabled"><a id="goToLeftPage" href="#!"><i class="material-icons">chevron_left</i></a></li>';
            echo '<li class="active"><a href="#!">'.$numberOfTbody.'</a></li>';
            echo '<li class="disabled"><a id="goToRightPage" href="#!"><i class="material-icons">chevron_right</i></a></li>';
            echo '</ul>';

        } else {

            echo '<li class="waves-effect"><a href="#!">'.$numberOfTbody.'</a></li>';
            echo '</ul>';

        }
    }

    //Reverses the result of a mysqli_result and returns an array in reversed order
    public static function ReverseResult($mysqli_result)
    {
        $result_array = array();
        #foreach result item found
        if(isset($mysqli_result) && $mysqli_result)
        {   
            foreach($mysqli_result as $result)
            {
                array_push($result_array,$result);            
            }
        }
        else
        {
            return false;
        }        

        $array_length = count($result_array);

        $reversed_array = array();
        for($i=($array_length-1); $i>=0; $i--)
        {
            array_push($reversed_array,$result_array[$i]); 
        }

        return $reversed_array;
    }

    //Get array from a list of comma separated values
    public static function GetArrayFromList($list_var)
    {
        $the_array  = explode(",",$list_var);
        
        array_pop($the_array);#removes the last value in the array since it will always be blank

        return $the_array;
    }

    //Get a subject by its id, returns the first instance of the subject found on success, false on fail and null on query prepare error
    public static function GetSubjectById($subject_id)
    {
        $subjects =  self::SinglePropertyExists("subjects","subject_id",$subject_id,"i");

       if(!empty($subjects) && isset($subjects))
       {
            foreach($subjects as $subject)
            {
                return $subject;
            }
       } 
       else
       {
           return self::SinglePropertyExists("subjects","subject_id",$subject_id,"i");
       }
    }
    
    //Get a stream by its id, returns the first instance of the subject found on success, false on fail and null on query prepare error
    public static function GetStreamById($stream_id)
    {
        $streams =  self::SinglePropertyExists("streams","stream_id",$stream_id,"i");

       if(!empty($streams) && isset($streams))
       {
            foreach($streams as $stream)
            {
                return $stream;
            }
       } 
       else
       {
           return self::SinglePropertyExists("streams","stream_id",$stream_id,"i");
       }
    }

    //Get topics by subject id, returns all topics found on success, false on fail and null on query prepare error
    public static function GetTopicBySubjectId($subject_id)
    {
        $topics =  self::SinglePropertyExists("topics","subject_id",$subject_id,"i");

        return self::SinglePropertyExists("topics","subject_id",$subject_id,"i");

    }

    //Get sub-topic by topic id, returns all sub-topics found on success, false on fail and null on query prepare error
    public static function GetSubTopicByTopicId($topic_id)
    {
        $sub_topics =  self::SinglePropertyExists("sub_topics","topic_id",$topic_id,"i");

        return self::SinglePropertyExists("sub_topics","topic_id",$topic_id,"i");

    }

    //Get the number of assignments in a certain class - specific to a teacher
    public static function GetTeacherAssInClass($class_id,$teacher_acc_id)
    {   
        if($assignments = self::GetSpecificTeacherAssignments($teacher_acc_id))
        {
            $assignments_found=array();
            foreach($assignments as $assignment)#get individual assignments
            {
                $cur_class_id = $assignment["class_id"];

                if($class_id == $cur_class_id)#if the current class id is the class id we are looking for
                {
                    array_push($assignments_found,$assignment);
                }
            }
            
            return $assignments_found;#return the array containing the assignments found - keys same as mysqli_result
        }
        else
        {
            return self::GetSpecificTeacherAssignments($teacher_acc_id);
        }
    }

    //Get the number of assignments in a certain class - all assignments in the given classroom
    public static function GetAssignmentsInClass($class_id)
    {   
        if($assignments = self::GetAllAssignments())
        {
            $assignments_found=array();
            foreach($assignments as $assignment)#get individual assignments
            {
                $cur_class_id = $assignment["class_id"];

                if($class_id == $cur_class_id)#if the current class id is the class id we are looking for
                {
                    array_push($assignments_found,$assignment);
                }
            }
            
            return $assignments_found;#return the array containing the assignments found - keys same as mysqli_result
        }
        else
        {
            return self::GetSpecificTeacherAssignments($teacher_acc_id);
        }
    }

    #Get all the students that are not in a classroom 
    public static function GetAllStudentsNotInClass($class_id)
    {
        //Get classroom
        if($classroom = self::ClassroomExists($class_id))
        {
            $students_not_in_class = array();
            
            $student_ids_array = self::GetArrayFromList($classroom["student_ids"]);#list of the students that are in the list
            if($students = self::GetAllStudents())
            {
                // var_dump($student_ids_array);
                foreach($students as $student)#for every student
                {   
                    #If we don't find the admission number in the array for the classroom, then add the student'
                    if(array_search($student["adm_no"],$student_ids_array) === false)
                    {
                        array_push($students_not_in_class,$student);
                    }
                }
            }
            else
            {
                return false;
            }


            #if there are students in the array
            if(count($students_not_in_class)>0)
            {
                return $students_not_in_class;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
/*----------------------------------------------------------------------------------------------------------
                    RESOURCES FUNCTIONS
----------------------------------------------------------------------------------------------------------*/
     //Get all resources
     public static function GetAllResources()
     {
        $resources = self::GetAllRecordsFromTable("resources");
        return $resources;
     }
        
     //Get all resources that have a certain subject_id
     public static function GetResourcesBySubject($subject_id)
     {
         $resources= self::SinglePropertyExists("resources","subject_id",$subject_id,"i");
         return $resources;
     }

     //Get resource by id
     public static function ResourceExists($resource_id)
     {
          $resources= self::SinglePropertyExists("resources","resource_id",$resource_id,"i");
          if($resources && ($resources->num_rows>0))
          {
              foreach($resources as $resource_found)
              {
                 return $resource_found;
              }
          }
          else #Resource does not exist, or could not be retrieved
          {
              return $resources;
          }
     }

     /*TIMEFRAME RELATED FUNCTIONS ~ MAINLY USED FOR STATISTICS*/
     //CONVENIENCE : Get records within a certain timeframe for specific table ~ REFACTORED []
     private static function GetRecordsInTimeframe($table_name,$date_column,$start_date,$end_date)
     {
        global $dbCon;

        $select_query = "SELECT * FROM $table_name WHERE $table_name.$date_column>=? AND $table_name.$date_column<=?";

        //Attempt to prepare query
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("ss",$start_date,$end_date);

            $select_status = $select_stmt->execute();
            $results = @$select_stmt->get_result();

            if(@$select_status && ($results->num_rows>0))
            {
                return $results;
            }
            else
            {
                return $select_status;
            }
        }
        else
        {
            return null;
        }
     }

     //Get schedules within a certain timeframe
     #TODO: Consider refactoring timeframe functions further into a parent function that contains all the core  [DONE]
     protected static function GetSchedulesInTimeframe($start_date,$end_date)
     {
        return self::GetRecordsInTimeframe("schedules","schedule_date",$start_date,$end_date);
     }
    
    #Get the current day's schedules
    public static function GetTodaySchedules()
    {
        $today = EsomoDate::GetCurrentDate();
        $schedules = self::GetSchedulesInTimeframe($today,$today);

        return $schedules;
    }
    
    #Get yesterday's schedules
    public static function GetYesterdaySchedules()
    {
        $today = EsomoDate::GetCurrentDate();
        $yesterday = EsomoDate::GetYesterday();

        $schedules = self::GetSchedulesInTimeframe($yesterday,$today);

        return $schedules;
    }

    #Get schedules for the past 7 days
    public static function Get7DaySchedules()
    {
        $today = EsomoDate::GetCurrentDate();
        $seven_days_ago = EsomoDate::Get7DaysAgo();

        $schedules = self::GetSchedulesInTimeframe($seven_days_ago,$today);

        return $schedules;
    }

    #Get schedules for this month
    public static function GetThisMonthSchedules()
    {
        $this_month = EsomoDate::GetThisMonth();
        $schedules = self::GetSchedulesInTimeframe($this_month['start'],$this_month['end']);

        return $schedules;
    }
    
    #Get last month schedules
    public static function GetLastMonthSchedules()
    {
        $last_month = EsomoDate::GetLastMonth();
        $schedules = self::GetSchedulesInTimeframe($last_month['start'],$last_month['end']);

        return $schedules;
    }

    #Get schedules for the past 30 days
    public static function GetLast30DaysSchedules()
    {
        $today = EsomoDate::GetCurrentDate();
        $thirty_days_ago = EsomoDate::Get30DaysAgo();

        $schedules = self::GetSchedulesInTimeframe($thirty_days_ago,$today);

        return $schedules;
    }

    //Get the schedule attendance
    private static function GetScheduleAttendance($schedules,$is_attended)
    {
        $found_schedules = array();
        //If the schedules were found
        if($schedules && $schedules->num_rows>0)
        {
            foreach($schedules as $schedule)
            {
                if($schedule["attended_schedule"] == $is_attended)
                {
                    array_push($found_schedules,$schedule);
                }
            }

            return $found_schedules;
        }
        else
        {
            return $schedules;
        }
    }

    #Get done schedules
    public static function GetDoneSchedules($schedules)
    {
        return self::GetScheduleAttendance($schedules,true);
    }
    
    #Get unattended schedules
    public static function GetUnattendedSchedules($schedules)
    {
        return self::GetScheduleAttendance($schedules,false);
    }

    //Get assignments within a certain timeframe
    #TODO: Consider refactoring timeframe functions further into a parent function that contains all the core logic [DONE]
    protected static function GetAssignmentsInTimeframe($start_date,$end_date)
    {
        return self::GetRecordsInTimeframe("assignments","date_sent",$start_date,$end_date);
    }

    #Get the current day's assignments
    public static function GetTodayAssignments()
    {
        $today = EsomoDate::GetCurrentDate();
        $assignments = self::GetAssignmentsInTimeframe($today,$today);

        return $assignments;
    }
    
    #Get yesterday's assignments
    public static function GetYesterdayAssignments()
    {
        $today = EsomoDate::GetCurrentDate();
        $yesterday = EsomoDate::GetYesterday();

        $assignments = self::GetAssignmentsInTimeframe($yesterday,$today);

        return $assignments;
    }

    #Get assignments for the past 7 days
    public static function Get7DayAssignments()
    {
        $today = EsomoDate::GetCurrentDate();
        $seven_days_ago = EsomoDate::Get7DaysAgo();

        $assignments = self::GetAssignmentsInTimeframe($seven_days_ago,$today);

        return $assignments;
    }

    #Get assignments for this month
    public static function GetThisMonthAssignments()
    {
        $this_month = EsomoDate::GetThisMonth();
        $assignments = self::GetAssignmentsInTimeframe($this_month['start'],$this_month['end']);

        return $assignments;
    }
    
    #Get last month assignments
    public static function GetLastMonthAssignments()
    {
        $last_month = EsomoDate::GetLastMonth();
        $assignments = self::GetAssignmentsInTimeframe($last_month['start'],$last_month['end']);

        return $assignments;
    }

    #Get assignments for the past 30 days
    public static function GetLast30DaysAssignments()
    {
        $today = EsomoDate::GetCurrentDate();
        $thirty_days_ago = EsomoDate::Get30DaysAgo();

        $assignments = self::GetAssignmentsInTimeframe($thirty_days_ago,$today);

        return $assignments;
    }

    #Get assignment submissions given assignments
    public static function GetMultipleAssSubmissions($assignments)
    {
        $found_ass_submissions = array();
        if($assignments && @$assignments->num_rows>0)
        {
            //For each assignment
            foreach($assignments as $ass)
            {
                //Get assignment submissions for the current assignment
                $ass_submissions = self::GetAssSubmissionsByAssId($ass["ass_id"]);
                if($ass_submissions && @$ass_submissions->num_rows>0)
                {
                    foreach($ass_submissions as $ass_sub)
                    {
                        array_push($found_ass_submissions,$ass_sub);
                    }
                }
            }

            return $found_ass_submissions;
        }
        else
        {
            return $assignments;
        }
    }

    #Get assignment submissions based on assignments
    private static function GetAssSubsBasedOnAss($assignments,$is_returned)
    {
        $ass_submissions = self::GetMultipleAssSubmissions($assignments);
        $graded_submissions = array();
        //If assignment submissions were found
        if($ass_submissions && count($ass_submissions)>0)
        {
            foreach($ass_submissions as $ass_sub)
            {
                //if the submission is a returned submissiona and the grade has been set
                if($ass_sub["returned"] == $is_returned && !empty($ass_sub["grade"]))
                {
                    array_push($graded_submissions,$ass_sub);
                }
            }

            return $graded_submissions;
        }
        else
        {
            return $ass_submissions;
        }
    }

    #Get graded assignment submissions
    public static function GetGradedAssSubmissions($assignments)
    {
        return self::GetAssSubsBasedOnAss($assignments,true);
    }

    #Get ungraded/unretured assignment submissions
    public static function GetUngradedAssSubmissions($assignments)
    {
        return self::GetAssSubsBasedOnAss($assignments,false);
    }

    //Get number text ~ rounds off number after 1000 to show shorter version eg. 1K instead of 1000
    public static function GetNumberText($number)
    {
        $num_text = "";
        if($number>=1000)
        {
            $num_text = round(($number/1000),2)."K";
        }
        else
        {
            $num_text = $number;
        }

        return $num_text;
    }
};#END OF CLASS

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_GET['action'])) {
    $user_info = MySessionHandler::GetLoggedUserInfo();#store the logged in user info anytime an AJAX call is made
    sleep(1);//Sleep for  ashort amount of time, to reduce odds of a DDOS working.
    
    switch($_GET['action']) {
        case 'StudentIdExists':
            
            $std_id = $_GET['adm_no'];
        
            $result = DbInfo::StudentIdExists($std_id);
            
            echo json_encode($result);
            
            break;
        case 'GetSpecificTeacherClassrooms':
            
            $teacher_acc_id = $_SESSION['admin_acc_id'];
            
            $result = DbInfo::GetSpecificTeacherClassrooms($teacher_acc_id);
            
            $num = 0;
            
            
            
            foreach ($result as $row) {

                $subject_id = $row['subject_id'];
                $stream_id = $row['stream_id'];
                
                $subjectname = DBInfo::GetSubjectById($subject_id);
                $streamname = DBInfo::GetStreamById($stream_id);
                
                $newResult = array(
                    "id" => $row['class_id'],
                    "subject" => $subjectname['subject_name'],
                    "stream" => $streamname['stream_name'],
                    "selectedStudents" => $row['student_ids'],
                    "name" => $row['class_name']
                );
                
                $arrayResult[$num] = $newResult;
                
                $num += 1;
                
                //echo $num;
                
            }
            
            echo json_encode($arrayResult);
            
            break;
        case 'GetAllStudentsInClass':
            
            $class_id = $_GET['class_id'];
        
            $result = DbInfo::GetAllStudentsInClass($class_id);
            
            echo $result;
            
            break;
        case 'GetTeacherAssInClass':
            
            $class_id = $_GET['class_id'];
            $teacher_acc_id = $_SESSION['admin_acc_id'];
            
            $result = DbInfo::GetTeacherAssInClass($class_id,$teacher_acc_id);
            
            echo $result;
            
            break;
        case 'GetAssignmentsInClass':
            
            $class_id = $_GET['class_id'];
            
            $result = $DBInfo::GetAssignmentsInClass($class_id);
            
            echo $result;
            
            break;
        case 'getAllTeachers':
            
            $result = DbInfo::getAllTeachers();
            
            return $result;
            
            break;
        case 'ClassroomExists':
            
            $result = DbInfo::ClassroomExists($_GET['class_id']);
            
            $num = 0;
            
            //var_dump($result);
            
            if($result != null) {
              
                echo json_encode($result);

            } else {
                
                $result = 'null';
            
                echo json_encode($result);
                //echo 'null';
                
            }
            
            break;
        case 'GetAllStudents':
            $result = DbInfo::GetAllStudents();
            
            $num = 0;
            
            foreach ($result as $row) {
                
                $newResult = array(
                    "id" => $row['adm_no'],
                    "name" => $row['full_name']
                );
                
                $arrayResult[$num] = $newResult;
                
                $num += 1;
                
                //echo $num;
                
            }
            
            echo json_encode($arrayResult);
            
            break;
        case 'GetAllStudentsNotInClass':
            $class_id = $_GET['class_id'];
            
            //echo $class_id;
            
            $result="";
            if($students_found = DbInfo::GetAllStudentsNotInClass($class_id))
            {
                
                $num = 0;

                foreach ($students_found as $student) {
                    $newResult = array(
                        "id" => $student['adm_no'],
                        "name" => $student['full_name']
                    );
                    
                    $arrayResult[$num] = $newResult;
                    
                    $num += 1;
                }
                echo json_encode($arrayResult);

                //Cleanup
            }
            else
            {
                
                $result = DbInfo::GetAllStudents();

                $num = 0;

                foreach ($result as $row) {

                    $newResult = array(
                        "value" => $row['adm_no'],
                        "name" => $row['full_name']
                    );

                    $arrayResult[$num] = $newResult;

                    $num += 1;

                    //echo $num;

                }

                echo json_encode($arrayResult);

                
            }

            
        break;
        case 'GetAllStreams':
            
            $result = DbInfo::GetAllStreams();
            
            $num = 0;
            
            foreach ($result as $row) {
                
                $newResult = array(
                    "value" => $row['stream_id'],
                    "name" => $row['stream_name']
                );
                
                $arrayResult[$num] = $newResult;
                
                $num += 1;
                
                //echo $num;
                
            }
            
            echo json_encode($arrayResult);
            
            break;
        case 'GetAllSubjects':
            
            $result = DbInfo::GetAllSubjects();
            
            $num = 0;
            
            foreach ($result as $row) {
                
                $newResult = array(
                    "value" => $row['subject_id'],
                    "category" => $row['subject_category'],
                    "name" => $row['subject_name']
                );
                
                $arrayResult[$num] = $newResult;
                
                $num += 1;
                
                //echo $num;
                
            }

            echo json_encode($arrayResult);
            
            break;
        case 'GetSubjectById':
            
            $result = DbInfo::GetSubjectById($_GET['subject_id']);
            
            $num = 0;
            
            $newResult = array(
                "id" => $result['subject_id'],
                "category" => $result['subject_category'],
                "name" => $result['subject_name']
            );
            
            echo json_encode($newResult);
            
            break;
        case 'GetStreamById':
            
            $result = DbInfo::GetStreamById($_GET['stream_id']);
            
            $num = 0;
            
            $newResult = array(
                "id" => $result['stream_id'],
                "name" => $result['stream_name']
            );
            
            echo json_encode($newResult);
            
            break;
        case 'GetTopicBySubjectId':
            
            $result = DbInfo::GetTopicBySubjectId($_GET['subject_id']);
            
            echo $subject_id;
            var_dump($result);

            $newResult = array(
                "value" => $row['topic_id'],
                "category" => $subject_id,
                "name" => $row['topic_name']
            );

            echo json_encode($newResult);
            
            break;
        case 'ScheduleExistsByGuid':

            $result = DbInfo::ScheduleExistsByGuid($_GET['guid_id']);

            $num = 0;

            //var_dump($result);

            if($result != null) {

                foreach ($result as $row) {


                    echo json_encode($row);

                    //echo $num;

                }

            } else {

                $result = 'null';

                echo json_encode($result);
                //echo 'null';

            }

            break;
        case 'ScheduleExists':

            $result = DbInfo::ScheduleExists($_GET['schedule_id']);

            $num = 0;

            //var_dump($result);

            if($result != null) {

                echo json_encode($result);

            } else {

                $result = 'null';

                echo json_encode($result);
                //echo 'null';

            }

            break;

        case "GetTestById":
            $test_id = &$_GET["test_id"];
            $test_found = DbInfo::TestExists($test_id);
            
            echo json_encode($test_found);
        break;
        
        case "GetSkippedQuestions":
            $test_id = &$_GET["test_id"];
            $skipped_questions = DbInfo::GetSkippedQuestions($test_id,$user_info);

            #if we could retrieve the skipped questions
            if($skipped_questions)
            {
                $skipped_array = array();
                $array_result = $skipped_questions->fetch_all(MYSQLI_ASSOC);
                $json = json_encode($array_result);
                echo $json;
            }
            else
            {
                echo "0";
            }
            
        break;

        /*Ids exist*/
        case 'SuperuserStaffIdExists':
            $staff_id = htmlspecialchars($_GET["staff_id"]);
            $id_exists = DbInfo::SuperuserStaffIdExists($id);

            if($id_exists)
            {
                echo true;
            }
            else
            {
                echo "staff id does not exist";
            }
        break;
        case 'PrincipalStaffIdExists':
            $staff_id = htmlspecialchars($_GET["staff_id"]);
            $id_exists = DbInfo::PrincipalStaffIdExists($id);

            if($id_exists)
            {
                echo true;
            }
            else
            {
                echo "staff id does not exist";
            }
        break;
        case 'TeacherStaffIdExists':
            $staff_id = htmlspecialchars($_GET["staff_id"]);
            $id_exists = DbInfo::TeacherStaffIdExists($id);

            if($id_exists)
            {
                echo true;
            }
            else
            {
                echo "staff id does not exist";
            }
        break;
        case 'StudentIdValidation':
            $student_id = htmlspecialchars($_GET["student_id"]);
            $id_exists = DbInfo::StudentIdExists($student_id);

            if($id_exists)
            {
                echo true;
            }
            else
            {
                echo "student id does not exist";
            }
        break;

        /*Usernames exist*/
        case 'SuperuserUsernameExists':
            $username = htmlspecialchars($_GET["username"]);
            $username_exists  = DbInfo::SuperuserUsernameExists($username);

            if($username_exists)
            {
                echo true;
            }
            else
            {
                echo "username does not exist";
            }
        break;

        case 'PrincipalUsernameExists':
            $username = htmlspecialchars($_GET["username"]);
            $username_exists  = DbInfo::PrincipalUsernameExists($username);

            if($username_exists)
            {
                echo true;
            }
            else
            {
                echo "username does not exist";
            }
        break;

        case 'TeacherUsernameExists':
            $username = htmlspecialchars($_GET["username"]);
            $username_exists  = DbInfo::TeacherUsernameExists($username);

            if($username_exists)
            {
                echo true;
            }
            else
            {
                echo "username does not exist";
            }
        break;

        case 'StudentUsernameExists':
            $username = htmlspecialchars($_GET["username"]);
            $username_exists  = DbInfo::StudentUsernameExists($username);

            if($username_exists)
            {
                echo true;
            }
            else
            {
                echo "username does not exist";
            }

        break;

        case 'GetScheduleComments':
            $schedule_id = intval($_GET["id"]);

            $comments = DbInfo::GetScheduleComments($schedule_id);

            echo json_encode($comments);

        break;

        case 'GetAssComments':
            $ass_id = intval($_GET["id"]);

            $comments = DbInfo::GetAssComments($ass_id);

            echo json_encode($comments);

        break;

        case 'GetAssSubmissionComments':
            $submission_id = intval($_GET["id"]);

            $comments = DbInfo::GetAssSubmissionComments($submission_id);

            echo json_encode($comments);

        break;

        //Principal Section ajax requests
        case "UpdateScheduleOverview":#Update schedule overview
            //---------------------------------------------------------------------
            $timeframe = $_GET["timeframe"];

            //If the timeframe provided is valid
            if(isset($timeframe) && !empty($timeframe))
            {
                $schedules = false;

                //TODO: [REFACTOR THIS CODE INTO A FUNCTION since it is reused]
                switch($timeframe)
                {
                    case ALL_TIME:
                        $schedules = DbInfo::GetAllSchedules();
                    break;
                    case TODAY:
                        $schedules = DbInfo::GetTodaySchedules();
                    break;
                    case YESTERDAY:
                        $schedules = DbInfo::GetYesterdaySchedules();
                    break;
                    case LAST_7_DAYS:
                        $schedules = DbInfo::Get7DaySchedules();
                    break;
                    case THIS_MONTH:
                        $schedules = DbInfo::GetThisMonthSchedules();
                    break;
                    case LAST_MONTH:
                        $schedules = DbInfo::GetLastMonthSchedules();
                    break;
                    case LAST_30_DAYS:
                        $schedules = DbInfo::GetLast30DaysSchedules();
                    break;
                    default:
                        echo "Invalid timeframe provided";
                    break;
                }
                //---------------------------------------------------------------------
                
                $total_schedule_count = $done_schedule_count = $unattended_schedule_count = 0;
                if($schedules && @$schedules->num_rows>0)
                {
                    $total_schedule_count = $schedules->num_rows;
                    
                    //Returns an array that contains associative arrays corresponding to db records
                    $done_schedules = DbInfo::GetDoneSchedules($schedules);
                    $unattended_schedules = DbInfo::GetUnattendedSchedules($schedules);

                    $done_schedule_count = count($done_schedules);
                    $unattended_schedule_count = count($unattended_schedules);
                }
                $data = array("total_schedule_count"=>$total_schedule_count,"done_schedules"=>$done_schedule_count,"unattended_schedule_count"=>$unattended_schedule_count);

                echo json_encode($data);

            }
            else
            {
                echo "Failed to retrieve timeframe";
            }
        break;

        case "UpdateAssignmentOverview": 
            $timeframe = $_GET["timeframe"];

            //If the timeframe provided is valid
            if(isset($timeframe) && !empty($timeframe))
            {
                $assignments = false;
                
                //TODO: [REFACTOR THIS CODE INTO A FUNCTION since it is reused]
                switch($timeframe)
                {
                    case ALL_TIME:
                        $assignments = DbInfo::GetAllAssignments();
                    break;
                    case TODAY:
                        $assignments = DbInfo::GetTodayAssignments();
                    break;
                    case YESTERDAY:
                        $assignments = DbInfo::GetYesterdayAssignments();
                    break;
                    case LAST_7_DAYS:
                        $assignments = DbInfo::Get7DayAssignments();
                    break;
                    case THIS_MONTH:
                        $assignments = DbInfo::GetThisMonthAssignments();
                    break;
                    case LAST_MONTH:
                        $assignments = DbInfo::GetLastMonthAssignments();
                    break;
                    case LAST_30_DAYS:
                        $assignments = DbInfo::GetLast30DaysAssignments();
                    break;
                    default:
                        echo "Invalid timeframe provided";
                    break;
                }
                //---------------------------------------------------------------------

                $total_ass_sent = $total_ass_subs = $total_graded_ass_subs = $total_unreturned_subs = 0;

                if($assignments && @$assignments->num_rows>0)
                {
                    $total_ass_sent = $assignments->num_rows;

                    $ass_subs = DbInfo::GetMultipleAssSubmissions($assignments);
                    $graded_subs = DbInfo::GetGradedAssSubmissions($assignments);
                    
                    $total_ass_subs = count($ass_subs);
                    $total_graded_ass_subs = count($graded_subs);
                    $total_unreturned_subs = DbInfo::GetUngradedAssSubmissions($assignments);
                }
                $data = array("total_ass_sent"=>$total_ass_sent,"total_ass_subs"=>$total_ass_subs,"total_graded_ass_subs"=>$total_graded_ass_subs,"total_unreturned_subs"=>$total_unreturned_subs);

                echo json_encode($data);
            }
            else
            {
                echo "Failed to retrieve timeframe";
            }

        break;

        case "UpdateScheduleTabStats": 
            $timeframe = $_GET["timeframe"];

            //If the timeframe provided is valid
            if(isset($timeframe) && !empty($timeframe))
            {
                $schedules = false;

                //TODO: [REFACTOR THIS CODE INTO A FUNCTION since it is reused]
                switch($timeframe)
                {
                    case ALL_TIME:
                        $schedules = DbInfo::GetAllSchedules();
                    break;
                    case TODAY:
                        $schedules = DbInfo::GetTodaySchedules();
                    break;
                    case YESTERDAY:
                        $schedules = DbInfo::GetYesterdaySchedules();
                    break;
                    case LAST_7_DAYS:
                        $schedules = DbInfo::Get7DaySchedules();
                    break;
                    case THIS_MONTH:
                        $schedules = DbInfo::GetThisMonthSchedules();
                    break;
                    case LAST_MONTH:
                        $schedules = DbInfo::GetLastMonthSchedules();
                    break;
                    case LAST_30_DAYS:
                        $schedules = DbInfo::GetLast30DaysSchedules();
                    break;
                    default:
                        echo "Invalid timeframe provided";
                    break;
                }
                //---------------------------------------------------------------------

                /*Expected data indices
                    schedule_title,
                    schedule_teacher,
                    schedule_classroom,
                    schedule_date (formatted),
                    schedule_due_date (formatted).
                    schedule_status
                    schedule_id
                    comment_count
                */

                #If schedules were found
                if($schedules && @$schedules->num_rows>0)
                {
                    $data = array();#Will store all the schedules' data

                    $schedule_info = array("schedule_title"=>"","schedule_teacher"=>"","schedule_classroom"=>"","schedule_date"=>"","schedule_due_date"=>"","schedule_status"=>"","schedule_id"=>"","comment_count"=>"");

                    //Foreach schedule ~ set the schedule info
                    foreach($schedules as $schedule)
                    {
                        $schedule_id = $schedule["schedule_id"];

                        //Set foreign key based variables
                        #Schedule teacher
                        $schedule_teacher = "Unknown teacher";

                        if($teacher_found = DbInfo::GetTeacherById($schedule["teacher_id"]))
                        {
                            $schedule_teacher = $teacher_found["first_name"] . " " . $teacher_found["last_name"];
                        }

                        #Schedule Classroom
                        $schedule_classroom = "Unknown classroom";

                        if($classroom_found = DbInfo::ClassroomExists($schedule["class_id"]))
                        {
                            $schedule_classroom = $classroom_found["class_name"];
                        }
                        
                        #If the schedule has been attended
                        $schedule_status = null;
                        if($schedule["attended_schedule"])
                        {   
                            $schedule_status = "Done";
                        }
                        else
                        {
                            $schedule_status = "Unattended";
                        }

                        $schedule_comment_count = 0;
                        #Schedule comments
                        if($schedule_comments = DbInfo::GetScheduleComments($schedule_id) && @$schedule_comments->num_rows>0)
                        {
                            $schedule_comment_count = $schedule_comments->num_rows;
                        }
                        
                        //TODO : Get this data
                        $schedule_info["schedule_title"] = $schedule["schedule_title"];
                        $schedule_info["schedule_teacher"] = $schedule_teacher;
                        $schedule_info["schedule_classroom"] = $schedule_classroom;
                        $schedule_info["schedule_date"] = EsomoDate::GetOptimalDateText($schedule["schedule_date"]);
                        $schedule_info["schedule_due_date"] = EsomoDate::GetOptimalDateText($schedule["due_date"]);
                        $schedule_info["schedule_status"] = $schedule_status;
                        $schedule_info["schedule_id"] = $schedule["schedule_id"];
                        $schedule_info["comment_count"] = $schedule_comment_count;

                        #Add the schedule info to the data array
                        array_push($data,$schedule_info);
                    }

                    echo json_encode($data);#Echo the data as json data
                }
                else
                {
                    echo "\nNo schedules found in this timeframe";
                }

            }
            else
            {
                echo "Failed to retrieve timeframe";
            }

        break;

        case "UpdateAssignmentTabStats": 
            $timeframe = $_GET["timeframe"];

            //If the timeframe provided is valid
            if(isset($timeframe) && !empty($timeframe))
            {
                $assignments = false;

                //TODO: [REFACTOR THIS CODE INTO A FUNCTION since it is reused]
                switch($timeframe)
                {
                    case ALL_TIME:
                        $assignments = DbInfo::GetAllAssignments();
                    break;
                    case TODAY:
                        $assignments = DbInfo::GetTodayAssignments();
                    break;
                    case YESTERDAY:
                        $assignments = DbInfo::GetYesterdayAssignments();
                    break;
                    case LAST_7_DAYS:
                        $assignments = DbInfo::Get7DayAssignments();
                    break;
                    case THIS_MONTH:
                        $assignments = DbInfo::GetThisMonthAssignments();
                    break;
                    case LAST_MONTH:
                        $assignments = DbInfo::GetLastMonthAssignments();
                    break;
                    case LAST_30_DAYS:
                        $assignments = DbInfo::GetLast30DaysAssignments();
                    break;
                    default:
                        echo "Invalid timeframe provided";
                    break;
                }
                //---------------------------------------------------------------------

                /*Expected data indices
                    ass_title,
                    ass_teacher,
                    ass_classroom,
                    ass_date_sent,
                    ass_date_due,
                    ass_submission_count,
                    returned_submission_count,
                    unreturned_submission_count,
                    ass_id
                */
                #If assignments were found
                if($assignments && @$assignments->num_rows>0)
                {
                    $data = array();#Will store all the schedules' data

                    $ass_info = array("ass_title"=>"","ass_teacher"=>"","ass_classroom"=>"","ass_date_sent"=>"","ass_date_due"=>"","ass_submission_count"=>"","unreturned_submission_count"=>"","unreturned_submission_count"=>"","ass_id"=>"");

                    //Foreach assignment ~ set the assignment info
                    foreach($assignments as $ass)
                    {
                        $ass_id = $ass["ass_id"];
                        $teacher_id = $ass["teacher_id"];

                        //Set foreign key based variables
                        #Assignment teacher
                        $ass_teacher = "Unknown teacher";

                        if($teacher_found = DbInfo::GetTeacherById($teacher_id))
                        {
                            $ass_teacher = $teacher_found["first_name"] . " " . $teacher_found["last_name"];
                        }

                        #Assignment Classroom
                        $ass_classroom = "Unknown classroom";

                        if($classroom_found = DbInfo::ClassroomExists($ass["class_id"]))
                        {
                            $ass_classroom = $classroom_found["class_name"];
                        }

                        #Returned and unreturned assignment submission count
                        $returned_ass_submission_count = $unreturned_ass_submission_count = $ass_submission_count = 0;
                        
                        #If the assignment submissions were found
                        if($ass_submissions = DbInfo::GetAssSubmissionsByAssId($ass_id) && @$ass_submissions->num_rows>0)
                        {
                            $ass_submission_count = $ass_submissions->num_rows;
                        }
                        
                        #If the returned assignment submissions were found
                        if($returned_ass_submissions = DbInfo::GetGradedAssSubBasedOnAss($ass_submissions) && count($returned_ass_submissions)>0)
                        {
                            $returned_ass_submission_count = $returned_ass_submissions->num_rows;
                        }

                        #If the unreturned assignment submissions were found
                        if($unreturned_ass_submissions = DbInfo::GetUnreturnedAssSubBasedOnAss($ass_submissions) && count($unreturned_ass_submissions)>0)
                        {
                            $unreturned_ass_submission_count = $unreturned_ass_submissions->num_rows;
                        }


                        //TODO : Get this data
                        $ass_info["ass_title"] = $ass["ass_title"];
                        $ass_info["ass_teacher"] = $ass_teacher;
                        $ass_info["ass_classroom"] = $ass_classroom;
                        $ass_info["ass_date_sent"] = EsomoDate::GetOptimalDateText($ass["date_sent"]);
                        $ass_info["ass_date_due"] = EsomoDate::GetOptimalDateText($ass["due_date"]);
                        $ass_info["ass_submission_count"] = $ass_submission_count;
                        $ass_info["returned_submission_count"] = $returned_ass_submission_count;
                        $ass_info["unreturned_submission_count"] = $unreturned_ass_submission_count;
                        $ass_info["ass_id"] = $ass_id;

                        #Add the assignment info to the data array
                        array_push($data,$ass_info);
                    }
                    echo json_encode($data);#Echo the data as json data
                }
                else
                {
                    echo "\nNo assignments found for this timeframe";
                }
            }
            else
            {
                echo "Failed to retrieve timeframe";
            }
        break;

        default:

            //return null;
            break;
    }

} else {
    return null;
}
