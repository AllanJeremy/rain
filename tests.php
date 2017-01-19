<!DOCTYPE html>

<html lang="en">
    <head>
        <?php 
            require_once("handlers/header_handler.php"); 
            require_once(realpath(dirname(__FILE__) . "/classes/test.php"));
        ?>
        <title><?php echo MyHeaderHandler::SITE_TITLE;?> | Tests</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        
    </head>

    <body>
        <?php
            //If statement that determines whether content can be viewed
            if (MySessionHandler::AdminIsLoggedIn() || MySessionHandler::StudentIsLoggedIn()):
               
                //Account type - from session variable storing the account type of the currently logged in user
                $snippet_folder = "snippets/";#folder that contains snippets

                $accType="";
                //Determine what type of account is logged in and set accType to the appropriate value
                if(MySessionHandler::AdminIsLoggedIn())
                {
                    $accType = $_SESSION["admin_account_type"];#corresponds with file name prefix as well as the database name of the account type
                }
                else if(MySessionHandler::StudentIsLoggedIn())
                {
                    $accType = "student";#corresponds with file name prefix
                }
                
                //Allow editing of test if the test creator is logged in and the test is in an editable state
                if(isset($_GET["tid"]) && $test=DbInfo::TestExists(htmlspecialchars($_GET["tid"]))):#If the test can be identified
?>
        <header>
            <nav class="top-nav">
                <div class="container ">
                    <div class="nav-wrapper ">
                        <div class="row no-margin">
                            <div class="col s2">
                                <a href="index.php" class="">
                                    <i class="material-icons">arrow_back</i>
                                </a>
                            </div>
                            <div class="col s8">
                                <a class="page-title center-align"><?php echo $test["test_title"];?></a>
                            </div>
                            <div class="col s2" id="fullScreenDiv">
                                <a class="right" id="fullScreenToggle" href="#!FullScreenTestPage"><i class="material-icons">fullscreen</i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!--Jquery-->
            <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        </header>
        

        <main>
            <?php
                $taking_test = false;#if we are taking the test
                
                $question_count = $test["number_of_questions"];
                switch($accType):
                    case "teacher":#teacher logged in
                        $teacher_acc_id = $_SESSION["admin_acc_id"];
                        
                        #tid = test_id | edit=1, anything else means no
                        //If test is in edit state and the test belongs to the currently logged in teacher
                        if(isset($_GET["edit"]) && (htmlspecialchars($_GET["edit"])=="1") && $test["teacher_id"]==$teacher_acc_id && $test["editable"]):
                            #editing the test
                        if(isset($_GET["q"])):#if question number is set
                            $current_question = htmlspecialchars($_GET["q"]);
                            if($current_question>0 && $current_question<= $test["number_of_questions"]):#if the question number is a valid number_format
                                Test::DisplayEditQuestion($test,$current_question);
                            
                            else:#invalid question number provided
            ?>
            <h3 class="center">Invalid question. Question not found</h3>

            <?php
                    endif;   
                    else:#question number not set
                        Test::DisplayTestInstructions($test);
            ?>
            
            <?php
                        endif;
                        else:#invalid edit credentials - redirect to the test
                            $taking_test = true;                     
                        endif;
                    break;
                    case "student":
                        $taking_test = true;
                    break;#student logged in
                endswitch;

                if($taking_test):
            ?>
            <div class="row grey darken-2 z-depth-1">
                <div class="container">
                    <div class="row no-margin">
                        <div class="col s12 m4 center-align">
                            <p class="white-text">Question <span class="php-data"><?php echo $current_question; ?></span> of <?php echo $question_count; ?></p>
                        </div>
                        <!--<div class="col s12 m4 center-align">
                            <p class="white-text"><span class="php-data">1</span> question skipped</p>
                        </div>-->
                        <div class="col s12 m4 center-align">
                            <p class="white-text">Time left: <span class="php-data">1:04:32</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-container">
                <h4 class="col s12 grey-text thin text-darken-4 question-number">Question 4</h4>
                <br>
                <h5 class="question light black-text">
                    Tables are a nice way to organize a lot of data. We provide a few utility classes to help you style your table as easily as possible. In addition, to improve mobile experience, all tables on mobile-screen widths are centered automatically. Which color?
                </h5>
                <br>
                <div class="col s12">
                    <form action="#" class="row">
                        <p>
                            <input name="group1" type="radio" id="test1" />
                            <label for="test1">Red</label>
                        </p>
                        <p>
                            <input name="group1" type="radio" id="test2" />
                            <label for="test2">Yellow</label>
                        </p>
                        <p>
                            <input class="" name="group1" type="radio" id="test3"  />
                            <label for="test3">Green</label>
                        </p>
                        
                        <div class="col s6 input-field">
                            <a class="btn right" type="submit">Next</a>
                        </div>
                        <div class="col s6 input-field">
                            <a class="btn-flat btn-skip-question right" type="submit">skip</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php
                endif;#if taking test

                else:#testid is set and is a valid test
                    header("Location:./404.html");
                endif;
            else:#if no test id is provided
                header("Location:./");
            endif;
            ?>
        </main>
        <footer>
        </footer>

        <script>

        $(document).ready(function(){
            //function to hide and show content
            function ToggleQuestionType(q_id)
            {
                $(".single_choice_question").attr("data-qid",function()
                {
                    if($(this).attr("data-qid")==q_id)
                    {
                        $(this).toggleClass("hide");
                    } 
                });

                $(".multiple_choice_question").attr("data-qid",function()
                {
                    if($(this).attr("data-qid")==q_id)
                    {
                        $(this).toggleClass("hide");
                    } 
                });
            }
            //When the question type changes
            $(".test_q_type").change(function(){
                var q_id = $(this).attr("data-toggle-qid");//unique generated question id
                var q_type = $(this).attr("value");//question type
                
                var qtype_single_id = "#test_qtype_single";
                var qtype_multiple_id = "#test_qtype_multiple";

                switch(q_type)
                {
                    case "single_choice":
                        ToggleQuestionType(q_id);
                    break;

                    case "multiple_choice":
                        //Select the container for single choice questions
                        ToggleQuestionType(q_id);
                    break;

                    default:
                        console.log("Unknown question type!");
                }
            });

            //When content of the option/answers changes update the label for the radio/checkbox
            $(".test_answer").on("input",function()
            {
                $(this).siblings(".test_answer_label").html($(this).val());
            }
            );


            //Initial values of the choice counts 
            var single_choice_count = $("#single_choices_count").val();
            var multiple_choice_count = $("#multiple_choices_count").val();
            var option_dom_count = $(".s_que_answer_container").children().length;
            
            //Function to update the DOM, updating the number of choices
            function UpdateChoicesCount()
            {

            }

            //When the value of number of options changes
            $(".option_count").change(function()
            {
                var option_type = $(this).attr("id");
                var cur_choice_count = $(this).val();//current choice count
                console.log("Choice count : "+cur_choice_count+" | Max value : "+$(this).attr("max"));
                //Update current choice count to fit within the range provided
                if(cur_choice_count < $(this).attr("min"))//If the value is less than the min. Set it to min
                {
                    $(this).val($(this).attr("min"));
                }
                else if(cur_choice_count > parseInt($(this).attr("max")))//If the value is greater than the max. Set it to max
                {
                    console.log("exceeded the max value");
                    $(this).val($(this).attr("max"));
                }

                switch(option_type)
                {
                    case "single_choices_count": //If single choice

                        //Means value increased
                        if(cur_choice_count>single_choice_count)
                        {
                            console.log("value went up");
                            $(".s_que_answer_container").append("<div class='test_answer_container' data-ans-index='1'><input type='radio' name='option_group' id='option_1' class='valign'><label for='option_1' class='test_answer_label'>Option 1</label><input placeholder='Option 1' class='test_answer'></div>");
                        } 
                        else if(cur_choice_count==single_choice_count)//Value did not change
                        {
                            console.log("value stayed the same");//this should not run since this code runs on changed value. Here incase something goes wrong.
                        }
                        else//Value reduced
                        {
                            if(cur_choice_count>0)
                            {
                                //Prevent deleting of the  last child
                                if($(".s_que_answer_container").children().length>1)
                                {
                                    $(".s_que_answer_container").children(":last-child").remove();
                                }
                                console.log($(".s_que_answer_container").children().length);                     
                            }
                            
                        }

                       single_choice_count = cur_choice_count;//update the single choice count to be the current value. Used for the next cycle

                    break;

                    case "multiple_choices_count"://If multiple choice
                        console.log("Multiple choice remove");
                        //$(".m_que_answer_container:last-child").remove();
                    break;

                    default:
                        console.log("Unknown question type selected");
                }
            });
        });

        </script>

        <script type="text/javascript" src="js/tests-functions.js"></script>
        
        <script type="text/javascript" src="js/materialize.js"></script>
        <script type="text/javascript" src="js/dashboard.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
        <script>
        $(document).ready(function() {
            
            var fullscreenButton = $('a#fullScreenToggle');
                
            fullscreenButton.click(function (e) {
                e.preventDefault();
                toggleFullScreen();
            });

           // 
        });
        </script>
    </body>
</html>