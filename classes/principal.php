<?php

require_once("admin_account.php");

#HANDLES PRINCIPAL RELATED FUNCTIONS
class Principal extends AdminAccount
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        $this->accType = "principal";
    }

    #Other Code here
    //Create a superuser account - call this to create  a superuser account
    //TODO Add the various account properties as parameters to the function
    public function CreatePrincipal()
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
        if (Validator::PrincipalSignupValid())
        {         
            #set the class variable values to the post variable values
            $this->staffId = htmlspecialchars($_POST["new_principal_staff_id"]);
            $this->firstName = htmlspecialchars($_POST["new_principal_first_name"]);
            $this->lastName = htmlspecialchars($_POST["new_principal_last_name"]);
            $this->username = htmlspecialchars($_POST["new_principal_username"]);
            $this->email = htmlspecialchars($_POST["new_principal_email"]);
            $this->password = htmlspecialchars($_POST["new_principal_password"]);
            


            #if the phone number was set, set the this to it, otherwise leave the default in $args [""]
            if(isset($_POST["new_principal_phone"]))
            {
                $this->phone = htmlspecialchars($_POST["new_principal_phone"]);
                unset($_POST["new_principal_phone"]);#unset after usage
            }

            #converts the this-> variables to an argument array
            $args = parent::GetArgsArray();
            

            #unset the post variables once they have been used
            unset(
                $_POST["new_principal_first_name"],
                $_POST["new_principal_last_name"],
                $_POST["new_principal_email"],
                $_POST["new_principal_username"],
                $_POST["new_principal_staff_id"],
                $_POST["new_principal_password"],
                $_POST["new_principal_confirm_password"]
            );

            return parent::CreateAccount($args);
        }

        return false;
    }
};