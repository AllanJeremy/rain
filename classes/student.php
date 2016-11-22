<?php
require_once (realpath(dirname(__FILE__) . "/../handlers/error_handler.php")); #Allows printing of error and success messages

require_once (realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Allows connection to database
require_once (realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php")); #Allows encryption of passwords

require_once (dirname(__FILE__) ."/../handlers/validation_handler.php");#Handles validation of form data

#HANDLES STUDENT RELATED FUNCTIONS
class Student
{
    //Variable initialization
    public $student_id;
    public $first_name;
    public $last_name;
    public $full_name;   
    public $username;
    public $email;
    public $personal_phone;
    public $parent_names;
    public $parent_phone;
    public $password;
    public $encrypted_password;

    //Constructor
    function __construct()
    {
        
    }

    //returns true if the account exists false if it doesn't - faster than the db_info function for finding student, selects one column
    public static function AccountExists($username)#doesn't run when information is valid but runs when it is not
    {
        #Database connection - mysqli object
        global $dbCon;

        $prepare_error = "Couldn't prepare query to check if account exists. <br><br> Technical information : ";  

        #Select acc_id instead of * to increase speed of execution (optimization)
        $search_query = "SELECT acc_id FROM student_accounts WHERE username=?";
        
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

    //Deals with database operations of creating the account - protected function
    protected static function CreateAccount($args = 
    array("adm_no" => "","first_name" => "","last_name" => "","username" => "","encrypted_password" => "","email" => "",
    "personal_phone" => "","parent_names" => "","parent_phone" => "","full_name" => "","class_ids" => ""))
    {
        #Database connection - mysqli object
        global $dbCon;
        
        if(self::AccountExists($args["username"])==false)
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
                $args["encrypted_password"],
                $args["email"],
                $args["personal_phone"],
                $args["parent_names"],
                $args["parent_phone"],
                $args["full_name"],
                $args["class_ids"]
                );        

                if ($insert_stmt->execute())
                {
                    echo "<p class='pink-text'>Succeeded in creating the student account</p>";
                }
                else
                {
                    
                    echo "<p class='teal-text'>Failed in creating the student account<br>".$dbCon->error."</p>";
                }
                
                return true;#return true if account was successfully created
            }
            else #if the query cannot be prepared
            {
                ErrorHandler::PrintError("Couldn't prepare query to create a " . 
                $this->accType . " account. <br><br> Technical information : ".$dbCon->error);
                return false;#return false if account creation failed
            }

        }
        else#account already exists
        {
            return false;#return false if account creation failed
            // ErrorHandler::PrintSmallError("Failed to create a ".$this->accType." account with the username ".$this->username." as it already exists.");
        }
    }

    //Check if password and username of an account match - faster than the db_info function for finding student, selects one column
    public static function LoginInfoValid($username_input,$password_input)
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
            return $dbCon->error;
        }
    }

    //Public function that can be called to create the student account
    public function CreateStudentAccount()
    {
        #if the teacher details are set (form data filled,phone can be left blank), create account
        if (Validator::StudentSignupValid())
        {         
            #set the class variable values to the post variable values
            $this->student_id = htmlspecialchars($_POST["new_student_id"]);
            $this->first_name = htmlspecialchars($_POST["new_student_first_name"]);
            $this->last_name = htmlspecialchars($_POST["new_student_last_name"]); 

            $this->full_name = $this->first_name . " " . $this->last_name;#full name is first name + last name

            $this->username = htmlspecialchars($_POST["new_student_username"]);
            $this->password = htmlspecialchars($_POST["new_student_password"]);

            $args = $this->GetArgsArray();

            return self::CreateAccount($args);
        }

        return false;#failed to create account
    }

    //returns an array where the $this variables have been set as args
    protected function GetArgsArray()
    {
        #encrypt the current password
        $this->encrypted_password = PasswordEncrypt::EncryptPass($this->password);
        
        $args = array(
                    "adm_no" => $this->student_id,
                    "first_name" => $this->first_name,
                    "last_name" => $this->last_name,
                    "username" => $this->username,
                    "encrypted_password" => $this->encrypted_password,
                    "email" => "",
                    "personal_phone" => "",
                    "parent_names" => "",
                    "parent_phone" => "",
                    "full_name" => $this->full_name,
                    "class_ids" => ""
                );

         return $args;
    }
};