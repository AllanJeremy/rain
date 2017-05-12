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

if(isset($_GET['action']))
{
    if($_GET['action']=="reset")
    {
        require_once('../esomoDbConnect.php');
        $encrypt = mysqli_real_escape_string($dbCon,$_GET['enc']);
        //echo $encrypt;

        $query = "SELECT recover_id,temp_password, acc_email, date_created FROM recovery where token=?";
        if($stmt= $dbCon->prepare($query))
        {
            $stmt->bind_param('s',$encrypt);
            $stmt->execute();//if the statement successfully ran
            $result = $stmt->get_result();

            $rowCount = mysqli_num_rows($result);
            if ($rowCount==1)
            {
                foreach ($result as $item) {

                    //student information variables from database
                    $tmp_recover_id = @$item['recover_id'];
                    $tmp_acc_email = @$item['acc_email'];
                    $tmp_date_created = @$item['date_created'];
                    $tmp_password = @$item['temp_password'];
                }
                //$emailheader = md5(1290*3+$tmp_acc_email);
                //$emailheader = password_hash($tmp_acc_email);
                //header('../reset.php');
                $content = '
                <div class="container-fluid">
                <br><h2 class="" style="width:85%;padding-left:15%;">Set a new Password</h2><br><br>
                <form class="form-horizontal" method="post" action="resetPassword.php?id='.$tmp_recover_id.'" role="form" id="resetPassForm">

                <!--New Password Input-->
                <div class="form-group col-xs-12">
                  <label class="control-label col-sm-3 hidden-xs" for="newPass" >New Password</label>
                  <div class="col-sm-6">
                    <input class="form-control required" type="password" name="newPass" id="newPass" placeholder="Type your new password" minlength="8">
                  </div>
                </div>

                <!--New Password Input-->
                <div class="form-group col-xs-12">
                  <label class="control-label col-sm-3 hidden-xs" for="newPassConfirm">Confirm password</label>
                  <div class="col-sm-6">
                    <input class="form-control required" type="password" name="newPassConfirm" id="PassConfirm" placeholder="Confirm new password" minlength="8">
                  </div>
                </div>
                <br>
                <br>
                <button type="submit" class="col-xs-offset-5 btn btn-default action-color " id="new-pass-btn" name="submit">RECOVER</button>
                </form>
                </div>';


                //determine if 'token' has expired by comparing dates
                $today = date("Y-m-d h:m:s");
                $date1=date_create($tmp_date_created);
                $date2=date_create($today);
                $diff=date_diff($date1,$date2);

                if($diff->format("%R%a") == '+0') {
                    echo $content;
                    //header('location: resetPassword.php?acc_email='.$emailheader);
                    //exit;
                } else {
                    echo '<h1>token expired</h1>';
                }


            } else {
                echo "<h4>error in the link provided.<br>probably link expired</h4>";
            }

        }

    }
}
else {
    echo 'nothing set';
}

?>


   <script src="../js/jquery.min.js"></script>
   <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.validate.min.js"></script>

    <script type="text/javascript">
        $( "#resetPassForm" ).validate({
          rules: {
            newPass: "required",
            newPassConfirm: {
              equalTo: "#newPass"
            }
          }
        });

    </script>
        </body>
</html>
