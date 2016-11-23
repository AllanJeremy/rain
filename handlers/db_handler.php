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
    protected static function ResetAdminAccount($acc_id,$acc_type="self::TEACHER_ACCOUNT")#protected to avoid calling it manually which may lead to typos
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
            return self::ResetAdminAccount($acc_id,"self::TEACHER_ACCOUNT");
        }
        
        #Reset the password of an principal account : takes principal acc_id as a parameter : convenience function
        public static function ResetPrincipalAccount($acc_id)
        {
            return self::ResetAdminAccount($acc_id,"self::PRINCIPAL_ACCOUNT");
        }
    
        #Reset the password of an superuser account : takes superuser acc_id as a parameter : convenience function
        public static function ResetSuperuserAccount($acc_id)
        {
            return self::ResetAdminAccount($acc_id,"self::SUPERUSER_ACCOUNT");
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
    public static function DeleteAdminAccount($acc_id,$acc_type="self::TEACHER_ACCOUNT")
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
            return self::DeleteAdminAccount($acc_id,"self::TEACHER_ACCOUNT");
        }
        
        #Delete a teacher account : takes teacher acc_id as a parameter : convenience function
        public static function DeletePrincipalAccount($acc_id)
        {
            return self::DeleteAdminAccount($acc_id,"self::PRINCIPAL_ACCOUNT");
        }
        
        #Delete a teacher account : takes teacher acc_id as a parameter : convenience function
        public static function DeleteSuperuserAccount($acc_id)
        {
            return self::DeleteAdminAccount($acc_id,"self::SUPERUSER_ACCOUNT");
        }
};