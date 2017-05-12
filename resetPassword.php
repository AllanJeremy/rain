<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Reset password</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link  rel="stylesheet" type="text/css" href="../css/theme.min.css"/>
       <link  rel="stylesheet" type="text/css" href="../css/main.css"/>
    </head>

    <body>
    <?php
const SALT_LENGTH = 128;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['newPassConfirm']))
    {
        if(isset($_GET['id']))
        {
            require_once('../esomoDbConnect.php');

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
                            echo $tmp_acc_email;
                            setNewPass($tmp_acc_email, $recover_id);
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
            echo 'no id set';
        }

    }
    else
    {
        echo 'invalid';
    }
}


function setNewPass($tmp_acc_email, $recover_id) {


    $password = htmlspecialchars($_POST['newPassConfirm']);

    require('../functions/pass_encrypt.php');
    $passEncrypt = new PasswordEncrypt();

    $password = $passEncrypt->encryptPass($password);

    require('../esomoDbConnect.php');
    $query = "UPDATE accounts set password=? WHERE email=?";

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
    require('../esomoDbConnect.php');
    $query = "DELETE FROM recovery WHERE recover_id = ?";
    if($stmt= $dbCon->prepare($query))
    {
        $stmt->bind_param('i',$recover_id);
        if($stmt->execute())//if the statement successfully ran
        {
            echo '<div class="container brand-success col-xs-s12 col-sm-6 col-sm-offset-4"><h1 class="white-text col-xs-12 " style="margin-bottom:32px;margin-top:102px;">Password set successfully</h1><br><br><a class="btn btn-default  col-xs-12 col-sm-8" href="login.php">Go to home page to login with your new password</a></div>';
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


   <script src="../js/jquery.min.js"></script>
   <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.validate.min.js"></script>

    <script type="text/javascript">
        $('#resetPassForm').validate();

    </script>
        </body>
</html>
