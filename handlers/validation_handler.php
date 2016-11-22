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
                $_POST["new_teacher_staff_id"],
                $_POST["new_teacher_password"],
                $_POST["new_teacher_confirm_password"]
            )
            &&
            (
                $_POST["new_teacher_first_name"] !== "" &&
                $_POST["new_teacher_last_name"] !== "" &&
                $_POST["new_teacher_email"] !== "" &&
                $_POST["new_teacher_username"] !== "" &&
                $_POST["new_teacher_staff_id"] !== "" &&
                $_POST["new_teacher_password"] !== "" &&
                $_POST["new_teacher_confirm_password"] !== ""                 
            )
        )
        {
            // Check if the password  is valid (appropriate length and passwords match)
            if (self::PasswordValid(htmlspecialchars($_POST["new_teacher_password"])) && 
                self::PasswordsMatch($_POST["new_teacher_password"],$_POST["new_teacher_confirm_password"])
                )
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
            //echo "missing values ";
            return false;
        }

    }

    // Principal form entry information is valid  - returns true if valid and false if not
    public static function PrincipalSignupValid()
    {
        #if the principal details are set (form data filled,phone can be left blank), create account
        if (
            isset(
                $_POST["new_principal_first_name"],
                $_POST["new_principal_last_name"],
                $_POST["new_principal_email"],
                $_POST["new_principal_username"],
                $_POST["new_principal_staff_id"],
                $_POST["new_principal_password"],
                $_POST["new_principal_confirm_password"]
            )
            &&
            (
                $_POST["new_principal_first_name"] !== "" &&
                $_POST["new_principal_last_name"] !== "" &&
                $_POST["new_principal_email"] !== "" &&
                $_POST["new_principal_username"] !== "" &&
                $_POST["new_principal_staff_id"] !== "" &&
                $_POST["new_principal_password"] !== "" &&
                $_POST["new_principal_confirm_password"] !== ""                 
            )
        )
        { 
            // Check if the password  is valid (appropriate length and passwords match)
            if (self::PasswordValid(htmlspecialchars($_POST["new_principal_password"])) && 
                self::PasswordsMatch($_POST["new_principal_password"],$_POST["new_principal_confirm_password"])
                )
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
            return false;
        }
    }

    // Super user form entry information is valid - returns true if valid and false if not
    public static function SuperuserSignupValid()
    {
        #if the principal details are set (form data filled,phone can be left blank), create account
        if (
            isset(
                $_POST["new_superuser_first_name"],
                $_POST["new_superuser_last_name"],
                $_POST["new_superuser_email"],
                $_POST["new_superuser_username"],
                $_POST["new_superuser_staff_id"],
                $_POST["new_superuser_password"],
                $_POST["new_superuser_confirm_password"]
            )
            &&
            (
                $_POST["new_superuser_first_name"] !== "" &&
                $_POST["new_superuser_last_name"] !== "" &&
                $_POST["new_superuser_email"] !== "" &&
                $_POST["new_superuser_username"] !== "" &&
                $_POST["new_superuser_staff_id"] !== "" &&
                $_POST["new_superuser_password"] !== "" &&
                $_POST["new_superuser_confirm_password"] !== ""              
            )
        )
        {
            // Check if the password  is valid (appropriate length and passwords match)
            if (self::PasswordValid(htmlspecialchars($_POST["new_superuser_password"])) && 
                self::PasswordsMatch($_POST["new_superuser_password"],$_POST["new_superuser_confirm_password"])
                )
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
            return false;
        }
    }

    // Student form entry information is valid
    public static function StudentSignupValid()
    {
        #if the principal details are set (form data filled,phone can be left blank), create account
        if (
            isset(
                $_POST["new_student_id"],
                $_POST["new_student_first_name"],
                $_POST["new_student_last_name"],
                $_POST["new_student_username"],
                $_POST["new_student_password"],
                $_POST["new_student_confirm_password"]
            )
            &&
            (
                $_POST["new_student_id"] !== "" &&
                $_POST["new_student_first_name"] !== "" &&
                $_POST["new_student_last_name"] !== "" &&
                $_POST["new_student_username"] !== "" &&
                $_POST["new_student_password"] !== "" &&
                $_POST["new_student_confirm_password"] !== ""               
            )
        )
        {
            // Check if the password  is valid (appropriate length and passwords match)
            if (self::PasswordValid(htmlspecialchars($_POST["new_student_password"])) && 
                self::PasswordsMatch($_POST["new_student_password"],$_POST["new_student_confirm_password"])
                )
            {
                echo "student validated<br>";
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            //echo "missing values ";
            return false;
        }
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