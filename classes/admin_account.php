<?php
require_once (realpath(dirname(__FILE__) . "/../handlers/error_handler.php")); #Allows printing of error and success messages

require_once (realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Allows connection to database
require_once (realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php")); #Allows encryption of passwords

#HANDLES ACCOUNTS
class AdminAccount
{
    //Variable initialization
    public $staffId;
    public $firstName;
    public $lastName;
    public $username;
    public $email;
    public $phone;
    public $password;
    public $encrypted_password;
    public $accType;

    //Constructor
    function __construct()
    {
        
    }

    //returns true if the account exists false if it doesn't - faster than the db_info function, selects one column
    public static function AccountExists($username,$acc_type)
    {
        #Database connection - mysqli object
        global $dbCon;

        $prepare_error = "Couldn't prepare query to check if account exists. <br><br> Technical information : ";  

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
            ErrorHandler::PrintError($prepare_error . $dbCon->error);
            return null;
        }
    }


    //Create an account - used by all classes that inherit from this class ie. teacher, principal, superuser
    protected function CreateAccount($args = 
            array("staffId"=>999,"firstName"=>"","lastName"=>"","username"=>"","email"=>"",
                "phone"=>"","accType"=>"","encrypted_password"=>""))
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
                $insert_stmt->bind_param("isssssss",
                    $args["staffId"],
                    $args["firstName"],
                    $args["lastName"],
                    $args["username"],
                    $args["email"],
                    $args["phone"],
                    $args["accType"],
                    $args["encrypted_password"]);  

            $insert_stmt->execute();
            }
            else #if the query cannot be prepared
            {
                ErrorHandler::PrintError("Couldn't prepare query to create a " . 
                $this->accType . " account. <br><br> Technical information : ".$dbCon->error);
            }
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

        $prepare_error = "Couldn't prepare query to check if password is valid. <br><br> Technical information : "; #displayed if prepare fails

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
            ErrorHandler::PrintError($prepare_error . $dbCon->error);
        }
    }


    //returns an array where the $this variables have been set as args
    protected function GetArgsArray()
    {
         #encrypt the current password
         $this->encrypted_password = PasswordEncrypt::EncryptPass($this->password);
         
         $args = array(
         "staffId"=>$this->staffId,
         "firstName"=>$this->firstName,
         "lastName"=>$this->lastName,
         "username"=>$this->username,
         "email"=>$this->email,
         "phone"=>$this->phone,
         "accType"=>$this->accType,
         "encrypted_password"=>$this->encrypted_password
         );

         return $args;
    }
};