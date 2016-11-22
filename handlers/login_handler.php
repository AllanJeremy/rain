<?php

ob_start();#enable output buffering, allows for sending of headers within the file, prevents errors

require_once("session_handler.php");#Handles sessions - included first so that session_start is at the beginning of this file

require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php"));#Connection to the database
require_once(realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php"));#Password encryption and verification

require_once(realpath(dirname(__FILE__) . "/../classes/admin_account.php"));#Checking if an admin account exists
require_once(realpath(dirname(__FILE__) . "/../classes/student.php"));#checking if the student account exists

include_once("error_handler.php");#Printing debug information

//Returns true if the admin login POST variables are set, false otherwise
function AdminLoginSet()
{
    return (isset($_POST["staff_acc_type"],$_POST["staff_username"],$_POST["staff_password"]));
}

//Returns true if the student login POST variables are set, false otherwise
function StudentLoginSet()
{
    return (isset($_POST["student_username"],$_POST["student_password"]));
}

//Check if admin login credentials are valid - returns true if valid and false if not
function AdminInfoValid()
{
    $admin_username = htmlspecialchars($_POST["staff_username"]);
    $admin_acc_type = htmlspecialchars($_POST["staff_acc_type"]);
    $admin_password = htmlspecialchars($_POST["staff_password"]);

    //If the account exists check if the credentials are valid
    if (AdminAccount::AccountExists($admin_username,$admin_acc_type) == true)
    {
        
        if(AdminAccount::LoginInfoValid($admin_username,$admin_password))
        {
            //Cleanup - we don't need this anymore
            unset($admin_username);
            unset($admin_acc_type);
            unset($admin_password);           
            return true;
        }
        else
        {
            //Cleanup - we don't need this anymore
            unset($admin_username);
            unset($admin_acc_type);
            unset($admin_password);           
            return false;
        }
        
    }
    else //The account does not exist. Return false
    {
        //Cleanup - we don't need this anymore
        unset($admin_username);
        unset($admin_acc_type);
        unset($admin_password);

        return false;
    }
}

//Check if student login credentials are valid - returns true if valid and false if not
function StudentInfoValid()
{
    $student_username = htmlspecialchars($_POST["student_username"]);
    $student_password = htmlspecialchars($_POST["student_password"]);

    //If the account exists check if the credentials are valid
    if (Student::AccountExists($student_username) == true)
    {
        
        if(Student::LoginInfoValid($student_username,$student_password))
        {
            //Cleanup - we don't need this anymore
            unset($student_username);
            unset($student_password);           
            return true;
        }
        else
        {
            //Cleanup - we don't need this anymore
            unset($student_username);
            unset($student_password);           
            return false;
        }
        
    }
    else //The account does not exist. Return false
    {
        //Cleanup - we don't need this anymore
        unset($student_username);
        unset($student_password);  
        return false;
    }
}


#RUN THIS CODE WHEN THIS FILE IS REFERENCED - when the user attempts to login

//Check if the student login variables have been set
if(StudentLoginSet())
{
    if(StudentInfoValid())
    {
        //If the info is valid, log them in
        $student_username = htmlspecialchars($_POST["student_username"]);
        
        MySessionHandler::StudentLogin($student_username);
        //header("Location:".MySessionHandler::LOGIN_REDIRECT_PAGE);#redirect logged out user to this page
        echo '1';
    }
    else
    {
        //if the info is invalid deny login
        ErrorHandler::PrintSmallError("Invalid student credentials, failed to logged in");
    }
}
else if (AdminLoginSet())//if the student variables have not been set, then check if the admin variables have been set
{
    if(AdminInfoValid())
    {
        //If the info is valid, log them in
        $admin_username = htmlspecialchars($_POST["staff_username"]);
        $admin_acc_type = htmlspecialchars($_POST["staff_acc_type"]);
        
        MySessionHandler::AdminLogin($admin_username,$admin_acc_type);
        //header("Location:".MySessionHandler::LOGIN_REDIRECT_PAGE);#redirect logged out user to this page
        echo '1';
    }
    else
    {
        //if the info is invalid deny login
        ErrorHandler::PrintSmallError("Invalid admin credentials, failed to logged in");
    }
}

