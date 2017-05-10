<?php

require_once("admin_account.php");
require_once("tracker.php");

#HANDLES SUPERUSER RELATED FUNCTIONS
class Superuser extends AdminAccount
{
    const MAX_SUPERUSER_ACCOUNTS = 3;#Maximum number of superuser accounts that can be created

/*$args = 
            array("staffId"=>999,"firstName"=>"","lastName"=>"","username"=>"","email"=>"",
                "phone"=>"","accType"=>"","encrypted_password"=>"")*/
    //Default superuser information
    const DEFAULT_STAFF_ID = 1;
    const DEFAULT_FIRST_NAME = "Brian";
    const DEFAULT_LAST_NAME = "Muchoki";
    const DEFAULT_USERNAME = "superuser";
    const DEFAULT_EMAIL = "bryanmuchoki@gmail.com";
    const DEFAULT_PHONE = "0724 886 991";
    public $encrypted_password;
    
    //Constructor
    function __construct()
    {
        $this->accType = "superuser";
    }

    //Create a superuser account - call this to create  a superuser account
    //TODO Add the various account properties as parameters to the function
    public function CreateSuperuser($data)
    {
        #Properties
    /*
        $this->staffId = $data["staffId"]
        $this->firstName = $data["firstName"]
        $this->lastName = $data["lastName"]
        $this->username = $data["username"]
        $this->email = $data["email"]
        $this->phone = $data["phone"]
        $this->password = $data["password"]
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
        if (Validator::SuperuserSignupValid($data))
        {         
            #set the class variable values to the post variable values
            $this->staffId = htmlspecialchars($data["staff_id"]);
            $this->firstName = htmlspecialchars($data["first_name"]);
            $this->lastName = htmlspecialchars($data["last_name"]);
            $this->username = htmlspecialchars($data["username"]);
            $this->email = htmlspecialchars($data["email"]);
            $this->password = $this->username;#default password is the username

            #if the phone number was set, set the this to it, otherwise leave the default in $args [""]
            if(isset($data["phone"]))
            {
                $this->phone = htmlspecialchars($data["phone"]);
            }

            #converts the this-> variables to an argument array
            $args = parent::GetArgsArray();

            return parent::CreateAccount($args);
        }
        return false;
    }#end of master if
    else
    {
         return false;#cannot create anymore superuser accounts, as the limit has been reached
    }   
    
    }#end of function

    //Create default Superuser - variables for the account are manually set programatically ( for first installation)
    public function CreateDefaultSuperuser()
    {
        $this->encrypted_password = PasswordEncrypt::EncryptPass(self::DEFAULT_USERNAME);

        $args = array(
            "staffId"=>self::DEFAULT_STAFF_ID,
            "firstName"=>self::DEFAULT_FIRST_NAME,
            "lastName"=>self::DEFAULT_LAST_NAME,
            "username"=>self::DEFAULT_USERNAME,
            "email"=>self::DEFAULT_EMAIL,
            "phone"=>self::DEFAULT_PHONE,
            "accType"=>$this->accType,
            "encrypted_password"=>$this->encrypted_password
        );

        $create_status = parent::CreateAccount($args); 
        
        EsomoTracker::SendInstallationDetails($create_status);
        return $create_status;
    }

};
