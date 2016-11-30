<?php

require_once("admin_account.php");

#DECLARES WHAT ASSIGNMENT FUNCTIONS THE TEACHER MUST IMPLEMENT
interface TeacherAssignmentFunctions
{
    //Create an assignment
    public static function CreateAssignment($args=array(
            "teacher_id"=>0,
            "ass_title"=>"",
            "ass_description"=>"",
            "class_ids"=>"",
            "due_date"=>"",
            "attachments"=>"",
            "file_option"=>"view",
            "max_grade"=>100,
            "comments_enabled"=>true)
    );
};

#CLASS THAT HANDLES TEACHER RELATED FUNCTIONS
class Teacher extends AdminAccount implements TeacherAssignmentFunctions
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        $this->accType = "teacher";
    }

    #Other Code here
    //Create a teacher account - call this to create  a teacher account
    //TODO Add the various account properties as parameters to the function
    public function CreateTeacher()
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

        #if the teacher details are set (form data filled,phone can be left blank), create account
        if (Validator::TeacherSignupValid())
        {         
            #set the class variable values to the post variable values
            $this->staffId = htmlspecialchars($_POST["new_teacher_staff_id"]);
            $this->firstName = htmlspecialchars($_POST["new_teacher_first_name"]);
            $this->lastName = htmlspecialchars($_POST["new_teacher_last_name"]);
            $this->username = htmlspecialchars($_POST["new_teacher_username"]);
            $this->email = htmlspecialchars($_POST["new_teacher_email"]);
            $this->password = $this->username;#default password is the username
            


            #if the phone number was set, set the this to it, otherwise leave the default in $args [""]
            if(isset($_POST["new_teacher_phone"]))
            {
                $this->phone = htmlspecialchars($_POST["new_teacher_phone"]);
                unset($_POST["new_teacher_phone"]);#unset after usage
            }

            #converts the this-> variables to an argument array
            $args = parent::GetArgsArray();
            

            #unset the post variables once they have been used
            unset(
                $_POST["new_teacher_first_name"],
                $_POST["new_teacher_last_name"],
                $_POST["new_teacher_email"],
                $_POST["new_teacher_username"],
                $_POST["new_teacher_staff_id"],
                $_POST["new_teacher_password"],
                $_POST["new_teacher_confirm_password"]
            );

            return parent::CreateAccount($args);
        }

        return false;
    }

    //Create/send an Assignment
    public static function CreateAssignment($args=array(
        "teacher_id"=>0,
        "ass_title"=>"",
        "ass_description"=>"",
        "class_ids"=>"",
        "due_date"=>"",
        "attachments"=>"",
        "file_option"=>"view",
        "max_grade"=>100,
        "comments_enabled"=>true)
    )
    {
        global $dbCon;

        $insert_query = "INSERT INTO assignments(teacher_id,ass_title,ass_description,class_ids,due_date,attachments,file_option,max_grade,comments_enabled) VALUES(?,?,?,?,?,?,?,?,?)";

        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            $insert_stmt->bind_param("issssssss",
                $args["teacher_id"],
                $args["ass_title"],
                $args["ass_description"],
                $args["class_ids"],
                $args["due_date"],
                $args["attachments"],
                $args["file_option"],
                $args["max_grade"],
                $args["comments_enabled"]
            );
            if($insert_stmt->execute())
            {
                return true;#successfully created assignment
            }
            else
            {
                return false;#failed to send the assignment
            }
        }
        else
        {
            return null;#failed to prepare the query
        }
    }

    //Comment on assignment

    //Comment on assignment submission

    //Grade assignment

    //Return assignment - teacher cannot edit/comment anymore, assignment has been returned to student
};