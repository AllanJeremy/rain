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
    public static function GetStudentAccount($adm_no_input)
    {
        global $dbCon;

        $prepare_error = "Couldn't prepare query to retrieve student account information. <br><br> Technical information : ";

        $query = "SELECT * FROM student_accounts WHERE adm_no=?";

        if($stmt = $dbCon->prepare($query))
        {
            $stmt->bind_param("i",$adm_no_input);
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
}