<!DOCTYPE html>

<html lang="en" class="login-bg">
    <head>
        <title>Password recovery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link rel="stylesheet" type="text/css" href="stylesheets/pace-theme-flash.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body class="">
        <style>
            .forgot-form-bar {
                border-bottom: 4px solid #05a8bd;
                border-radius: 2px;
            }
        </style>
      <?php
      //Include the session functions file once


      $content = <<<EOD

        <div class="row container marg-vert-16"><br>
        <div class="col s12 m6 offset-m3 z-depth-4 forgot-form-bar white pad-16">
          <h3 class="grey-text text-darken-3 bold">Recover your password here</h3>
          <br>
          <form class='form-inline' action="handlers/forgotHandler.php" method="post" id="recovery-form">
            <p class="grey-text text-darken-2"><b>Step 1: </b> Enter your email address and we will send you an email with details on recovering your password</p>
            <br>
            <div class="row">
                <div class="input-field col s12">
                    <select name="acc_type" required class='grey-text text-darken-2'>
                        <option value="student" selected>Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="principal">Principal</option>
                        <option value="superuser">Superuser</option>
                    </select>
                    <label>Account type</label>
                </div>
            </div>
            <div class="input-field">
	          <label for='recoverEmail' class='control-label'>Email Address : </label>
            <input type='email' name='recoverEmailInput' id='recoverEmail' placeholder='Email Address' class="validate grey-text text-darken-2" pattern="[a-zA-Z0-9_]+(?:\.[A-Za-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*@(?!([a-zA-Z0-9]*\.[a-zA-Z0-9]*\.[a-zA-Z0-9]*\.))(?:[A-Za-z0-9](?:[a-zA-Z0-9-]*[A-Za-z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?" required></input>
            </div>
            <button type='submit' class="btn btn-default action-color" id="recovery-btn" name="submit" >RECOVER</button>
          </form>
          <br>
          <br>
          <span class="grey-text text-darken-2">*When nothing happens, it means the email does not exist*</span>
        </div>
        </div>
EOD;
        echo $content;
/*
    $redirectPath = '../learn.php';#relative path from current location
      $sessionHandler = new SessionFunctions();#contains convenience session functions
        require_once('../esomoDbConnect.php');
      #redirects user if logged in
     $sessionHandler->redirectLoggedUser($content,$redirectPath);
*/


      ?>

        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>
        $(document).ready(function() {
            $('select').material_select();

            //Ensure labels don't overlap text fields
            Materialize.updateTextFields(); //doesn't work

        });

        </script>
    </body>
</html>

  <!--If the email is in the database
    generate a custom password and expiry date
    store password in database
  -->
