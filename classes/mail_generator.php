<?php
/*Requires*/
require_once(realpath(dirname(__FILE__) . "/../handlers/date_handler.php"));

/*THIS CLASS GENERATES EMAILS AND RETURNS THE EMAIL INFORMATION*/
class EsomoMailGenerator
{
    /*Constants*/

    const DEV_EMAILS = "aj.dev254@gmail.com,gramwauu@gmail.com";
    const ACCOUNTS_CONTACT_EMAIL = "account@rain.co.ke";#TODO: Change this to a valid email
    const NO_REPLY_EMAIL = "noreply@rain.co.ke";

    /*NEW ACCOUNT EMAILS*/
    //New account created
    private static  function NewAccountEmail($first_name,$last_name,$username,$acc_type,$email_to="",$cc="",$bcc="")
    {
        $today = EsomoDate::GetCurrentDate();
        $today = EsomoDate::GetOptimalDateText($today);

        $subject = "Your E-Learning account has been successfully created";
        $message = "<h3>Your E-Learning account has been successfully created</h3><p>Dear $first_name $last_name, your $acc_type account has been successfully been created.</p><p>To login to your account, use this information <br>username: $username <br>password: $password</p><p>Remember to change your password in the account section as soon as you login to ensure security of your account</p><b><b>Note: Do not share your password with anyone</b></p><br><p>If you believe that this account creation was a mistake, feel free to contact us at ".self::SUPERUSER_CONTACT_EMAIL." with the subject of the email being 'WRONG ACCOUNT CREATION'</p><p>Do not reply to this message. <i>This message was automatically generated on $today</i></p>";

        //Consider sending mail from within this function
        $mail_data = array(
            "subject"=>$subject,
            "message"=>$message,
            "to"=>$email_to,
            "from"=>self::NO_REPLY_EMAIL,
            "cc"=>$cc,
            "bcc"=>$bcc
        );

        return $mail_data;
    }

    //New Superuser account created
    public static function NewSuperuserAccEmail($first_name,$last_name,$username,$email_to="",$cc="",$bcc="")
    {
        $mail_generated = self::NewAccountEmail($first_name,$last_name,$username,"superuser",$email_to,$cc,$bcc);
        return $mail_generated;
    }

    //New Principal account created
    public static function NewPrincipalAccEmail($first_name,$last_name,$username,$email_to="",$cc="",$bcc="")
    {
        $mail_generated = self::NewAccountEmail($first_name,$last_name,$username,"principal",$email_to,$cc,$bcc);
        return $mail_generated;
    }

    //New Teacher account created
    public static function NewTeacherAccEmail($first_name,$last_name,$username,$email_to="",$cc="",$bcc="")
    {
        $mail_generated = self::NewAccountEmail($first_name,$last_name,$username,"teacher",$email_to,$cc,$bcc);
        return $mail_generated;
    }

    //New Student account created
    public static function NewStudentAccEmail($first_name,$last_name,$username,$email_to="",$cc="",$bcc="")
    {
        $mail_generated = self::NewAccountEmail($first_name,$last_name,$username,"student",$email_to,$cc,$bcc);
        return $mail_generated;
    }

    //Report problem email
    public static function ReportProblemEmail($data,$user_info)
    {
        $today = EsomoDate::GetCurrentDate();
        $today = EsomoDate::GetOptimalDateText($today);
        
        #Sanitize all inputs to prevent XSS attacks (htmlspecialchars)
        //Section 
        $report_section = htmlspecialchars($data["report_section"]);

        //Specific
        $specific_section = "[Not provided]";
        if(!empty($data["report_specific"]))
        {
            $specific_section = htmlspecialchars($data["report_specific"]);
        }

        //Details of the problem
        $problem_details = "[Not provided]";
        if(!empty($data["report_message"]))
        {
            $problem_details = htmlspecialchars($data["report_message"]);
        }

        $subject = "Rain E-Learning | Problem report";
        $message = "<h4>Problem reported by ".$user_info["full_name"]."(Account type : ".$user_info["account_type"].") on ".$today."</h4>";
        $message .= "<p><b>Section : </b>".$report_section."</p>";
        $message .= "<p><b>Specifically : </b>".$specific_section."</p>";
        $message .= "<p><b>Details </b><br>".ucfirst($problem_details)."<p>";

        $non_html_message = "\nProblem reported by ".$user_info["full_name"]." on ".$today."";
        $non_html_message .= "\nSection : ".$report_section;
        $non_html_message .= "\nSpecifically : ".$specific_section;
        $non_html_message .= "\nDetails \n".ucfirst($problem_details);

        $email_data = array(
            "from"=>self::DEV_EMAILS,
            "to"=>self::DEV_EMAILS,
            "subject"=>$subject,
            "message"=>$message,
            "alt_message"=>$non_html_message,
            "cc"=>self::DEV_EMAILS,
            "bcc"=>self::DEV_EMAILS
        );

        #Return the email data ~ can be used by phpmailer
        return $email_data;
    }
}