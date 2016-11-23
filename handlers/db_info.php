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
                return null;
            }
        }
        else
        {
            ErrorHandler::PrintError($prepare_error . $dbCon->error);
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
                return null;
            }
        }
        else
        {
            ErrorHandler::PrintError($prepare_error . $dbCon->error);
        }
    }

    //Get all students
    public static function GetAllStudents()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM student_accounts";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails
        return $result;
    }

    //Get all teachers
    public static function GetAllTeachers()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM admin_accounts WHERE account_type='teacher'";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails
        return $result;
    }

    //Get all principals
    public static function GetAllPrincipals()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM admin_accounts WHERE account_type='principal'";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails
        return $result;        
    }

    //Get all superusers
    public static function GetAllSuperusers()
    {
        global $dbCon;#database connection

        $select_query = "SELECT * FROM admin_accounts WHERE account_type='superuser'";

        $result = $dbCon->query($select_query);#run the query, returns false if it fails
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
}