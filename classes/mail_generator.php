<?php
/*Requires*/
require_once(realpath(dirname(__FILE__) . "/../handlers/date_handler.php"));

/*THIS CLASS GENERATES EMAILS AND RETURNS THE EMAIL INFORMATION*/
class EsomoMailGenerator
{
    /*Constants*/
    const ACCOUNTS_CONTACT_EMAIL = "account@brookhurst.com";#TODO: Change this to a valid email
    const NO_REPLY_EMAIL = "noreply@brookhurst.com";

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
}