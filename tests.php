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
            //Get URL params script 
            jQuery.fn.extend({getUrlParam:function(a){a=escape(unescape(a));var b=new Array,c=null;if("#document"==$(this).attr("nodeName"))window.location.search.search(a)>-1&&(c=window.location.search.substr(1,window.location.search.length).split("&"));else if("undefined"!=$(this).attr("src")){var d=$(this).attr("src");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}else{if("undefined"==$(this).attr("href"))return null;var d=$(this).attr("href");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}if(null==c)return null;for(var f=0;f<c.length;f++)escape(unescape(c[f].split("=")[0]))==a&&b.push(c[f].split("=")[1]);return 0==b.length?null:1==b.length?b[0]:b}});
            
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
            $(document.body).on("input",".test_answer",function()
            {
                $(this).siblings(".test_answer_label").html($(this).val());
            }
            );


            //Initial values of the choice counts 
            var single_choice_count = $("#single_choices_count").val();
            var multiple_choice_count = $("#multiple_choices_count").val();
            var option_dom_count = $(".s_que_answer_container").children().length;
            
            //Function to update the DOM, updating the number of choices
            function UpdateChoicesCount(container_name,cur_choice_count)
            {
                        var options_dom_count = $(container_name).children().length;
                        
                        var input_type = "radio";
                        var type_prepend = "";//The text prepended to any corresponding attribute - s_ for single and m_ for multiple choice questions
                       
                        switch(container_name)
                        {
                            case ".s_que_answer_container":
                                input_type = "radio";
                                type_prepend = "s_";
                            break;
                            case ".m_que_answer_container":
                                input_type = "checkbox";
                                type_prepend = "m_";
                            break;
                            default:
                                console.log("Unknown input type requested in UpdateChoicesCount");
                        }
                        //If the current value is more than the options available, add more options
                        if(cur_choice_count>options_dom_count)
                        {
                            //Number of items to be added from the dom
                            var items_to_add = (cur_choice_count - options_dom_count);
                            for(var i=0; i<items_to_add; i++)
                            {
                                var opt_index = options_dom_count + i + 1;
                                $(container_name).append("<div class='test_answer_container' data-ans-index='"+opt_index+"'><input type='"+input_type+"' name='"+type_prepend+"option_group' id='"+type_prepend+"option_"+opt_index+"' class='valign'><label for='"+type_prepend+"option_"+opt_index+"' class='test_answer_label'>Option "+opt_index+"</label><input placeholder='Option "+opt_index+"' class='test_answer'></div>");
                            }
                            console.log("Number of test label objects is "+ $(".test_answer_label").length);
                        }
                        else if(cur_choice_count<options_dom_count)//If the current value is less than the options available take out the extra options
                        {
                            //Items to be removed from the dom
                            var items_to_remove = (options_dom_count - cur_choice_count);
                            
                            //Remove all the extra items from DOM
                            for(var i=0; i<items_to_remove ; i++)
                            {
                                $(container_name).children(":last-of-type").remove();
                            }
                        }

            }

            //When the value of number of options changes
            $(".option_count").change(function()
            {
                //Regulate current choice count to fit within the range provided
                if($(this).val() < parseInt($(this).attr("min")))//If the value is less than the min. Set it to min
                {
                    $(this).val($(this).attr("min"));
                }
                else if($(this).val() > parseInt($(this).attr("max")))//If the value is greater than the max. Set it to max
                {
                    $(this).val($(this).attr("max"));
                }

                var cur_choice_count = $(this).val();//current choice count

                var option_type = $(this).attr("id");//The type is determined by what kind of id the question has
                //Check which option_type was selected
                switch(option_type)
                {
                    case "single_choices_count": //If single choice
                         UpdateChoicesCount(".s_que_answer_container",cur_choice_count);
                    break;

                    case "multiple_choices_count"://If multiple choice
                         UpdateChoicesCount(".m_que_answer_container",cur_choice_count);
                    break;

                    default:
                        console.log("Unknown question type selected");
                }
            });

            //Returns question answer data ~ q_type is either "single" or "multiple"
            function GetQuestionAnswerData(q_type)
            {
                var ans_container = "";
                var marks_attainable_selector = "";
                var input_type = "";//Type of the answer | radio or checkbox
                
                switch(q_type)
                {
                    case "single":
                        ans_container = ".s_que_answer_container";
                        marks_attainable_selector= "#s_question_marks";
                        input_type = "radio";
                    break;

                    case "multiple":
                        ans_container = ".m_que_answer_container";
                        marks_attainable_selector= "#m_question_marks";
                        input_type = "checkbox";
                    break;

                    default:
                        console.log("Unknown Question type. Unable to retrieve question answers");
                }

                //Im[;e,]
                var $answers = $(ans_container).children(".test_answer_container");

                var answerListJson = [];//Contains the list of answers

                //Number of correct answers
                var correct_answer_count = $($answers).children("input:"+input_type+":checked").length;
               
                //Get individual answers
                for(var i=0; i<$answers.length;i++)
                {
                    //Json storing the answer
                    var answerJson = {
                        "answer_text":"",
                        "answer_index":"",
                        "right_answer":"",
                        "marks_attainable":""
                    };//TODO : Remember to get the question_id in php

                    var $cur_ans = $answers[i];//Current answer - container of the answer test_answer_container

                    var $ans_text = $($cur_ans).children("input.test_answer").val();
                    var marks_attainable = 0;
                    
                    //If no answer has been entered yet. Make the label value for the corresponding radio button the answer
                    if($ans_text == "" || $ans_text==null)
                    {
                        $ans_text = $($cur_ans).children(".test_answer_label").text();
                    }
                    answerJson["answer_text"] = $ans_text;
                    answerJson["answer_index"] = parseInt($($cur_ans).attr("data-ans-index"));
                    
                    //If the answer is checked then it is the right answer. if not it is not 
                    if($($cur_ans).children("input:"+input_type).is(":checked"))
                    {
                        answerJson["right_answer"] = 1; 
                    }
                    else
                    {
                        answerJson["right_answer"] = 0;
                    }
                    
                    //If it is a multiple choice question, distribute the points equally across correct answers
                    if(q_type == "multiple")
                    {
                        answerJson["marks_attainable"] = $(marks_attainable_selector).val()/correct_answer_count;
                    }
                    else
                    {
                        answerJson["marks_attainable"] = parseInt($(marks_attainable_selector).val());
                    }
                    
                    answerListJson.push(answerJson);//Bugged ~ Only pushes the last element
                    
                
                }
                console.log(answerListJson);
                return answerListJson;
            }

            //Get the question data
            function GetQuestionData(redirect_url)
            {
                //Check what type of question the question is ~ to know what answers to store
                var q_type = $(".test_q_type:checked").val();
                
                //Json storing the question data
                var qData = {
                "test_id":parseInt($(document).getUrlParam("tid")),
                "question_index":parseInt($(document).getUrlParam("q")),
                "question_text":"",
                "question_type":"",
                "no_of_choices":"",
                "marks_attainable":"",
                "answers":"",
                "redirect_url":redirect_url
                };

                //Variables for storing the question data
                var question_text = $("#test_question").val();//TODO : Make sure question cannot be blank
                var question_type = q_type;
                var no_of_choices;
                var marks_attainable;
                var answersJsonArray;
                
                switch(q_type)
                {
                    case "single_choice":
                        no_of_choices = parseInt($("#single_choices_count").val());
                        marks_attainable = parseInt($("#s_question_marks").val());
                        answersJsonArray = GetQuestionAnswerData("single");
                    break;

                    case "multiple_choice":
                        no_of_choices = parseInt($("#multiple_choices_count").val());
                        marks_attainable = parseInt($("#m_question_marks").val());
                        answersJsonArray = GetQuestionAnswerData("multiple");
                    break;

                    default:
                    console.log("Unknown question type");
                }

                //Set values for the question data
                qData["question_text"] = question_text;
                qData["question_type"] = question_type;
                qData["no_of_choices"] = no_of_choices;
                qData["marks_attainable"] = marks_attainable;
                qData["answers"] = answersJsonArray;

                console.log("Question data \n");
                console.log(qData);
                return qData;
            }

            var question_data;//Question data ~ Json

            $("#prev_question").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                //Do everything that needs to be done first here
                question_data =  GetQuestionData(redirect_url);
                
                //Redirect to the previous page
                //window.location.replace(redirect_url);
            });
            //When the next question button is clicked
            $("#next_question").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                //Do everything that needs to be done first here
                question_data = GetQuestionData(redirect_url);
                
                $.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(question_data,status){
                  //  alert("Data :"+ question_data+" Status:"+status);
                });
                //Redirect to the next page
                //window.location.replace(redirect_url);
            });
            //When the complete test button is clicked
            $("#complete_test").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                //Do everything that needs to be done first here
                question_data = GetQuestionData(redirect_url);

                //Redirect to the completed test page
                window.location.replace(redirect_url);
            });
        });//End of document ready

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
        });

        </script>
    </body>
</html>