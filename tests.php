<!DOCTYPE html>

<html lang="en">
    <head>
        <?php require_once("handlers/header_handler.php"); ?>
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
        </header>
        
        <main>
            <?php
                $taking_test = false;#if we are taking the test
                $current_question = 1;
                $question_count = $test["number_of_questions"];
                switch($accType):
                    case "teacher":#teacher logged in
                        $teacher_acc_id = $_SESSION["admin_acc_id"];
                        
                        #tid = test_id | edit=1, anything else means no
                        //If test is in edit state and the test belongs to the currently logged in teacher
                        if(isset($_GET["edit"]) && (htmlspecialchars($_GET["edit"])=="1") && $test["teacher_id"]==$teacher_acc_id && $test["editable"]):
                            #editing the test
            ?>

            <!--Test SubTitle section-->
            <div class="row grey darken-2 z-depth-1">
                <div class="container">
                    <div class="row no-margin">
                        <div class="col s12 m4 center-align">
                            <p class="white-text">Question <span class="php-data"><?php echo $current_question; ?></span> of <?php echo $question_count; ?></p>
                        </div>
                        <div class="col s12 m4 center-align">
                            <p class="white-text">Time left: <span class="php-data">1:00</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!--Test creation - editing section-->
            <div class="row">
                <div class="container">
                    <p class="grey-text">Question Info | Tip : Select the answer(s) to the question by selecting in your question options </p>
                    <div class="divider"></div><br>
                    <div class="row">
                        <div class="col s12">
                            <label for="test_question">Question</label>
                            <textarea class="materialize-textarea" id="test_question" placeholder="Enter question here"></textarea>
                        </div>
                    </div>
                    
                    <!--Question type-->
                    <p class="grey-text text-darken-2">Question type</p>
                    <div class="row">
                        <div class="col s12 m4">
                            <input name="test_question_type" type="radio" id="test_qtype_single" checked/>
                            <label for="test_qtype_single">Single Choice Question</label>
                        </div>
                        <div class="col s12 m4">
                            <input name="test_question_type" type="radio" id="test_qtype_multiple" />
                            <label for="test_qtype_multiple">Multiple Choice Question</label>
                        </div>

                    </div>
                    <div class="divider"></div>

                    <br><br>

                    <!--Single choice question-->
                    <div class="row single_choice_question">
                        <p class="grey-text text-darken-2">Single choice Question</p>
                        <div class="divider col s12"></div><br>
                        <!--Default settings for the question-->
                        <div class="col s12 m6">
                            <label for="no_of_choices">Number of choices</label>
                            <input type="number" value="1" min="1" max="8" id="no_of_choices" required/>
                        </div>
                        <div class="col s12 m6">
                            <label for="question_marks">Marks attainable</label>
                            <input type="number" value="5" min="1" max="20" id="question_marks" required/>
                        </div>
                        
                        <p class="grey-text text-darken-2">Options</p>
                        <div class="divider col s12"></div><br>

                        <!--Options-->
                        <div class=" col s12">
                            
                                <input type="radio" name="option_group" id="option_1" class="valign">
                                <label for="option_1">Option 1</label>
                                <input placeholder="Option 1">
                            
                                <input type="radio" name="option_group" id="option_2" class="valign">
                                <label for="option_2">Option 2</label>
                                <input placeholder="Option 2">
                            
                                <input type="radio" name="option_group" id="option_3" class="valign">
                                <label for="option_3">Option 3</label>
                                <input placeholder="Option 3">
                            
                        </div>
                    </div>
                    <br><br>
                    <!--Multiple choice question-->
                    <div class="row multiple_choice_question">
                        <p class="grey-text text-darken-2">Multiple choice Question</p>
                        <div class="divider col s12"></div><br>
                        <!--Default settings for the question-->
                        <div class="col s12 m6">
                            <label for="no_of_choices">Number of choices</label>
                            <input type="number" value="1" min="1" max="8" id="no_of_choices" required/>
                        </div>
                        <div class="col s12 m6">
                            <label for="question_marks">Marks attainable</label>
                            <input type="number" value="5" min="1" max="20" id="question_marks" required/>
                        </div>
                        
                        
                        <p class="grey-text text-darken-2">Options</p>
                        <div class="divider col s12"></div><br>

                        <!--Options-->
                        <div class=" col s12">
                            
                                <input type="checkbox" name="option_group" id="m_option_1" class="valign">
                                <label for="m_option_1">Option 1</label>
                                <input placeholder="Option 1">
                            
                                <input type="checkbox" name="option_group" id="m_option_2" class="valign">
                                <label for="m_option_2">Option 2</label>
                                <input placeholder="Option 2">
                            
                                <input type="checkbox" name="option_group" id="m_option_3" class="valign">
                                <label for="m_option_3">Option 3</label>
                                <input placeholder="Option 3">
                            
                        </div>
                    </div>
                    
                    <!--Open ended choice question | to be implemented later on as an update-->
                    <div class="row open_ended_choice_question">
                    </div>
                    
                    <div class="row">
                        <div class="col s4 left">
                            <a class="btn  disabled" href="javascript:void(0)">PREVIOUS QUESTION</a>
                        </div>
                        <div class="col s4 right">
                            <a class="btn right" href="javascript:void(0)">NEXT QUESTION</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
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
            else:
                header("Location:./");
            endif;
            ?>
        </main>
        <footer>
        </footer>
        
        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/tests-functions.js"></script>
        
        <script type="text/javascript" src="js/materialize.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
        <script>
        $(document).ready(function() {
            
            var fullscreenButton = $('a#fullScreenToggle');
                
            fullscreenButton.click(function (e) {
                e.preventDefault();
                toggleFullScreen();
            });
            
        });
        </script>
    </body>
</html>