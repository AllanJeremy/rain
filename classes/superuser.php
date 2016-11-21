<?php

require_once("admin_account.php");

#HANDLES SUPERUSER RELATED FUNCTIONS
class Superuser extends AdminAccount
{
    
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
        $args = parent::GetArgsArray();

        parent::CreateAccount($args);
    }

};