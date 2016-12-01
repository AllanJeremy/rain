<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Connection to the database
include_once(realpath(dirname(__FILE__) . "/../handlers/error_handler.php")); #Printing error messages

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
        $select_query = "SELECT * FROM admin_accounts WHERE acc_id=? AND account_type=?";

        $prepare_error = "Couldn't prepare query to retrieve admin account information by id. <br><br> Technical information : ";

        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("is",$acc_id,$acc_type);
            $select_stmt->execute();

            $select_result = $select_stmt->get_result();

            #if records could be found
            if($select_result->num_rows == 0)
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
            return self::GetAdminById($acc_id,$acc_type="teacher");
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
        $select_query = "SELECT * FROM student_accounts WHERE acc_id=?";

        $prepare_error = "Couldn't prepare query to retrieve student account information by id. <br><br> Technical information : ";

        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("i",$acc_id);
            $select_stmt->execute();

            $select_result = $select_stmt->get_result();

            #if records could be found
            if($select_result->num_rows == 0)
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
           return self::SinglePropertyExists("student_accounts","adm_no",$std_id,"i");;
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
        return self::SinglePropertyExists("classrooms","class_id",$class_id,"i");
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

    //Checks if the schedule with the given id exists, returns true on success | false if no records found | null if query couldn't execute
    public static function ScheduleExists($schedule_id)
    {
        return self::SinglePropertyExists("schedules","schedule_id",$schedule_id,"i");
    }
    
    //Checks if the test with the given id exists, returns true on success | false if no records found | null if query couldn't execute
    public static function TestExists($test_id)
    {
        return self::SinglePropertyExists("tests","test_id",$test_id,"i");
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
    
    #Get all tests - returns tests on success | false if no records found | null if query couldn't execute
    public static function GetAllTests()
    {
        return self::GetAllRecordsFromTable("tests");        
    }

    //Get all the students in a given classroom
    public static function GetAllStudentsInClass($class_id)
    {
        global $dbCon;
        $student_ids = null;

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
                        $student_ids = $std_id_list["student_ids"];
                        break;
                    }

                    //Extract individual student_ids
                    echo "Students list : ".$student_ids;

                    //self::StudentIdExists();
                }
            }
        }
        else
        {
            ErrorHandler::PrintError("Error preparing query. <br>Technical Error :".$dbCon->error);
        }
    }
/*----------------------------------------------------------------------------------------------------------
                    EXTRA FUNCTIONALITY 
----------------------------------------------------------------------------------------------------------*/
    //Reverses a mysqli_result and returns an array with the values reversed
    public static function ReverseResult($mysqli_result)
    {
        $result_array = array();
        #foreach result item found
        foreach($mysqli_result as $result)
        {
            array_push($result_array,$result);            
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

    //Get the number of assignments in a certain class - specific to a teacher
    public static function GetTeacherAssInClass($class_id,$teacher_acc_id)
    {   
        if($assignments = self::GetSpecificTeacherAssignments($teacher_acc_id))
        {
            $assignments_found=array();
            foreach($assignments as $assignment)#get individual assignments
            {
                $class_ids = self::GetArrayFromList($assignment["class_ids"]);#convert the list of class_ids to an array
                $found_assignment = array_search ($class_id,$class_ids); #true if found, false if not , null if invalid
                if($found_assignment!==false && isset($assignments_found))
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
                $class_ids = self::GetArrayFromList($assignment["class_ids"]);#convert the list of class_ids to an array
                $found_assignment = array_search ($class_id,$class_ids); #true if found, false if not , null if invalid
                if($found_assignment!==false && isset($assignments_found))
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
};#END OF CLASS

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_GET['action'])) {
    
    $DBInfo = new DBInfo();
    
    switch($_GET['action']) {
        case 'CreateClassroom':
            
            break;
        case 'getAllTeachers':
            
            $result = $DBInfo::getAllTeachers();
            
            return $result;
            
            break;
        case 'GetAllStudents':
            
            $result = $DBInfo::GetAllStudents();
            
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
            
            break;
        case 'GetAllStreams':
            
            $result = $DBInfo::GetAllStreams();
            
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
            
            $result = $DBInfo::GetAllSubjects();
            
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
        default:
            return null;
            break;
    }

} else {
    return null;
}
