<?php

require_once("admin_account.php");

#HANDLES SUPERUSER RELATED FUNCTIONS
class Superuser extends AdminAccount
{
    const MAX_SUPERUSER_ACCOUNTS = 2;#Maximum number of superuser accounts that can be created

    //Constructor
    function __construct()
    {
        $this->accType = "superuser";
    }

    //Create a superuser account - call this to create  a superuser account
    //TODO Add the various account properties as parameters to the function
    public function CreateSuperuser()
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
        global $dbCon;
        $select_query = "SELECT acc_id FROM admin_accounts WHERE account_type='superuser'";
        $superuser_accounts = 0;#initial value
        if($result = $dbCon->query($select_query))
        {
            $superuser_accounts = $result->num_rows;
        }

     if($superuser_accounts<=self::MAX_SUPERUSER_ACCOUNTS)
     {
        #if the teacher details are set (form data filled,phone can be left blank), create account
        if (Validator::SuperuserSignupValid() )
        {         
            #set the class variable values to the post variable values
            $this->staffId = htmlspecialchars($_POST["new_superuser_staff_id"]);
            $this->firstName = htmlspecialchars($_POST["new_superuser_first_name"]);
            $this->lastName = htmlspecialchars($_POST["new_superuser_last_name"]);
            $this->username = htmlspecialchars($_POST["new_superuser_username"]);
            $this->email = htmlspecialchars($_POST["new_superuser_email"]);
            $this->password = $this->username;#default password is the username
            


            #if the phone number was set, set the this to it, otherwise leave the default in $args [""]
            if(isset($_POST["new_superuser_phone"]))
            {
                $this->phone = htmlspecialchars($_POST["new_superuser_phone"]);
                unset($_POST["new_superuser_phone"]);#unset after usage
            }

            #converts the this-> variables to an argument array
            $args = parent::GetArgsArray();
            

            #unset the post variables once they have been used
            unset(
                $_POST["new_superuser_first_name"],
                $_POST["new_superuser_last_name"],
                $_POST["new_superuser_email"],
                $_POST["new_superuser_username"],
                $_POST["new_superuser_staff_id"],
                $_POST["new_superuser_password"],
                $_POST["new_superuser_confirm_password"]
            );

            return parent::CreateAccount($args);
        }
        return false;
    }#end of master if
    else
    {
         return null;#cannot create anymore superuser accounts, as the limit has been reached
    }   
    
    }#end of function

    //Create default Superuser - variables for the account are manually set programatically ( for first installation)
    public function CreateDefaultSuperuser()
    {
        return parent::CreateAccount($args); 
    }

};