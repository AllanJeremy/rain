<?php
require_once (realpath(dirname(__FILE__) . "/../handlers/error_handler.php")); #Allows printing of error and success messages

require_once (realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Allows connection to database
require_once (realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php")); #Allows encryption of passwords

require_once (dirname(__FILE__) ."/../handlers/validation_handler.php");#Handles validation of form data

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
            // ErrorHandler::PrintError($prepare_error . $dbCon->error);
            return null;
        }
    }


    //Create an account - used by all classes that inherit from this class ie. teacher, principal, superuser
    protected function CreateAccount($args = 
            array("firstName"=>"","lastName"=>"","username"=>"","email"=>"",
                "phone"=>"","accType"=>"","encrypted_password"=>""))
    {
        #Database connection - mysqli object
        global $dbCon;
        $errors = array();
        $error_msg = "Something went wrong while trying to create your account. Consider reporting this to the dev team";

        #query for inserting the information to the database
        $insert_query = "INSERT INTO admin_accounts(first_name,last_name,username,email,phone,account_type,password) 
        VALUES(?,?,?,?,?,?,?)"; 

        //TODO : Consider getting more consistent naming for args parameters ~ choose either CamelCase or this_case (don't know the name)
        //Prepare query
        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            //Title case names
            $args["firstName"] = ucwords(strtolower($args["firstName"]));
            $args["lastName"] = ucwords(strtolower($args["lastName"]));

            $insert_stmt->bind_param("sssssss",
                $args["firstName"],
                $args["lastName"],
                $args["username"],
                $args["email"],
                $args["phone"],
                $args["accType"],
                $args["encrypted_password"]);  

            #return true if account was successfully created
            if(!$insert_stmt->execute())
            {
                array_push($errors,"[query exec error] : $error_msg");
            }
        }
        else #if the query cannot be prepared
        {
            array_push($errors,"[query prepare error] : $error_msg");
        }
        if(count($errors)==0)
        {
            return true;
        }
        else
        {
            return $errors;
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
