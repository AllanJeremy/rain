<?php

require_once (dirname(__FILE__) ."/../classes/admin_account.php");#admin account -  contains parent class
require_once (dirname(__FILE__) ."/../handlers/db_handler.php");#db_handler - convenience delete functions

#DECLARES WHAT FUNCTIONS ARE USED FOR COMMENTS by TEACHERS
interface TeacherCommentFunctions
{
    //Assignments
    public function TrCommentOnAss($ass_id,$teacher_id,$comment_text);#Teacher comment on ass.
    
    //Assignment submissions
    public function TrCommentOnAssSubmission($submission_id,$teacher_id,$comment_text);#Comment on ass. submission

}

#DECLARES WHAT ASSIGNMENT FUNCTIONS THE TEACHER MUST IMPLEMENT
interface TeacherAssignmentFunctions extends TeacherCommentFunctions
{
    //Create an assignment
    public function CreateAssignment($args=array(
            "teacher_id"=>0,
            "ass_title"=>"",
            "ass_description"=>"",
            "class_id"=>0,
            "submission_text"=>"",
            "due_date"=>"",
            "attachments"=>"",
            "file_option"=>"view",
            "max_grade"=>100,
            "comments_enabled"=>true,
            "sent"=>true)
    );
    
    public function SendAssignment($ass_id);#Send assignment to classroom
    
    public function ReturnAssignment($submission_id);#Return assignment - teacher cannot edit/comment anymore, assignment has been returned to student
    
    public function GradeAssignment($submission_id,$grade);#Grade assignment

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
    public function CreateTeacher($data)
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

        #if the teacher details are set (form data filled,phone can be left blank), create account
        if (Validator::TeacherSignupValid($data))
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
    }

    //Create/send an Assignment
    public function CreateAssignment($args=array(
            "teacher_id"=>0,
            "ass_title"=>"",
            "ass_description"=>"",
            "class_id"=>0,
            "submission_text"=>"",
            "due_date"=>"",
            "attachments"=>"",
            "file_option"=>"view",
            "max_grade"=>100,
            "comments_enabled"=>true,
            "sent"=>true)
    )
    {
        global $dbCon;#database connection

        $insert_query = "INSERT INTO assignments(teacher_id,ass_title,ass_description,class_id,submission_text,due_date,attachments,file_option,max_grade,comments_enabled,sent) VALUES(?,?,?,?,?,?,?,?,?,?)";

        if($insert_stmt = $dbCon->prepare($insert_query))
        {
            $insert_stmt->bind_param("isssssssisi",
                $args["teacher_id"],
                $args["ass_title"],
                $args["ass_description"],
                $args["class_id"],
                $args["submission_text"],
                $args["due_date"],
                $args["attachments"],
                $args["file_option"],
                $args["max_grade"],
                $args["comments_enabled"],
                $args["sent"]
            );
            if($insert_stmt->execute())
            {
                return 'true';#successfully created assignment
            }
            else
            {
                return 'false';#failed to send the assignment
            }
        }
        else
        {
            echo $dbCon->error;
            return 'null';#failed to prepare the query
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
        return CommentHandler::CommentOnAssSubmission($submission_id,$teacher_id,$comment_text,"teacher");
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
                $update_stmt->bind_param("ii",$grade,$submission_id);
                return($update_stmt->execute());
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
                return($update_stmt->execute());
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
                    return($update_stmt->execute());
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
};#END OF CLASS

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_POST['action'])) {
    
    $Teacher = new Teacher();
    
    switch($_POST['action']) {
        case 'CreateAssignment':
            
            $args = array(
                'teacher_id' => $_SESSION['admin_acc_id'],
                'ass_title' => $_POST['assignmenttitle'],
                'ass_description' => $_POST['assignmentdescription'],
                'due_date' => $_POST['duedate'],
                'attachments' => $_POST['attachments'],
                'max_grade' => $_POST['maxgrade'],
                'comments_enabled' => $_POST['cancomment']
            );
            
            if(isset($_POST['class_ids'])) {
                
                $args['class_id'] = $_POST['classids'];
                
            } else {
                
                $args['class_id'] = 0;
                
            }
            
            $args['submission_text'] = '';
            
            $result = $Teacher->CreateAssignment($args['submission_text'],$args['teacher_id'],$args['ass_title'],$args['ass_description'],$args['class_id'],$args['due_date'],$args['attachments'],$args['max_grade'],$args['comments_enabled']);
            
            echo $result;
            
            break;
        case 'RemoveStudent':
            
            
            //dddd
            break;
        case 'DeleteClassRoom':
          
            $class_id = $_POST['classroomid'];
            
            if(!isset($class_id)) {
                
                $result = Assignment::DeleteClassRoom($class_id);
            
                echo $result;
                
            }
            
            //dddd
            break;
        case 'ReturnAssSubmission'://Return assignment submission
            $teacher = new Teacher();
            
            //Getting the submission information
            $data = &$_POST["submission_data"];
            $submission_id = $data["submission_id"];
            $grade = $data["grade"];
            
            $grade_status = $teacher->GradeAssignment($submission_id,$grade);#grade the assignment 
            $return_status = $teacher->ReturnAssignment($submission_id);#return the assignment to the Student

            $final_status=array("grade_status"=>$grade_status,"return_status"=>$return_status);
            echo (json_encode($final_status));
        default:
            return null;
            break;
    }

} else {
    return null;
}
