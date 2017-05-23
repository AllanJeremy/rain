<?php
require_once(realpath(dirname(__FILE__) . "/../classes/class.smtp.php")); #Connection to the stmp for mail
require_once (realpath(dirname(__FILE__) . "/../classes/class.phpmailer.php")); #Allows connection to phpmailer
require_once (realpath(dirname(__FILE__) . "/../classes/class.phpmailer.php")); #Allows connection to phpmailer
require_once (realpath(dirname(__FILE__) . "/../classes/mail_generator.php")); #Allows connection to phpmailer
require_once(realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Session related functions ~ eg. login info

class EmailHandler
{

    //Convenience - send email using phpMailer
    protected static function EsomoSendEmail($data)
    {
        //var_dump($data);

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = "idfinder254@gmail.com";
        $mail->Password = "MWAURAMUCHIRI";
        $mail->setFrom("idfinder254@gmail.com", 'Brookhurst eLearning buddy');
        $mail->addAddress($data['to'], $data['address_name']);
        $mail->Subject = $data['subject'];

        if($data['attachments'] != null) {
            //Provide file path and name of the attachments
            $mail->addAttachment($data['attachements']);
            //$mail->addAttachment("images/profile.png"); //Filename is optional
        }

        $mail->Body    = $data['message'];
        $mail->AltBody = $data['alt_message'];
        $mail->IsHTML(true);

        return $mail->send();

        exit;

    }

    #Send email to reset password
    public static function SendPasswordRecoveryEmail($email_data)
    {

        return self::EsomoSendEmail($email_data);
    }

    #Developers' notification on installation details
    public static function SendInstallationDetails($email_data)
    {

        return self::EsomoSendEmail($email_data);
    }

    #Email problem report to the developers
    public static function ReportProblem($email_data)
    {

        return self::EsomoSendEmail($email_data);
    }

};

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/

if(isset($_POST['action'])) {

    sleep(1);//Sleep for  ashort amount of time, to reduce odds of a DDOS working.

    $user_info = MySessionHandler::GetLoggedUserInfo();#store the logged in user info anytime an AJAX call is made

    switch($_POST['action']) {

        //Report problem
        case "ReportProblem":
            $data = $_POST["data"];

            #Ensure that required fields are set ~ if not, echo an error message and end execution of the section
            if (empty($data["report_section"]) || empty($data["report_message"]))
            {
                echo "0 ~ Invalid data provided";
                // return false;#Consider trying this incase the break does not break out of the case
                break;#Stop running the rest of the case code
            }

            $email_data = EsomoMailGenerator::ReportProblemEmail($data,$user_info);#TODO: Generate email here

            $result = EmailHandler::ReportProblem($email_data);
            //var_dump($result);

            if($result) {
                echo true;
            } else {
                echo false;
            }

        break;

        default:
            return null;
        break;

    };

}
