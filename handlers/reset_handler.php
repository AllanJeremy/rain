<?php
const SALT_LENGTH = 128;
if(!isset($_POST['submit'])) {
	//This page should not be accessed directly. Need to submit the form.
    $loginRedirectPath = 'reset.php';
    header('Location: '.$loginRedirectPath.'');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['newPassConfirm']))
    {
        if(isset($_GET['id']) && isset($_GET['acc_type']))
        {
            require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Connection to the database

            $recover_id = $_GET['id'];
            $query = "SELECT acc_email from recovery WHERE recover_id=?";

            if($stmt= $dbCon->prepare($query))
            {
                $stmt->bind_param('i',$recover_id);


                if($stmt->execute())//if the statement successfully ran
                {
                    $result = $stmt->get_result();
                    $rowCount = mysqli_num_rows($result);
                    if ($rowCount==1)
                    {
                        foreach ($result as $item) {

                            $tmp_acc_email = $item['acc_email'];
                            $acc_type = $_GET['acc_type'];

                            //echo $tmp_acc_email;
                            //echo $acc_type;
                            if ($_POST['email'] == $tmp_acc_email) {
                                //echo 'bingo';
                                setNewPass($tmp_acc_email, $recover_id, $acc_type);
                            } else {
                                echo 'The recovery token was not assigned to this email';
                            }
                        }

                    }
                    else
                    {
                        echo 'error in query';
                    }
                }
                else
                {
                    echo 'error in preparing the query';
                }
            }
        }
        else
        {
            echo 'no id or account type set';
        }

    }
    else
    {
        echo 'invalid';
    }
}


function setNewPass($tmp_acc_email, $recover_id,$acc_type) {


    $password = htmlspecialchars($_POST['newPassConfirm']);

    require_once(realpath(dirname(__FILE__) . "/../handlers/pass_encrypt.php"));

    $passEncrypt = new PasswordEncrypt();

    $password = $passEncrypt->encryptPass($password);

    require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php")); #Connection to the database

    global $dbCon;
    $acc_table = 'student_accounts';

    if ($acc_type != 'student') {
        $acc_table = 'admin_accounts';
    }

    $query = "UPDATE ".$acc_table." set password=? WHERE email=?";

    if($stmt= $dbCon->prepare($query))
    {
        $stmt->bind_param('ss',$password,$tmp_acc_email);


        if($stmt->execute())//if the statement successfully ran
        {
            $changes = $stmt->affected_rows;
            if($changes = '1') {
                killToken($recover_id);
            }

        }
        else {
            echo 'error in query';
        }
    }
    else {
        echo 'error in preparing the query';

    }
}

function killToken($recover_id) {
    require_once(realpath(dirname(__FILE__) . "/../handlers/db_connect.php"));

    global $dbCon;

    $query = "DELETE FROM recovery WHERE recover_id = ?";
    if($stmt= $dbCon->prepare($query))
    {
        $stmt->bind_param('i',$recover_id);
        if($stmt->execute())//if the statement successfully ran
        {
            echo '<div class="container brand-success col-xs-s12 col-sm-6 col-sm-offset-4"><h1 class="white-text col-xs-12 " style="margin-bottom:32px;margin-top:102px;">Password set successfully</h1><br><br><a class="btn btn-default  col-xs-12 col-sm-8" href="../login.php">Go to home page to login with your new password</a></div>';
        }
        else {
            echo 'error in query';
        }
    }
    else {
        echo 'errror in kill token query prepare';
    }
}
    ?>
