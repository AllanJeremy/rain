<!DOCTYPE html>

<html lang="en" >
    <head>
        <?php require_once("handlers/header_handler.php");?>

        <title>Report a problem | RAIN E-Learning</title>

        <!--Site metadata-->
        <?php MyHeaderHandler::GetMetaData(); ?>
        
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link  rel="stylesheet" type="text/css" href="stylesheets/pace-theme-flash.css"/>
<!--        <script src="js/head.min.js"></script>-->
        <script>
//            head.load('https://fonts.googleapis.com/icon?family=Material+Icons');
        </script>

        
    </head>

    <body class="grey lighten-5">

        <?php 
            require_once("handlers/session_handler.php");
            require_once("handlers/global_init_handler.php");
        ?>
        <main>
        <?php
            //Only display the contents of the page if there is a user logged in
            if(MySessionHandler::AdminIsLoggedIn() || MySessionHandler::StudentIsLoggedIn()):
        ?>
            <br>
        <div class="container">
            <div class="card-panel grey lighten-3">
                <h5 class="center grey-text text-darken-2">Hi. You can report any problems you found here</h5>
                <p class="center grey-text">
                     If you found a problem, we're looking forward to hearing about it from you. Your feedback goes a long way in helping us create a better system for you.
                </p>
            </div>

            <div class="card">
                <div class="card-content row">
                    <span class="card-title">Report a problem</span>
                    <div class="divider"></div><br>
                    <div class="col s12 m6 input-field" title="[Required] Specific section where the problem occurred">
                        <select id="report_section" required>
                            <option value="Classrooms">Classrooms</option>
                            <option value="Assignments">Assignments</option>
                            <option value="Schedules">Schedules</option>
                            <option value="Tests">Tests</option>
                            <option value="Resources">Resources</option>
                            <option value="Account">Account</option>
                            <option value="Other">Other</option>
                        </select>
                        <label for="report_section">Section problem occurred <sup>*</sup></label>
                    </div>
                    <div class="col s12 m6 input-field" title="[Optional] Specific section/subsection the problem occurred">
                        <input type="text" placeholder="Specifically..." id="report_specific">
                        <label for="report_specific">Specifically</label>
                    </div>

                    <div class="col s12 input-field" title="[Required] The details of the problem or any feedback you have regarding the problem">
                        <textarea class="materialize-textarea" required placeholder="Details on the problem you found" id="report_message"></textarea>
                        <label for="report_message">Problem details &amp feedback <sup>*</sup></label>
                    </div>
                    <div class="divider"></div>
                    
                    <div class="col s12 m6">
                        <small class="grey-text">Fields marked with * are <b>required fields</b> and must be filled before submitting the problem</small>
                    </div>
                    
                    <div class="col s12 m6">
                        <a href="javascript:void(0)" class="btn right" id="btn_send_problem_form">SEND</a>
                    </div>
                </div>

                <div class="card-action grey lighten-2">
                    <p class="center">If you believe you are here by accident. You can navigate <a href="index.php" class="blue-text">back to home</a></p>
                </div>
            </div>
        </div>
        <?php
            else:#No users logged in ~ redirect to the login page
        ?>
            <script>window.location = "login.php"</script>
        <?php
            endif;
        ?>
        </main>

        
        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/materialize.js"></script>
        <script type="text/javascript" src="js/moment.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                /*Initialize select*/
                $('select').material_select();

                /*Initialize inputs*/


                //Client side : Verify required fields inputs
                function ValidateRequiredFields()
                {
                    var $inputs = $("input,textarea,select");
                    var $cur_input = null;
                    var cur_val = "";
                    var is_valid = true;

                    //Foreach input
                    $inputs.each(function(){
                        $cur_input = $(this);
                        
                        //If the item is required ~ check if the value
                        if($cur_input.prop("required"))
                        {
                            //Reset validity
                            $cur_input.removeClass("valid");
                            $cur_input.removeClass("invalid");

                            //Current value
                            cur_val = $cur_input.val();
                            
                            //If the value is set ~ it is valid
                            if(cur_val && cur_val!="")
                            {
                                $cur_input.addClass("valid");
                            }
                            else //Value is not set ~ required field is empty, invalid
                            {
                                $cur_input.addClass("invalid");
                                is_valid = false;
                            }
                            console.log($cur_input);
                            console.log("Current value = ",cur_val);
                        }
                    });

                    return is_valid;
                }
                /*Send button clicked*/
                $("#btn_send_problem_form").click(function(){
                    //Check that the required fields have been filled
                    var valid_input = ValidateRequiredFields();

                    //If the required inputs are valid/set ~ submit the form
                    if(valid_input)
                    {
                        var data = {
                            "report_section":$("#report_section").val(),
                            "report_specific":$("#report_specific").val(),
                            "report_message":$("#report_message").val()
                        };
                        console.log(data);
                        console.log();

                        var SUPPORT_EMAIL = "support@rain.co.ke";//TODO: Change or acquire this email

                        //AJAX request
                        $.post("handlers/email_handler.php",{"action":"ReportProblem","data":data},function(response,status){
                            if(response == 1 || response==true)
                            {
                                Materialize.toast("Successfully reported the problem. Thanks for your feedback",2500);
                            }
                            else
                            {
                                Materialize.toast("Failed to report the problem. Server side error: If the problem persists, contact support ("+SUPPORT_EMAIL+")",2500);
                            }
                        }, 'json');
                    }
                    else
                    {
                        Materialize.toast("Failed to report problem. Reason: One or more required fields is empty.",2500);
                    }
                });
            });
        </script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </body>
</html>
