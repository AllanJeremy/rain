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

    
        $args = parent::GetArgsArray();

        return parent::CreateAccount($args);
    }
};