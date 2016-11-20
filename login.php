<!DOCTYPE html>

<html lang="en" class="login-bg">
    <head>
        <title>Esomo2</title>
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
            <br>
            <div class="row">
                <div class="col s12 m6 offset-m3">
                <ul class="tabs tabs-transparent login-tabs">
                    <li class="tab left-tab col s6">
                        <a class="active" href="#studentLogin">Students</a>
                    </li>
                    <li class="tab right-tab col s6">
                        <a  href="#staffLogin" >Staff</a>
                    </li>
                </ul>
                </div>
                <div id="studentLogin" class="col s12 offset-m3 m6 ">
                    <div class="row">
                        <br>
                        <br>
                        <br>
                        <form class="col s12" method="post" action="">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="username" type="text" class="validate" name="student_username">
                                    <label for="student_username">Username</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate" name="student_password">
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <a class="mini-link" href="forgot.php">Forgot my password</a>
                                </div>
                                <div class="input-field col s6">
                                    <a class="right btn" type="submit" >LOGIN</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="staffLogin" class="col s12 offset-m3 m6 ">
                    <div class="row">
                        <br>
                        <br>
                        <br>
                        <form class="col s12" method="get" action="">
                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="staff_acc_type">
                                        <option value="teacher" selected>Teacher</option>
                                        <option value="principal">Principal</option>
                                        <option value="superuser">Superuser</option>
                                    </select>
                                    <label>Account type</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="username" type="text" class="validate" name="staff_username">
                                    <label for="staff_username">Username</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate" name="staff_password">
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <a class="mini-link" href="staff_account_request.php">Request an account</a>
                                    <br>
                                    <a class="mini-link" href="forgot.php">Forgot my password</a>
                                    
                                </div>
                                <div class="input-field col s6">
                                    <button class="right btn" type="submit" >LOGIN</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php include_once("classes/superuser.php");
                $super = new Superuser();
                
             ?>
        </main>
        <footer>
        </footer>
        
        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/materialize.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
        <script>
        $(document).ready(function() {
            $('select').material_select();
        });
        </script>
    </body>
</html>