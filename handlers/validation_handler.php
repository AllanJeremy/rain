<?php

class Validator
{
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 50;

    public static $password_error = "";#password error
    //Returns true if student data is set
    private static function StudentDataIsSet($data)
    {
        return
        (
            isset(
                $data["student_id"],
                $data["first_name"],
                $data["last_name"],
                $data["username"]
            )
            &&
            (
                !empty($data["student_id"]) &&
                !empty($data["first_name"]) &&
                !empty($data["last_name"]) &&
                !empty($data["username"])
            )
        );
    }
    //Returns true if admin data is set
    private static function AdminDataIsSet($data)
    {
        return (
            isset(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["username"]
            )&&
            (
                !empty($data["first_name"])&&
                !empty($data["last_name"])&&
                !empty($data["email"])&&
                !empty($data["username"])
            )
        );
    }
    
    private static function RecordsExistInResult($mysqli_result)
    {
        if($mysqli_result)
        {
            return ($mysqli_result->num_rows>0);
        }
        return $mysqli_result;
    }

    //TODO: Find out if all international schools use a numeric student id system ~ or what kind of system they use for identifying students
    private static function AccountSignupValid($accType,$data)
    {
        $errors = array();#This will store the list of errors
        $username_exists = false;
        $email_exists = false;
        
        #References to username and emails
        $username = &$data["username"];
        $email = &$data["email"];

        #If the data has been set
        $data_is_set = self::AdminDataIsSet($data);

        //Set the username exists and 
        switch($accType)
        {
            case "student":
                $username_exists = DbInfo::StudentUsernameExists($username);
                $data_is_set = self::StudentDataIsSet($data);#Set data is set to student data is set

                #Check if the student's student id exists ~ only extra validation in this function
                if(DbInfo::StudentIdExists($data["student_id"]))
                {
                    array_push($errors,"A $accType account with that student id already exists.");
                }

            break;
            case "teacher":
                $username_exists = DbInfo::TeacherUsernameExists($username);
                $email_exists = DbInfo::TeacherEmailExists($email);
            break;
            case "principal":
                $username_exists = DbInfo::PrincipalUsernameExists($username);
                $email_exists = DbInfo::PrincipalEmailExists($email);
            break;
            case "superuser":
                $username_exists = DbInfo::SuperuserUsernameExists($username);
                $email_exists = DbInfo::SuperuserEmailExists($email);
            break;
            default:
                echo "Error: Invalid signup validation check. Check switch statement";
                return false;
        }

        //If the data is set
        if($data_is_set)
        {
            $username_exists = self::RecordsExistInResult($username_exists);
            $email_exists = self::RecordsExistInResult($email_exists);

            //Check if the username exists
            if($username_exists)
            {
                array_push($errors,"A $accType account with that username already exists.");
            }

            //TODO: Check if the username is valid
            #code
        
            //Check if the email exists
            if($email_exists)
            {
                array_push($errors,"A $accType account with that email address already exists");
            }
        }
        else
        {
            array_push($errors,"Some required fields have not been set");
        }

        if(count($errors)>0)
        {
            ErrorHandler::PrintErrorLog($errors);
            return false;
        }
        else{
            return true;
        }
    }
    //TODO: CONSIDER ADDING A CLASS FOR STORING ERROR MESSAGES
    //Teacher form entry information is valid - returns true if valid and false if not
    public static function TeacherSignupValid($data)
    {
        return self::AccountSignupValid("teacher",$data);
    }

    // Principal form entry information is valid  - returns true if valid and false if not
    public static function PrincipalSignupValid($data)
    {
        return self::AccountSignupValid("principal",$data);
    }

    // Super user form entry information is valid - returns true if valid and false if not
    public static function SuperuserSignupValid($data)
    {
        return self::AccountSignupValid("superuser",$data);
    }

    // Student form entry information is valid
    public static function StudentSignupValid($data)
    {
        return self::AccountSignupValid("student",$data);
    }

    
    //Checks if the password is valid, at least minimum number of characters and not more than max characters
    public static function PasswordValid($password)
    {
        #if password is within the acceptable range
        if (strlen($password) < self::MIN_PASSWORD_LENGTH || strlen($password) > self::MAX_PASSWORD_LENGTH)
        {
            self::$password_error = "Password must be between ".self::MIN_PASSWORD_LENGTH." and ". self::MAX_PASSWORD_LENGTH. " characters long.";
            return false;#password invalid
        }
        else
        {
            self::$password_error = "Password is valid";
            return true;
        }
    }

    //Checks if the passwords match, returns true if they do and false if they don't
    public static function PasswordsMatch($password,$confirmPassword)
    {
        return ($password == $confirmPassword);
    }

};
