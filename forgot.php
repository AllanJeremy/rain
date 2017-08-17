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
      //Include the session functions file once
        require_once (realpath(dirname(__FILE__) . "/handlers/session_handler.php")); #Allows connection to database

      $content = <<<EOD

        <br><div class="row container marg-vert-16"><br>
        <div class="col s12 m6 offset-m3 z-depth-4 forgot-form-bar white pad-16">
          <h3 class="grey-text text-darken-3 bold">Recover your password</h3>
          <br>
          <form class='form-inline' action="handlers/forgot_handler.php" method="post" id="recoveryForm">
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
EOD;
        $redirectPath = 'index.php';

        if (!(MySessionHandler::AdminIsLoggedIn() || MySessionHandler::StudentIsLoggedIn())) {
            echo $content;

        } else {
            header("Location:".$redirectPath);
        }

      ?>

        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>
        $(document).ready(function() {
            $('select').material_select();

            //Ensure labels don't overlap text fields
            Materialize.updateTextFields(); //doesn't work

            $('form#recoveryForm').submit(function (e) {
                e.preventDefault();
                console.log('stopped');

                var $this = $(this),
                    acc_type = document.forms['recoveryForm']['acc_type'].value,
                    email = document.forms['recoveryForm']['recoverEmailInput'].value,
                    $resultDiv = $('.js-form-results#resultDiv'),
                    formData = new FormData(),
                    dataHook = $resultDiv.find('.js-data-hook'),
                    resultText = {
                        1:'We have sent you a link to your email. Use it to reset your password',
                        2:'Oops! Slight error.<br> We found that a similar request was made within 24 hours. Please try again later.',
                        3:'Oh no.<br> We cannot find an account under ' + acc_type + ' accounts that matches the email <i class="php-data">' + email + '</i>',
                        4:'Our fault.<br> We have a database error. Kindly try again or report',
                        5:'Bad email input. Try again',
                        6:'You did not submit any email address',
                        7:'Sorry your form was not submitted',
                        8:'Sorry there seems to be an error in sending emails<br>Check your internet connection then try again',
                        10:'Sorry there seems to be an error in sending emails<br>Check your internet connection<br>You will have to wait for at least 24 hours before you attempt to change it',
                        11:'You will have to wait for at least 24 hours before you attempt to change it',
                        'okay':'<a class="btn margin-vert-16" href="login.php">okay</a>',//button for redirecting the user to the login page
                        'close':'<a class="btn-flat margin-vert-16 js-close-form-results" href="javascript:void(0)">close</a>'//button for cancelling the result div for the user to retry
                    };

                formData.append('acc_type', acc_type);
                formData.append('submit', '');
                formData.append('recoverEmailInput', email);

                if(email !== '') {
                    //ajax post
                    $.ajax({

                        url: "handlers/forgot_handler.php",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        beforeSend : function () {
                            //Make the loader visible
                            $resultDiv.addClass('active');
                        },
                        success: function (result) {
                            jQuery.parseJSON(result);
                            result = jQuery.parseJSON(result);

                            console.log(result);
                            setTimeout(function () {

                                $resultDiv.find('.preloader-wrapper').hide(400, function () {
                                    dataHook.append('<h5 class="white-text">' + resultText[result['status']] + '</h5>');

                                    switch(result['status']) {
                                        case 1:
                                            dataHook.append(resultText['okay']);
                                            break;
                                        case 2:
                                            $resultDiv.addClass('failed');
                                            dataHook.append(resultText['okay']);
                                            break;
                                        case 3:case 4:case 5:case 6:case 7:case 8:
                                            $resultDiv.addClass('failed');
                                            dataHook.append(resultText['close']);
                                            break;
                                        case 10:
                                            dataHook.append(resultText['close']);
                                            break;
                                        default:
                                            $resultDiv.addClass('failed');
                                            dataHook.append(resultText['close']);
                                            break;
                                    }
                                });
                            }, 1200);
                        },
                        error: function (result) {
                            console.log(result);

                            $resultDiv.addClass('failed');
                            dataHook.append('<h5 class="white-text">' + resultText[result['status']] + '</h5>')
                        }
                    }, 'json');

                console.log(acc_type, email);
                }
            });

            $('body').on('click', 'a.js-close-form-results', function (e) {
                e.preventDefault();
                console.log('Removing data from it');
                var $resultsContainer = $(this).parents('.js-form-results#resultDiv'),
                    $dataHookContainer = $resultsContainer.find('.js-data-hook'),
                    $spinner = $resultsContainer.find('.preloader-wrapper');

                $spinner.show(300, function () {
                    $resultsContainer.removeClass('active failed');

                });
                $dataHookContainer.html('');

            });

        });

        </script>
    </body>
</html>

  <!--If the email is in the database
    generate a custom password and expiry date
    store password in database
  -->
