<?php
require_once ("handlers/error_handler.php"); #Allows printing of error and success messages

require_once ("handlers/db_connect.php"); #Allows connection to database
require_once ("handlers/pass_encrypt.php"); #Allows encryption of passwords

#HANDLES ACCOUNTS
class AdminAccount
{
    //Variable initialization
    protected $staffId;
    protected $firstName;
    protected $lastName;
    protected $username;
    protected $email;
    protected $phone;
    protected $password;
    protected $encrypted_password;
    protected $accType;

    //Constructor
    function __construct()
    {
        
    }

    //returns true if the account exists false if it doesn't
    protected function AccountExists($acc_type,$username)
    {
        global $dbCon;
        $search_query = "SELECT * FROM admin_accounts WHERE username=? AND account_type=?";

        if($search_stmt = $dbCon->prepare($search_query))
        {

        }
        else #if the query cannot be prepared
        {
            ErrorHandler::PrintError("Couldn't prepare query to check if account exists. <br><br> Technical information : ".$dbCon->error);
        }
    }

    //Create an account - used by all classes that inherit from this class ie. teacher, principal, superuser
    protected function CreateAccount()
    {
        global $dbCon;#connection to the database

        #query for inserting the information to the database
        $insert_query = "INSERT INTO admin_accounts(staff_id,first_name,last_name,username,email,phone,account_type,password) 
        VALUES(?,?,?,?,?,?,?,?)";

        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            $insert_stmt->bind_param("isssssss",$this->staffId,$this->firstName,$this->lastName,
            $this->username,$this->email,$this->phone,$this->accType,$this->encrypted_password);        

           $insert_stmt->execute();
        }
        else #if the query cannot be prepared
        {
            ErrorHandler::PrintError("Couldn't prepare query to create a " . $this->accType . " account. <br><br> Technical information : ".$dbCon->error);
        }
    }
};