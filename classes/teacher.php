<?php

require_once("admin_account.php");

#DECLARES WHAT FUNCTIONS ARE USED FOR COMMENTS by TEACHERS
interface TeacherCommentFunctions
{
    public function TrCommentOnAss($ass_id,$teacher_id,$comment_text);#Teacher comment on assignment
    public function TrCommentOnAssSubmission($submission_id,$teacher_id,$comment_text);#Comment on assignment submission
}

#DECLARES WHAT ASSIGNMENT FUNCTIONS THE TEACHER MUST IMPLEMENT
interface TeacherAssignmentFunctions extends TeacherCommentFunctions
{
    //Create an assignment
    public function CreateAssignment($args=array(
            "teacher_id"=>0,
            "ass_title"=>"",
            "ass_description"=>"",
            "class_ids"=>"",
            "due_date"=>"",
            "attachments"=>"",
            "file_option"=>"view",
            "max_grade"=>100,
            "comments_enabled"=>true,
            "sent"=>true)
    );
    //Send assignment to classroom
    public function SendAssignment($ass_id);

    //Return assignment - teacher cannot edit/comment anymore, assignment has been returned to student
    public function ReturnAssignment($submission_id);
    
    //Grade assignment
    public function GradeAssignment($submission_id,$grade);

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
    public function CreateAssignment($args=array(
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
        global $dbCon;#database connection

        $insert_query = "INSERT INTO assignments(teacher_id,ass_title,ass_description,class_ids,due_date,attachments,file_option,max_grade,comments_enabled,sent) VALUES(?,?,?,?,?,?,?,?,?,?)";

        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            $insert_stmt->bind_param("issssssssi",
                $args["teacher_id"],
                $args["ass_title"],
                $args["ass_description"],
                $args["class_ids"],
                $args["due_date"],
                $args["attachments"],
                $args["file_option"],
                $args["max_grade"],
                $args["comments_enabled"],
                $args["sent"]
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
    public function TrCommentOnAss($ass_id,$teacher_id,$comment_text)
    {
        return CommentHandler::CommentOnAss($ass_id,$teacher_id,$comment_text,"teacher");
    }

    //Comment on assignment submission
    public function TrCommentOnAssSubmission($submission_id,$teacher_id,$comment_text)
    {
           
    }


    //Grade assignment
    public function GradeAssignment($submission_id,$grade)
    {
        global $dbCon;#database connection

        //if the submission exists
        if($submission = DbInfo::AssSubmissionExists($submission_id))
        {
            $assignment = DbInfo::AssignmentExists($submission["ass_id"]);

            //If the grade given is greater than the maximum grade - by default we cannot enter a grade less than 0
            if($grade>$assignment["max_grade"])
            {
                $grade = $assignment["max_grade"];
            }
            elseif($grade<0)//If the grade is less than 0 make it 0
            {
                $grade = 0;
            }

            
            $update_query = "UPDATE ass_submissions SET grade=? WHERE submission_id=?";
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("ii",$submission_id,$grade);
                if($update_stmt->execute())
                {
                    return true;#successfully executed query
                }
                else
                {
                    return false;#failed to execute query
                }
            }
            else
            {
                return null;#failed to prepare query
            }
        }
    }

    //Return assignment to a student - teacher cannot edit/comment anymore, assignment has been returned to student
    public function ReturnAssignment($submission_id)
    {
        global $dbCon;

        if($submission = DbInfo::AssSubmissionExists($submission_id))
        { 
            $update_query = "UPDATE ass_submissions SET returned=true WHERE submission_id=?";
            
            if($update_stmt = $dbCon->prepare($update_query))
            {
                $update_stmt->bind_param("i",$submission_id);
                if($update_stmt->execute())
                {
                    return true;#successfully executed query
                }
                else
                {
                    return false;#failed to execute query
                }
            }
            else
            {
                return null;#failed to prepare query
            }
        }
    }


    //Send an assignment
    public function SendAssignment($ass_id)
    {
        global $dbCon;#database connection
        
        //If the assignment exists
        if($assignment = DbInfo::AssignmentExists($ass_id))
        {
            //Only send the assignment if the assignment hasn't already been sent
            if(!$assignment["sent"])
            {
                $update_query = "UPDATE assignments SET sent=true WHERE ass_id=?";
                
                if($update_stmt = $dbCon->prepare($update_query))
                {
                    $update_stmt->bind_param("i",$ass_id);
                    if($update_stmt->execute())
                    {
                        return true;#successfully executed query
                    }
                    else
                    {
                        return false;#failed to execute query
                    }
                }
                else
                {
                    return null;#failed to prepare query
                }
            }
           return false;#if the assignment has already been sent for some reason, return false. We failed to resend it
            

        }
        else
        {
            return false;
        }
        
    }
};