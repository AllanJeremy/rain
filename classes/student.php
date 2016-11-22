<?php
require_once (realpath(dirname(__FILE__) . "/../handlers/error_handler.php")); #Allows printing of error and success messages

require_once (realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Allows connection to database
require_once (realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php")); #Allows encryption of passwords

#HANDLES STUDENT RELATED FUNCTIONS
class Student
{
    //Variable initialization
    
    //Constructor
    function __construct()
    {
        
    }

    //returns true if the account exists false if it doesn't - faster than the db_info function for finding student, selects one column
    public static function AccountExists($username)
    {
        #Database connection - mysqli object
        global $dbCon;

        $prepare_error = "Couldn't prepare query to check if account exists. <br><br> Technical information : ";  

        #Select acc_id instead of * to increase speed of execution (optimization)
        $search_query = "SELECT username FROM student_accounts WHERE username=?";
        
        if($search_stmt = $dbCon->prepare($search_query))
        {
            $search_stmt->bind_param("s",$username);
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
  
    protected static function CreateStudentAccount($args = 
    array("adm_no" => "","first_name" => "","last_name" => "","username" => "","password" => "","email" => "",
    "personal_phone" => "","parent_names" => "","parent_phone" => "","full_name" => "","class_ids" => ""))
    {
        #Database connection - mysqli object
        global $dbCon;
        
        if($this::AccountExists($args["username"])==false)
        {  
            #query for inserting the information to the database
            $insert_query = "INSERT INTO 
            student_accounts(adm_no,first_name,last_name,username,password,email,personal_phone,parent_names,parent_phone,full_name,class_ids) 
            VALUES(?,?,?,?,?,?,?,?,?,?,?)"; 

            if($insert_stmt = $dbCon->prepare($insert_query))
            {
                $insert_stmt->bind_param("issssssssss",
                $args["adm_no"],
                $args["first_name"],
                $args["last_name"],
                $args["username"],
                $args["password"],
                $args["email"],
                $args["personal_phone"],
                $args["parent_names"],
                $args["parent_phone"],
                $args["full_name"],
                $args["class_ids"]
                );        

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

    //Check if password and username of an account match - faster than the db_info function for finding student, selects one column
    public static function LoginInfoValid($username_input="",$password_input="")
    {
        global $dbCon;

        $prepare_error = "Couldn't prepare query to check if password is valid. <br><br> Technical information : "; #displayed if prepare fails

        $search_query = "SELECT password FROM student_accounts WHERE username=?";

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

};