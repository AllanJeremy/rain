<?php

require_once("db_connect.php");#connection to the database
include_once("error_handler.php");

#USED TO RETRIEVE INFORMATION FROM THE DATABASE
class DbInfo
{

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

    //Get admin account by ID , default acc type is teacher but this can be passed in as different parameter
    public static function GetAdminById($acc_id,$acc_type="teacher")
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
                return $select_result;#return the records
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

    //Get student account by ID
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
                return $select_result;#return the records
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


}