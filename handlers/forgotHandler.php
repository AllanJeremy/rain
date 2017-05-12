<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Connection to the database
require_once (realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Allows connection to database

function validateEmail() {

    global $dbCon;
    var_dump($_SESSION);

    $acc_table = 'admin_account';

    $acc_type = $_POST['acc_type'];
    if ($acc_type == 'student') {
        $acc_table = 'student_account';
    }


    $query = 'SELECT * FROM '.$acc_table.' WHERE  email =?';

    $tmp_email = $_POST['recoverEmailInput'];
    if ($stmt = $dbCon->prepare($query)) {
        $stmt->bind_param('s',$tmp_email);

        $stmt->execute();

        $result = $stmt->get_result();

        $rowCount = mysqli_num_rows($result);
        echo 'hhh';
        //if there exists such an email
        if ($rowCount>0) {//email exists, check for validity

        echo 'exists';
            foreach ($result as $item) {

                //student information variables from database
                $tmp_acc_id = $item['acc_id'];
                $tmp_acc_type = $acc_type;
                $tmp_first_name = $item['first_name'];
                $tmp_password = $item['password'];
            }
            $encrypt = md5(1290*3+$tmp_stud_id);
            $tmp_token = $encrypt;
            $tmp_link = 'http://localhost/gits/esomo-upgrade/reset.php?enc='.$encrypt.'&action=reset';
            $tmp_token_destroy = 'TOKEN DESTROYED';
            $email_from = 'idfinder254@gmail.com';//<== update the email address
            $email_subject = "New Form submission";
            $to = $tmp_email;



            $query = "SELECT * FROM recovery WHERE acc_email=?";
            if ($stmt = $dbCon->prepare($query))
            {
                $stmt->bind_param('s',$tmp_email);
                $stmt->execute();
                $result = $stmt->get_result();

                $rowCount = mysqli_num_rows($result);

                if ($rowCount>0)
                {

                    echo "sorry, little error. wait for 24 hours before you try again.";
                } else {
                    require_once('class.smtp.php');
                    require_once('class.phpmailer.php');
                    $query = "INSERT INTO recovery(token,temp_password,acc_email,acc_type) VALUES (?,?,?,?)";
                    if($stmt = $dbCon->prepare($query))
                    {
                        $stmt->bind_param('ssss',$tmp_token,$tmp_password,$tmp_email,$tmp_acc_type);
                        $stmt->execute();


                        $mail = new PHPMailer;
                        $mail->isSMTP();
                        $mail->SMTPDebug = 2;
                        $mail->Debugoutput = 'html';
                        $mail->Host = 'ssl://smtp.gmail.com';
                        $mail->Port = 465;
                        $mail->SMTPSecure = 'ssl';
                        $mail->SMTPAuth = true;
                        $mail->Username = "idfinder254@gmail.com";
                        $mail->Password = "MWAURAMUCHIRI";
                        $mail->setFrom($email_from, 'Brookhurst eLearning bot');
                        $mail->addAddress($to, $tmp_first_name);
                        $mail->Subject = "Hey ".$tmp_first_name.", student id ".$tmp_stud_id."\n";
                        $mail->Body    = 'We received a request to change password.<br>Click this link : '.$tmp_link;

                        if (!$mail->send()) {
                            echo "Mailer Error: " . $mail->ErrorInfo;
                        } else {
                            echo "Message sent!";
                            header('location: sent.php');
                            exit;
                        }
                    } else {
                        echo "db error";

                    }
                }
            }
        } else {
            $loginRedirectPath = 'forgot.php';
            header('Location: '.$loginRedirectPath);
            //include('invalidEmail.php');
        }
    }
}

function sendMailMessage() {
    echo '<p>An email has been sent to the mail you entered</p>';
}


// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}

if(!isset($_POST['submit'])) {
	//This page should not be accessed directly. Need to submit the form.
	echo "You can't access this page";
	echo "<br><br>";
	echo "error; you need to submit the form!";
} elseif (IsInjected($_POST['recoverEmailInput'])) {
    echo "Bad email value!";
    exit;
} elseif ($_POST['recoverEmailInput'] === '') {
    $loginRedirectPath = 'forgot.php';
    header('Location: '.$loginRedirectPath.'');
    //include('invalidEmail.php');

} else {
    validateEmail();
}


?>
