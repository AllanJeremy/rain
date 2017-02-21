<!DOCTYPE html>

<html lang="en" class="login-bg">
    <head>
        <title>Staff Account Request</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>

    <body>
        <header>
            
            <?php
            
            //Account type - from session variable storing the account type of the currently logged in user
            $accType = "";
            
            ?>

        </header>
        <main>
            <br>
            <br>
            <br class="hide-on-small-and-down">
            <div class="row">
                <h5 class="center-align light white-text">REQUEST AN ACCOUNT</h5>
                <div id="staffRequestAccount" class="col s12 offset-m3 m6 ">
                    <div class="row">
                        <br>
                        <br>
                        <form class="col s12" method="post" action="">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" type="email" class="validate" name="email_address">
                                    <label for="email_address">E-mail address</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="firstName" type="text" class="validate" name="first_name">
                                    <label for="first_name">First name</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="lastName" type="text" class="validate" name="last_name">
                                    <label for="last_name">Last name</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="userName" type="text" class="validate" name="request_username">
                                    <label for="request_username">Username</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <a class="underline" href="login.php">Back to login</a>
                                </div>
                                <div class="input-field col s6">
                                    <a class="right btn" type="submit" >SEND REQUEST</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer>
        </footer>
        
        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/materialize.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
        <script>
        $(document).ready(function() {
            
        });
        </script>
    </body>
</html>