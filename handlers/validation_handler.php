<?php

class Validator
{

    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 50;

    public static $password_error = "";#password error

    //Teacher form entry information is valid - returns true if valid and false if not
    public static function TeacherSignupValid($data)
    {
        #if the teacher details are set (form data filled,phone can be left blank), create account
        return
        (
            isset(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["username"],
                $data["staff_id"]
            )
            &&
            (
                !empty($data["first_name"])&&
                !empty($data["last_name"])&&
                !empty($data["email"])&&
                !empty($data["username"])&&
                !empty($data["staff_id"])
            )
        );

    }

    // Principal form entry information is valid  - returns true if valid and false if not
    public static function PrincipalSignupValid($data)
    {
        #if the principal details are set (form data filled,phone can be left blank), create account
        return
        (
            isset(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["username"],
                $data["staff_id"]
            )
            &&
            (
                !empty($data["first_name"]) &&
                !empty($data["last_name"]) &&
                !empty($data["email"]) &&
                !empty($data["username"]) &&
                !empty($data["staff_id"])
            )
        );
    }

    // Super user form entry information is valid - returns true if valid and false if not
    public static function SuperuserSignupValid($data)
    {
        #if the principal details are set (form data filled,phone can be left blank), create account
        return
        (
            isset(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["username"],
                $data["staff_id"]
            )
            &&
            (
                !empty($data["first_name"]) &&
                !empty($data["last_name"]) &&
                !empty($data["email"]) &&
                !empty($data["username"]) &&
                !empty($data["staff_id"])
            )
        );

    }

    // Student form entry information is valid
    public static function StudentSignupValid($data)
    {
        #if the principal details are set (form data filled,phone can be left blank), create account
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
