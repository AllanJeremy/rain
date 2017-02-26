<!DOCTYPE html>

<html lang="en">
    <head>
        <?php
            require_once(realpath(dirname(__FILE__) ."/handlers/header_handler.php"));
            require_once(realpath(dirname(__FILE__) . "/classes/test.php"));
            require_once(realpath(dirname(__FILE__) . "/handlers/date_handler.php"));
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

                //Store the logged in user's information
                $user_info = MySessionHandler::GetLoggedUserInfo();
                $test_in_waiting_state = false; #true if the taker needs to wait before retaking the test, false if not

                //Allow editing of test if the test creator is logged in and the test is in an editable state
                if(isset($_GET["tid"]) && $test=DbInfo::TestExists(htmlspecialchars($_GET["tid"]))):#If the test can be identified
                    //TODO : Make it so that if a test is in waiting state, it cannot be taken ~ Questions are not displayed
                    //Check if the test has already been taken before. IF it has, check the soones it can be retaken
                    $retake_info = DbInfo::GetTestRetake($test["test_id"],$user_info);#retake info

                    if($retake_info)
                    {
                        $retake_date = strtotime($retake_info["retake_date"]);
                        #test is in waiting state if the time until test can be retaken has NOT elapsed
                        $test_in_waiting_state = !(EsomoDate::DateTimeHasElapsed($retake_date));
                    }


?>
        <header>
            <nav class="top-nav">
                <div class="container ">
                    <div class="nav-wrapper ">
                        <div class="row no-margin">
                            <?php
                                $logged_acc_type = $user_info["account_type"];#account type of the logged in user
                                
                                $test_id = &$_GET["tid"];#test_id
                                $edit_flag = &$_GET["edit"];#edit flag. If 1, means we are in editing mode

                                //If the test id is set and edit status is set ~ editing test
                                if(isset($test_id) && isset($edit_flag) && (htmlspecialchars($edit_flag)=="1") && $logged_acc_type=="teacher"):
                                    $url_take_test = "tests.php?tid=".$test_id;#url for taking test
                            ?>  
                            <div class="col s2">
                                <a class="tooltipped" data-position="right" data-delay="50" data-tooltip="Back to test initialization" href="<?php echo ($url_take_test.'&edit=1');?>">
                                    <i class="material-icons">arrow_back</i>
                                </a>
                            </div>
                            <div class="col s8">
                                <a class="page-title center-align"><?php echo $test["test_title"]." [EDIT MODE]";?></a>
                            </div>
                            <div class="col s2">
                                <a class="right tooltipped preview_test_btn" target="_blank" href="<?php echo $url_take_test;?>" data-position="bottom" data-delay="50" data-tooltip="Preview the test" id="previewTestBtn">
                                    <i class="material-icons">fullscreen</i>
                                </a>
                            </div>
                            <?php
                                #means we are taking the test
                                elseif(isset($test_id)&& (!isset($edit_flag) || (htmlspecialchars($edit_flag)!="1"))):
                            ?>
                            <div class="col s2">
                                <a class="tooltipped" data-position="right" data-delay="50" data-tooltip="Back to take tests" href="./#takeATest">
                                    <i class="material-icons" href="./#takeATest">arrow_back</i>
                                </a>
                            </div>
                            <div class="col s8">
                                <a class="page-title center-align"><?php echo $test["test_title"];?></a>
                            </div>
                            <div class="col s2">
                                <a class="right tooltipped skipped_questions_btn"  href="javascript:void(0)" data-position="bottom" data-delay="50" data-tooltip="Skipped questions">
                                    <i class="material-icons">library_books</i>
                                </a>
                            </div>
                            <?php
                                endif;
                            ?>
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
                        Test::DisplayEditTestInstructions($test);//Display test creator's instructions
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

                //If we are taking the test
                if($taking_test):
                    //If the test is not in waiting state, show it
                    if(!$test_in_waiting_state):
                        $current_question = 0;
                        if(isset($_GET["q"]))#if question number is set
                        {
                            $current_question = htmlspecialchars($_GET["q"]);
                            if($current_question<1 || $current_question>$test["number_of_questions"])
                            {header("Location:404.html");}
                            Test::DisplayTest($test,$current_question);#Display the current question
                        }else
                        {
                            Test::DisplayTestInstructions($test);//Display test taker's instructions
                        }
            ?>
            <?php
                    else:#if we need to wait to take the test ~ Test is in waiting state
                        Test::DisplayWaitRetakeMessage($retake_info,$user_info);
                    endif;
                endif;#if taking test

                else:#testid is set and is not a valid test
                    header("Location:./404.html");
                endif;



            else:#if no test id is provided
                header("Location:./"); #redirect to the home page
            endif;
            Test::DisplayEditTestModal();
            ?>
            
        </main>
        <?php
            /*Include the footer at the bottom of the page*/
            include_once("./snippets/site_footer.php");
        ?>

        <script>

        $(document).ready(function(){
            //Select initializing
            $('select').material_select();

            //Get URL params script
            jQuery.fn.extend({getUrlParam:function(a){a=escape(unescape(a));var b=new Array,c=null;if("#document"==$(this).attr("nodeName"))window.location.search.search(a)>-1&&(c=window.location.search.substr(1,window.location.search.length).split("&"));else if("undefined"!=$(this).attr("src")){var d=$(this).attr("src");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}else{if("undefined"==$(this).attr("href"))return null;var d=$(this).attr("href");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}if(null==c)return null;for(var f=0;f<c.length;f++)escape(unescape(c[f].split("=")[0]))==a&&b.push(c[f].split("=")[1]);return 0==b.length?null:1==b.length?b[0]:b}});

            /*
                UPDATING THE MARKS ALLOCATED TEXT IN THE SUBMENU BAR
            */
            var max_grade = parseInt($("#test_max_grade").text());
            //Update the classes used to display the different colors based on how many marks have been allocated
            function UpdateMarksClasses(cur_marks,max_grade)
            {
                var $txt_marks_alloc = $("#txt_marks_allocated");
                $txt_marks_alloc.removeClass();
                var class_to_add="";
                if(cur_marks<max_grade)
                {
                    class_to_add="green-text text-accent-3";
                }
                else if(cur_marks>max_grade)
                {
                    class_to_add="red-text text-accent-2";
                }
                else
                {
                    class_to_add="cyan-text";
                }

                $txt_marks_alloc.addClass(class_to_add);
            }

            //Init when the page first loads
            var marks_alloc = parseInt($("#txt_marks_allocated").text());//Marks allocated
            var init_marks_val = 0;
            //Send the marks attainable to the server and compute the total marks allocated
            function InitMarksAttainable()
            {
                var q_type = $(".test_q_type:checked").val();
                var question_marks = 0;

                switch(q_type)
                {
                    case "single_choice":
                        question_marks = $("#s_question_marks").val();
                    break;
                    case "multiple_choice":
                        question_marks = $("#m_question_marks").val();
                    break;
                    default:
                        console.log("Unknown question type");
                }
                init_marks_val = question_marks;

                UpdateMarksClasses(marks_alloc,max_grade)
            }

            //Get marks attainable once the page has loaded
            if(parseInt($(document).getUrlParam("edit"))===1)
            {
                InitMarksAttainable();
            }

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

                        marks_attainable_selector= "#"+type_prepend+"_question_marks";
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

                            var answersListJson = [];

                            //Remove all the extra items from DOM
                            for(var i=0; i<items_to_remove ; i++)
                            {
                                $ans_container = $(container_name).children(":last-of-type");
                                $answers = $($ans_container).children(".test_answer_container");

                                var answerJson = {
                                    "question_index":parseInt($(document).getUrlParam("q")),
                                    "answer_text":$($ans_container).children("input.test_answer").val(),
                                    "answer_index":parseInt($($ans_container).attr("data-ans-index")),
                                    "right_answer":"",
                                    "marks_attainable":""
                                };

                                correct_answer_count = $($answers).children("input:"+input_type+":checked").length;
                                //Setting the other answerJson attribute values
                                if($($ans_container).children("input:"+input_type).is(":checked"))
                                {
                                    answerJson["right_answer"] = 1;
                                }
                                else
                                {
                                    answerJson["right_answer"] = 0;
                                }
                                if(container_name == ".m_que_answer_container")
                                {
                                    answerJson["marks_attainable"] = $(marks_attainable_selector).val()/correct_answer_count;
                                }
                                else
                                {
                                    answerJson["marks_attainable"] = parseInt($(marks_attainable_selector).val());
                                }

                                answersListJson.push(answerJson);//Append the json to the json array for answers

                                $ans_container.remove();
                            }

                             $.post("handlers/db_handler.php",{"action":"DeleteQuestionAnswer","answers_data":answersListJson},function(status)
                             {
                                 console.log("Delete answer ajax status : "+status);
                             });
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

                //Contains all the answers
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
                    };

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
            var successful_save_message = "Successfully saved the question";

            //When the save button is clicked
            $("#save_question").click(function(){
                question_data = GetQuestionData("#!");

                $.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(data,status){
                   console.log("status :", status);
                   if(status )
                   Materialize.toast(successful_save_message,2000);
                });
                
            });

            //When the next question button is clicked
            $(".redirect_save_btn").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                //Do everything that needs to be done first here
                question_data = GetQuestionData(redirect_url);

                $.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(data,status){
                   window.location= (redirect_url);
                });

            });

            //Takers next question button pressed
            $(".taker_next_url").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                var answers_provided = ($(".t_test_answer").is(":checked"));//True if answers have been provided

                //Message displayed when no answer is provided
                var missing_answer_message = "Please provide at least one answer. Note : Unless you skip this question, you will not be able to come back to it. ";

                //Ensure that at least one answer is provided in order to submit data
                if(answers_provided)
                {
                    //Save question data input as json
                    var qData = {
                        "test_id":parseInt($(document).getUrlParam("tid")),
                        "question_index":parseInt($(document).getUrlParam("q")),
                        "answers_provided":[],
                        "skipped":($(this).is("#t_skip_que"))
                    };

                    //Add all provided answers to an array and update the qData
                    $.each($(".t_test_answer:checked"),function(index,value)
                    {
                        qData["answers_provided"].push($(this).attr("id"));
                    });
                    console.log(qData);

                    //Send the information to the handler
                    $.post("handlers/db_handler.php",{"action":"UpdateTestSubmission","q_data":qData},function(data,status){
                        console.log("Successfully updated the test submission");
                        window.location= (redirect_url);
                    });
                }
                else//No answer was provided for the question
                {
                    Materialize.toast(missing_answer_message,5000);
                }


            });

            //When the complete test button is clicked
            $("#complete_test").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");

                question_data = GetQuestionData(redirect_url);

                $.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(data,status){
                  //  alert("Data :"+ question_data+" Status:"+status);
                });

                //Redirect to the completed test page
                window.location.replace(redirect_url);
            });

            //Takers complete test handler
            $("#t_complete_test").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                var test_id = $(document).getUrlParam("tid");//Test Id, the id of the test

                //Marking the last question
                var answers_provided = ($(".t_test_answer").is(":checked"));//True if answers have been provided

                //Ensure that at least one answer is provided in order to submit data
                if(answers_provided)
                {
                    //Save question data input as json
                    var qData = {
                        "test_id":parseInt($(document).getUrlParam("tid")),
                        "question_index":parseInt($(document).getUrlParam("q")),
                        "answers_provided":[],
                        "skipped":false //Last question cannot be skipped
                    };

                    //Add all provided answers to an array and update the qData
                    $.each($(".t_test_answer:checked"),function(index,value)
                    {
                        qData["answers_provided"].push($(this).attr("id"));
                    });
                    $.post("handlers/db_handler.php",{"action":"CompleteTakingTest","q_data":qData},function(data,status){
                        console.log("Completed test");
                    });
                }

                //Redirect to the completed test page
                //window.location.replace(redirect_url);
            });

            //When the marks attainable change
            $(".question_marks").change(function(){
                var cur_val = $(this).val();
                marks_alloc = parseInt($("#txt_marks_allocated").text());

                marks_alloc+=(cur_val-init_marks_val);
                init_marks_val = cur_val;//Reset the initial value to the new current value

                $("#txt_marks_allocated").text(marks_alloc);
                UpdateMarksClasses(marks_alloc,max_grade);//Update the classes showing the different colors
            });

            //Start editing test clicked
            $(".simple_redirect_btn").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                window.location=(redirect_url);
            });
            
            //Start test clicked
            $("#start_test").click(function(){
                var redirect_url = $(this).attr("data-redirect-url");
                var test_id = $(document).getUrlParam("tid");//Test Id, the id of the test
                //Start timer
                $.post("handlers/timer_handler.php",{"action":"StartTestTimer","test_id":test_id},function(){
                    window.location=(redirect_url);
                });

            });

            /*SKIPPED QUESTIONS*/
            $(".skipped_questions_btn").click(function(){
                //Show skipped questions modal

            });
        });//End of document ready

        </script>

        <script type="text/javascript" src="js/test_functions.js"></script>
        <script type="text/javascript" src="js/materialize.js"></script>

        <!--Fullscreen functionality-->
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
