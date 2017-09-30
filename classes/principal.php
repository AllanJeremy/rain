<?php

require_once("admin_account.php");

#HANDLES PRINCIPAL RELATED FUNCTIONS
class Principal extends AdminAccount
{
    //Variable initialization
    const MAX_PRINCIPAL_ACCOUNTS = 5;#default is 3 - one for the principal, one for the deputy, one for the director

    //Constructor
    function __construct()
    {
        $this->accType = "principal";
    }

    #Other Code here
    //Create a superuser account - call this to create  a superuser account
    //TODO Add the various account properties as parameters to the function
    public function CreatePrincipal($data)
    {
        $errors = array();
            #Properties
        /*
            $this->firstName = $data["firstName"]
            $this->lastName = $data["lastName"]
            $this->username = $data["username"]
            $this->email = $data["email"]
            $this->phone = $data["phone"]
            $this->password = $data["password"]
        */
        global $dbCon;
        $select_query = "SELECT acc_id FROM admin_accounts WHERE account_type='principal'";
        $principal_accounts = 0;#initial value - number of principal accoutns

        
        if($result = $dbCon->query($select_query))
        {
            $principal_accounts = $result->num_rows;
        }
        
        if($principal_accounts < self::MAX_PRINCIPAL_ACCOUNTS )
        {
            #if the teacher details are set (form data filled,phone can be left blank), create account
            if (Validator::PrincipalSignupValid($data))
            {         
                #set the class variable values to the post variable values
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
        }
        else
        {
            //TODO: refactor this into its own function
            array_push($errors,"Cannot create anymore principal accounts. Maximum principal accounts created.");
            ErrorHandler::PrintErrorLog($errors);
            return null;#Cannot create anymore principal accounts
        }
    }

    //Create principal teacher account : when select corresponding teacher account is selected
    public function CreatePrincipalTeacherAccount($data)
    {       
        require_once("teacher.php");
        $teacher = new Teacher();

        $create_principal_status = $this->CreatePrincipal($data);
        $teacher->CreateTeacher($data);

        return ($create_principal_status/*  && $create_teacher_status */);
    }
};
