<?php

class Validator
{

    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 50;

    public static $password_error = "";#password error

    //Teacher form entry information is valid - returns true if valid and false if not
    public static function TeacherSignupValid()
    {
        #if the teacher details are set (form data filled,phone can be left blank), create account
        if (
            isset(
                $_POST["new_teacher_first_name"],
                $_POST["new_teacher_last_name"],
                $_POST["new_teacher_email"],
                $_POST["new_teacher_username"],
                $_POST["new_staff_id"],
                $_POST["new_teacher_password"]
            )
            &&
            (
                $_POST["new_teacher_first_name"] !== "" &&
                $_POST["new_teacher_last_name"] !== "" &&
                $_POST["new_teacher_email"] !== "" &&
                $_POST["new_teacher_username"] !== "" &&
                $_POST["new_staff_id"] !== "" &&
                $_POST["new_teacher_password"]                
            )
        )
        {
            // Check if the password  is valid
            if (self::PasswordValid(htmlspecialchars($_POST["new_teacher_password"])))
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
            echo "missing values ";
            return false;
        }

    }

    // Principal form entry information is valid  - returns true if valid and false if not
    public static function PrincipalSignupValId()
    {

    }

    // Super user form entry information is valid - returns true if valid and false if not
    public static function SuperuserSignupValid()
    {

    }

    // Student form entry information is valid
    public static function StudentSignupValid()
    {

    }

    
    //Checks if the password is valid, at least minimum number of characters and not more than max characters
    public static function PasswordValid($password)
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH || strlen($password) > self::MAX_PASSWORD_LENGTH)
        {
            self::$password_error = "Password length is invalid.<br> Password must be between ".self::MIN_PASSWORD_LENGTH." and ". self::MAX_PASSWORD_LENGTH. " characters long.";
            return false;#password invalid
        }
        else
        {
            self::$password_error = "Password is valid";
            return true;
        }
    }

};