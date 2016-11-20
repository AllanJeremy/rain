<?php

require_once("db_connect.php");#Connection to the database
require_once("pass_encrypt.php");#Password encryption and verification

require_once("classes/admin_account.php");#Checking if an admin account exists

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

}


#RUN THIS CODE WHEN THIS FILE IS REFERENCED - when the user attempts to login

//Check if the student login variables have been set
if(StudentLoginSet())
{
    if(StudentInfoValid())
    {
        //If the info is valid, log them in
    }
    else
    {
        //if the info is invalid deny login
    }
}
else if (AdminLoginSet())//if the student variables have not been set, then check if the admin variables have been set
{
    if(AdminInfoValid())
    {
        //If the info is valid, log them in
        ErrorHandler::PrintSmallSuccess("Successfully logged in");
    }
    else
    {
        //if the info is invalid deny login
        ErrorHandler::PrintSmallError("Invalid credentials, failed to logged in");
    }
}

