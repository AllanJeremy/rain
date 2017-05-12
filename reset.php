<!DOCTYPE html>

<html lang="en" class="login-bg">
    <head>
        <title>Password recovery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
<!--        <link rel="stylesheet" type="text/css" href="stylesheets/pace-theme-flash.css"/>-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body class="">
<?php
                $content_expired_token = <<<EOD
                <br><br><br><div class="row container marg-vert-16"><br>
        <div class="col s12 m6 offset-m3 z-depth-4 forgot-form-bar white pad-16">
        <div class="js-form-results form-results active failed" id="resultDiv">
                  <br>
                  <h3 class="grey-text text-lighten-3 bold">Token expired.</h3>
                  <br>
                  <br>
                  <div class="js-data-hook">
                </div>
                </div>
                </div>
        </div>
EOD;

if(isset($_GET['action']))
{
    if($_GET['action']=="reset")
    {
        require_once(realpath(dirname(__FILE__) . "/handlers/db_connect.php")); #Connection to the database
        $encrypt = mysqli_real_escape_string($dbCon,$_GET['enc']);
        //echo $encrypt;

        $query = "SELECT recover_id,temp_password, acc_email, date_created, acc_type FROM recovery where token=?";
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
                    $tmp_accType = @$item['acc_type'];
                }
                //$emailheader = md5(1290*3+$tmp_acc_email);
                //$emailheader = password_hash($tmp_acc_email);
                //header('../reset.php');
                //echo $tmp_recover_id;
                $content = <<<EOD

                <div class="row container marg-vert-16"><br>
        <div class="col s12 m6 offset-m3 z-depth-4 forgot-form-bar white pad-16">
          <h3 class="grey-text text-darken-3 bold">Set your new password here</h3>
          <br>
          <form method="post" action="handlers/reset_handler.php?acc_type=$tmp_accType&id=$tmp_recover_id" role="form" id="resetPassForm">

                <!--email address Input-->
                <div class="input-field col s12">
                  <label class="" for="newPass" >Email address</label>
                  <input class="required" type="email" name="email" id="resetEmail" placeholder="email address" minlength="8" class="validate grey-text text-darken-2" pattern="[a-zA-Z0-9_]+(?:\.[A-Za-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*@(?!([a-zA-Z0-9]*\.[a-zA-Z0-9]*\.[a-zA-Z0-9]*\.))(?:[A-Za-z0-9](?:[a-zA-Z0-9-]*[A-Za-z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?" required>
                </div>
                <!--New Password Input-->
                <div class="input-field col s12">
                  <label class=" " for="newPass" >New Password</label>
                  <input class="required grey-text text-darken-2" type="password" name="newPass" id="newPass" placeholder="Type your new password" minlength="8" required>
                </div>

                <!--New Password Input-->
                <div class="input-field col s12">
                    <label class="" for="newPassConfirm">Confirm password</label>
                    <input class=" required grey-text text-darken-2" type="password" name="newPassConfirm" id="newPassConfirm" placeholder="Confirm new password" minlength="8" required>
                </div>
                <br>
                <br>
                <button type="submit" class="btn" id="setNewPassBtn" name="submit">RESET</button>
                </form>
                  <br>
                <div class="js-form-results form-results" id="resultDiv">
                  <br>
                  <h3 class="grey-text text-lighten-3 bold">Recover your password here</h3>
                  <br>
                  <br>
                      <div class="preloader-wrapper  small">
                        <div class="spinner-layer spinner-form-results yellow-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div>
                            <div class="gap-patch">
                                <div class="circle"></div>
                            </div>
                            <div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                  <div class="js-data-hook">
                </div>
                </div>
                </div>
                </div>
EOD;


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
                    echo $content_expired_token;
                }


            } else {
                echo $content_expired_token;
            }

        }

    }
}
else {
    echo 'nothing set';
}

?>


   <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>
        $(document).ready(function() {

        });

    </script>
        </body>
</html>
