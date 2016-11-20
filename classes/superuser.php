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

    //Create a superuser account
    function CreateSuperuser()
    {
        $this->staffId = 1;
        $this->firstName = "Super";
        $this->lastName = "User";

        $this->username = $this->firstName . $this->lastName . "_" . $this->staffId;
        
        $this->email = "superuser@brookhurst.com";
        $this->phone = "0725123456";
        $this->password = "123456" ;
        $this->encrypted_password = PasswordEncrypt::EncryptPass($this->password);

        parent::CreateAccount();
    }

};