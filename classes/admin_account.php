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
    public static function AccountExists($username,$acc_type)
    {
        #Database connection - mysqli object
        global $dbCon;

        #Select acc_id instead of * to increase speed of execution (optimization)
        $search_query = "SELECT username FROM admin_accounts WHERE username=? AND account_type=?";

        if($search_stmt = $dbCon->prepare($search_query))
        {
            $search_stmt->bind_param("ss",$username,$acc_type);
            $search_stmt->execute();
            $search_result = $search_stmt->get_result();

            
            if($search_result->num_rows>0)
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        else #if the query cannot be prepared
        {
            ErrorHandler::PrintError("Couldn't prepare query to check if account exists. <br><br> Technical information : ".$dbCon->error);
            return null;
        }
    }


    //Create an account - used by all classes that inherit from this class ie. teacher, principal, superuser
    protected function CreateAccount()
    {
        #Database connection - mysqli object
        global $dbCon;
        
        if($this::AccountExists($this->username,$this->accType)==false)
        {  
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
                ErrorHandler::PrintError("Couldn't prepare query to create a " . 
                $this->accType . " account. <br><br> Technical information : ".$dbCon->error);
            }
            ErrorHandler::PrintSmallSuccess("Successfully created a ".$this->accType." account");
        }
        else
        {
            ErrorHandler::PrintSmallError("Failed to create a ".$this->accType." account with the username ".$this->username." as it already exists.");
        }
    }

    //Check if password and username of an account match
    public static function LoginInfoValid($username_input="",$password_input="")
    {
        global $dbCon;

        $search_query = "SELECT password FROM admin_accounts WHERE username=?";

        if($search_stmt = $dbCon->prepare($search_query))
        {
            $search_stmt->bind_param("s",$username_input);
            $search_stmt->execute();

            $search_result = $search_stmt->get_result();
            

            //Ensuring the account exists once more to prevent sql errors
            if($search_result->num_rows>0)
            {
                foreach ($search_result as $result) {
                    //Returns true if valid, false if not
                    return PasswordEncrypt::Verify($password_input,$result["password"]);
                    break;
                }
                unset($result);
            }
            else //Account does not exist, should have already been checked, this is a secondary check
            {
                return false;
            }
        }
        else
        {
            ErrorHandler::PrintError("Couldn't prepare query to check if password is valid. <br><br> Technical information : ".$dbCon->error);
        }
    }

};