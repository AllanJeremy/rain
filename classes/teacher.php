<?php

require_once("admin_account.php");

#HANDLES TEACHER RELATED FUNCTIONS
class Teacher extends AdminAccount
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        $this->accType = "teacher";
    }

    #Other Code here
    //Create a teacher account - call this to create  a teacher account
    //TODO Add the various account properties as parameters to the function
    public function CreateTeacher()
    {
        #Properties
    /*
        $this->staffId
        $this->firstName
        $this->lastName
        $this->username
        $this->email
        $this->phone
        $this->password
    */

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
                
            #set the class variable values to the post variable values
            $this->staffId = htmlspecialchars($_POST["new_staff_id"]);
            $this->firstName = htmlspecialchars($_POST["new_teacher_first_name"]);
            $this->lastName = htmlspecialchars($_POST["new_teacher_last_name"]);
            $this->username = htmlspecialchars($_POST["new_teacher_username"]);
            $this->email = htmlspecialchars($_POST["new_teacher_email"]);
            $this->password = htmlspecialchars($_POST["new_teacher_password"]);
            


            #if the phone number was set, set the this to it, otherwise leave the default in $args [""]
            if(isset($_POST["new_teacher_phone"]))
            {
                $this->phone = htmlspecialchars($_POST["new_teacher_phone"]);
                unset($_POST["new_teacher_phone"]);#unset after usage
            }

            #converts the this-> variables to an argument array
            $args = parent::GetArgsArray();
            parent::CreateAccount($args);
            ErrorHandler::PrintSuccess("Successfully created a teacher account. Username :".$_POST["new_teacher_username"]);#debug information

            #unset the post variables once they have been used
            unset(
                $_POST["new_teacher_first_name"],
                $_POST["new_teacher_last_name"],
                $_POST["new_teacher_email"],
                $_POST["new_teacher_username"],
                $_POST["new_staff_id"],
                $_POST["new_teacher_password"]
            );


        }
    }
};