<?php

require_once("session_handler.php"); #To access session related information

#HANDLES ALL HEADER RELATED FUNCTIONS, FROM META TO TITLES
class MyHeaderHandler
{

    #Site header constants
    const SITE_TITLE = "Brookhurst E-Learning";
    const SITE_ENCODING = "UTF-8";
    const SITE_KEYWORDS = "Learn,E-learning,Education,Brookhurst,Kenya,Classroom,Teacher,Account,Import,Students,International"; #meta keywords used on the page
    
    const STUDENT_DESCRIPTION = "Students can join classroom,
Students cannot leave classroom without the teacher,
Student can receive assignment,
Student can do assignments in the browser,
Student can submit assignment";
    
    const TEACHER_DESCRIPTION = "Teacher can create classrooms and remove classrooms as well,
Teacher can add and remove students from classrooms,
Teacher can send assignments as well as receive assignments to classes,

Teacher can grade assignments and send assignment back to student,
Teacher can comment on assignment submissions in classroom,
Teacher can create tests which can be taken by any student.

Two types of tests : Open ended tests, Multiple choice (single answer and multiple answer questions),
Teacher can mark tests and send them back to students,
Teachers can plan classes and schedule classes,

Teachers can mark classes as attended or not attended by the end of the class";

    const PRINCIPAL_DESCRIPTION = "Principal can view statistics:
Teacher schedules with search functionality and time filters,
Teacher assignments,
All assignments";

    const SUPERUSER_DESCRIPTION = "Superuser can create teacher,principal and student accounts.
(Superuser) Import students database and create automatic accounts for each student.";

    const DEFAULT_DESCRIPTION = "Brookhurst's very own e-learning platform developed by Deflix Enterprises";

    #Meta variables
    private static $description = self::SITE_TITLE; #meta description used for the page
    private static $author;

    //Returns the title of the page - to use in <title> tag in <head>
    public static function GetPageTitle()
    {
        $page_title = "";

        if (MySessionHandler::StudentIsLoggedIn()) #student logged in
        {
            $page_title = self::SITE_TITLE . " | ". ucwords($_SESSION["student_username"]) . "'s Account";
            return $page_title;
        }
        else if(MySessionHandler::AdminIsLoggedIn()) #admin logged in
        {
            $page_title = self::SITE_TITLE . " | ". ucwords($_SESSION["admin_username"]) . "'s Account (". ucwords($_SESSION["admin_account_type"]).")";
            return $page_title;
        }
        #If no user is logged in and function is called simply return the site title
        return self::SITE_TITLE;
    }

    //Returns site meta data
    public static function GetMetaData()
    {
        if (MySessionHandler::StudentIsLoggedIn()) #student logged in
        {
            self::$author = $_SESSION["student_first_name"] . " " . $_SESSION["student_last_name"];
            self::$description .= " | " . self::STUDENT_DESCRIPTION; 
        }
        else if(MySessionHandler::AdminIsLoggedIn()) #admin logged in
        {
            self::$author = $_SESSION["admin_first_name"] . " " . $_SESSION["admin_last_name"];

            switch($_SESSION["admin_account_type"])
            {
                case "teacher":
                    self::$description .= " | " . self::TEACHER_DESCRIPTION;
                break;

                case "principal":
                    self::$description .= " | " . self::PRINCIPAL_DESCRIPTION;
                break;
               
                case "superuser":
                    self::$description .= " | " . self::SUPERUSER_DESCRIPTION;
                break;

                default:
                    self::$description .= " | " . self::DEFAULT_DESCRIPTION;
            }
        }
        else
        {
            self::$author = "Deflix Enterprises";
            self::$description .= " | " . self::DEFAULT_DESCRIPTION;
        }
?>
    <meta charset="<?php echo self::SITE_ENCODING; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <meta name="keywords" content="<?php echo self::SITE_KEYWORDS ?>">
    <meta name="description" content="<?php echo self::$description; ?>">
    <meta name="author" content="<?php echo self::$author; ?>">
    
<?php
    }#end of GetMetaData()

};#end of class

?>