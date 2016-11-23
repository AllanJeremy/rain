<?php

require_once("db_connect.php");#connection to the database
include_once("error_handler.php");

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

    //Get all students
    public static function GetAllStudents()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM student_accounts";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails

        if ($result->num_rows == 0)#if the number of students found was 0, return false
        {
            return false;
        }
        return $result;
    }

    //Get all teachers
    public static function GetAllTeachers()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM admin_accounts WHERE account_type='teacher'";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails
        
        if ($result->num_rows == 0)#if the number of students found was 0, return false
        {
            ErrorHandler::PrintError("Something went wrong with teacher retrieval");
            return false;
        }
        return $result;
    }

    //Get all principals
    public static function GetAllPrincipals()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM admin_accounts WHERE account_type='principal'";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails

        if ($result->num_rows == 0)#if the number of students found was 0, return false
        {
            return false;
        }
        return $result;        
    }

    //Get all superusers
    public static function GetAllSuperusers()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM admin_accounts WHERE account_type='superuser'";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails
        
        if ($result->num_rows == 0)#if the number of students found was 0, return false
        {
            return false;
        }
        return $result;
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
    public static function GetStudentById($acc_id)
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
        return self::SinglePropertyExists("student_accounts","adm_no",$std_id,"i");
    }

    #Check if an student account with that username exists
    public static function StudentIdExists($std_username)
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

        $select_query = "SELECT * FROM $table_name WHERE $column_name=? AND $acc_type = ?";
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
    protected static function AdminEmailExists($admin_username,$acc_type)
    {
        return self::SingleAdminPropertyExists("admin_accounts","username",$admin_username,"ss",$acc_type);
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
}