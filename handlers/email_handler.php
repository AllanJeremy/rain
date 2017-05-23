<?php
require_once(realpath(dirname(__FILE__) . "/../classes/class.smtp.php")); #Connection to the stmp for mail
require_once (realpath(dirname(__FILE__) . "/../classes/class.phpmailer.php")); #Allows connection to phpmailer
require_once (realpath(dirname(__FILE__) . "/../classes/class.phpmailer.php")); #Allows connection to phpmailer

class EmailHandler
{

    //Convenience - send email using phpMailer
    protected static function EsomoSendEmail($data)
    {


        //$ass_id = htmlspecialchars($ass_id);
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
        $mail->setFrom($data['from'], 'Brookhurst eLearning buddy');
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

    #Student comment on assignment
    public static function SendPasswordRecoveryEmail($email_data)
    {

        return self::EsomoSendEmail($email_data);
    }

    #Student comment on assignment
    public static function SendInstallationDetails($email_data)
    {

        return self::EsomoSendEmail($email_data);
    }

};

/*
-----------------------------
---------------    AJAX CALLS
-----------------------------
*/
