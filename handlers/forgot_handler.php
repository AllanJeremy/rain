<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Connection to the database
require_once (realpath(dirname(__FILE__) . "/../handlers/session_handler.php")); #Allows connection to database

function validateEmail() {

    global $dbCon;
    //var_dump($_SESSION);

    $acc_table = 'admin_accounts';

    $acc_type = $_POST['acc_type'];

    if ($acc_type == 'student') {
        $acc_table = 'student_accounts';
    }


    $query = 'SELECT * FROM '.$acc_table.' WHERE  email =?';

    $tmp_email = $_POST['recoverEmailInput'];
    if ($stmt = $dbCon->prepare($query)) {
        $stmt->bind_param('s',$tmp_email);

        $stmt->execute();

        $result = $stmt->get_result();

        $rowCount = mysqli_num_rows($result);

        //if there exists such an email
        if ($rowCount>0) {//email exists, check for validity


            foreach ($result as $item) {

                //account information variables from database
                $tmp_acc_id = $item['acc_id'];
                $tmp_acc_type = $acc_type;
                $tmp_first_name = $item['first_name'];
                $tmp_password = $item['password'];
            }
            $encrypt = md5(uniqid(1290*3+$tmp_acc_id));

            //var_dump($encrypt);

            $tmp_token = $encrypt;
            $tmp_link = 'http://localhost/gits/esomo-upgrade/reset.php?enc='.$encrypt.'&action=reset';
            $tmp_token_destroy = 'TOKEN DESTROYED';
            $email_from = 'idfinder254@gmail.com';//<== update the email address
            $email_subject = "Brookhurst e-Learning helper";
            $to = $tmp_email;



            $query = "SELECT * FROM recovery WHERE acc_email=? and acc_type=?";
            if ($stmt = $dbCon->prepare($query))
            {
                $stmt->bind_param('ss',$tmp_email,$acc_type);
                $stmt->execute();
                $result = $stmt->get_result();

                $rowCount = mysqli_num_rows($result);

                if ($rowCount>0)
                {
                    /* AJAX STATUS
                    1 - message sent. Successful
                    2 - Existed in the database
                    3 - email does not exist in the accounts tables
                    4 - db error
                    5 - injected email
                    6 - empty email input
                    7 - not submitted
                    8 - mail not sent
                    */

                    echo json_encode(array('status'=>2));
                } else {
                    require_once(realpath(dirname(__FILE__) . "/../classes/class.smtp.php")); #Connection to the stmp for mail
                    require_once (realpath(dirname(__FILE__) . "/../classes/class.phpmailer.php")); #Allows connection to phpmailer

                    $query = "INSERT INTO recovery(token,temp_password,acc_email,acc_type) VALUES (?,?,?,?)";
                    if($stmt = $dbCon->prepare($query))
                    {
                        $stmt->bind_param('ssss',$tmp_token,$tmp_password,$tmp_email,$tmp_acc_type);
                        $stmt->execute();


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
                        $mail->setFrom($email_from, 'Brookhurst eLearning buddy');
                        $mail->addAddress($to, $tmp_first_name);
                        $mail->Subject = "Hey ".$tmp_first_name."\n";
                        $mail->Body    = 'We received a request to change your password for your Brookhurst account. Click this link : '.$tmp_link;
                        $mail->IsHTML(true);

                        if (!$mail->send()) {
                            //echo "Mailer Error: " . $mail->ErrorInfo;
//                            echo json_encode(array('status'=>8));
                            removeRecoveryData($dbCon,$tmp_email,$tmp_acc_type);
                        } else {
                            echo json_encode(array('status'=>1));
                            //header('location: sent.php');
                            exit;
                        }
                    } else {
                    echo json_encode(array('status'=>4));

                    }
                }
            }
        } else {
            echo json_encode(array('status'=>3));

        }
    }
}

function removeRecoveryData($dbCon, $email,$accid) {

    $delete_query = 'DELETE FROM recovery WHERE acc_email=? AND acc_type=?';
    if($stmt = $dbCon->prepare($delete_query))
    {
        $stmt->bind_param('ss',$email,$accid);
        $stmt->execute();

        echo json_encode(array('status'=>8));
    } else {
        echo json_encode(array('status'=>10));

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
    echo json_encode(array('status'=>7));
} elseif (IsInjected($_POST['recoverEmailInput'])) {
    echo json_encode(array('status'=>5));
    exit;
} elseif ($_POST['recoverEmailInput'] === '') {
    echo json_encode(array('status'=>6));
    $loginRedirectPath = 'forgot.php';
    header('Location: '.$loginRedirectPath.'');
    //include('invalidEmail.php');

} else {
    validateEmail();
}


?>
