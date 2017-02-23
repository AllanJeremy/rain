<!DOCTYPE html>
<!--Minified version of the js-->
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
                                <a href="<?php echo 'tests.php?tid='.htmlspecialchars($_GET['tid'])?>" class="">
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
                        Test::DisplayTestInstructions($test);//Display test creator's instructions
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

                    $current_question = 0;
                    if(isset($_GET["q"]))#if question number is set
                    {
                        $current_question = htmlspecialchars($_GET["q"]);
                        if($current_question<1 || $current_question>$test["number_of_questions"])
                        {header("Location:404.html");}
                        Test::DisplayTest($test,$current_question);#Display the current question
                    }else
                    {
                        Test::DisplayTestInstructions($test,true);//Display test taker's instructions
                    }


            ?>



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
    $(document).ready(function(){jQuery.fn.extend({getUrlParam:function(a){a=escape(unescape(a));var b=new Array,c=null;if("#document"==$(this).attr("nodeName"))window.location.search.search(a)>-1&&(c=window.location.search.substr(1,window.location.search.length).split("&"));else if("undefined"!=$(this).attr("src")){var d=$(this).attr("src");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}else{if("undefined"==$(this).attr("href"))return null;var d=$(this).attr("href");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}if(null==c)return null;for(var f=0;f<c.length;f++)escape(unescape(c[f].split("=")[0]))==a&&b.push(c[f].split("=")[1]);return 0==b.length?null:1==b.length?b[0]:b}});var max_grade=parseInt($("#test_max_grade").text());function UpdateMarksClasses(cur_marks,max_grade)
{var $txt_marks_alloc=$("#txt_marks_allocated");$txt_marks_alloc.removeClass();var class_to_add="";if(cur_marks<max_grade)
{class_to_add="green-text text-accent-3"}
else if(cur_marks>max_grade)
{class_to_add="red-text text-accent-2"}
else{class_to_add="cyan-text"}
$txt_marks_alloc.addClass(class_to_add)}
var marks_alloc=parseInt($("#txt_marks_allocated").text());var init_marks_val=0;function InitMarksAttainable()
{var q_type=$(".test_q_type:checked").val();var question_marks=0;switch(q_type)
{case "single_choice":question_marks=$("#s_question_marks").val();break;case "multiple_choice":question_marks=$("#m_question_marks").val();break;default:console.log("Unknown question type")}
init_marks_val=question_marks;UpdateMarksClasses(marks_alloc,max_grade)}
if(parseInt($(document).getUrlParam("edit"))===1)
{InitMarksAttainable()}
function ToggleQuestionType(q_id)
{$(".single_choice_question").attr("data-qid",function()
{if($(this).attr("data-qid")==q_id)
{$(this).toggleClass("hide")}});$(".multiple_choice_question").attr("data-qid",function()
{if($(this).attr("data-qid")==q_id)
{$(this).toggleClass("hide")}})}
$(".test_q_type").change(function(){var q_id=$(this).attr("data-toggle-qid");var q_type=$(this).attr("value");var qtype_single_id="#test_qtype_single";var qtype_multiple_id="#test_qtype_multiple";switch(q_type)
{case "single_choice":ToggleQuestionType(q_id);break;case "multiple_choice":ToggleQuestionType(q_id);break;default:console.log("Unknown question type!")}});$(document.body).on("input",".test_answer",function()
{$(this).siblings(".test_answer_label").html($(this).val())});var single_choice_count=$("#single_choices_count").val();var multiple_choice_count=$("#multiple_choices_count").val();var option_dom_count=$(".s_que_answer_container").children().length;function UpdateChoicesCount(container_name,cur_choice_count)
{var options_dom_count=$(container_name).children().length;var input_type="radio";var type_prepend="";switch(container_name)
{case ".s_que_answer_container":input_type="radio";type_prepend="s_";break;case ".m_que_answer_container":input_type="checkbox";type_prepend="m_";break;default:console.log("Unknown input type requested in UpdateChoicesCount")}
marks_attainable_selector="#"+type_prepend+"_question_marks";if(cur_choice_count>options_dom_count)
{var items_to_add=(cur_choice_count-options_dom_count);for(var i=0;i<items_to_add;i++)
{var opt_index=options_dom_count+i+1;$(container_name).append("<div class='test_answer_container' data-ans-index='"+opt_index+"'><input type='"+input_type+"' name='"+type_prepend+"option_group' id='"+type_prepend+"option_"+opt_index+"' class='valign'><label for='"+type_prepend+"option_"+opt_index+"' class='test_answer_label'>Option "+opt_index+"</label><input placeholder='Option "+opt_index+"' class='test_answer'></div>")}
console.log("Number of test label objects is "+$(".test_answer_label").length)}
else if(cur_choice_count<options_dom_count)
{var items_to_remove=(options_dom_count-cur_choice_count);var answersListJson=[];for(var i=0;i<items_to_remove;i++)
{$ans_container=$(container_name).children(":last-of-type");$answers=$($ans_container).children(".test_answer_container");var answerJson={"question_index":parseInt($(document).getUrlParam("q")),"answer_text":$($ans_container).children("input.test_answer").val(),"answer_index":parseInt($($ans_container).attr("data-ans-index")),"right_answer":"","marks_attainable":""};correct_answer_count=$($answers).children("input:"+input_type+":checked").length;if($($ans_container).children("input:"+input_type).is(":checked"))
{answerJson.right_answer=1}
else{answerJson.right_answer=0}
if(container_name==".m_que_answer_container")
{answerJson.marks_attainable=$(marks_attainable_selector).val()/correct_answer_count}
else{answerJson.marks_attainable=parseInt($(marks_attainable_selector).val())}
answersListJson.push(answerJson);$ans_container.remove()}
$.post("handlers/db_handler.php",{"action":"DeleteQuestionAnswer","answers_data":answersListJson},function(status)
{console.log("Delete answer ajax status : "+status)})}}
$(".option_count").change(function()
{if($(this).val()<parseInt($(this).attr("min")))
{$(this).val($(this).attr("min"))}
else if($(this).val()>parseInt($(this).attr("max")))
{$(this).val($(this).attr("max"))}
var cur_choice_count=$(this).val();var option_type=$(this).attr("id");switch(option_type)
{case "single_choices_count":UpdateChoicesCount(".s_que_answer_container",cur_choice_count);break;case "multiple_choices_count":UpdateChoicesCount(".m_que_answer_container",cur_choice_count);break;default:console.log("Unknown question type selected")}});function GetQuestionAnswerData(q_type)
{var ans_container="";var marks_attainable_selector="";var input_type="";switch(q_type)
{case "single":ans_container=".s_que_answer_container";marks_attainable_selector="#s_question_marks";input_type="radio";break;case "multiple":ans_container=".m_que_answer_container";marks_attainable_selector="#m_question_marks";input_type="checkbox";break;default:console.log("Unknown Question type. Unable to retrieve question answers")}
var $answers=$(ans_container).children(".test_answer_container");var answerListJson=[];var correct_answer_count=$($answers).children("input:"+input_type+":checked").length;for(var i=0;i<$answers.length;i++)
{var answerJson={"answer_text":"","answer_index":"","right_answer":"","marks_attainable":""};var $cur_ans=$answers[i];var $ans_text=$($cur_ans).children("input.test_answer").val();var marks_attainable=0;if($ans_text==""||$ans_text==null)
{$ans_text=$($cur_ans).children(".test_answer_label").text()}
answerJson.answer_text=$ans_text;answerJson.answer_index=parseInt($($cur_ans).attr("data-ans-index"));if($($cur_ans).children("input:"+input_type).is(":checked"))
{answerJson.right_answer=1}
else{answerJson.right_answer=0}
if(q_type=="multiple")
{answerJson.marks_attainable=$(marks_attainable_selector).val()/correct_answer_count}
else{answerJson.marks_attainable=parseInt($(marks_attainable_selector).val())}
answerListJson.push(answerJson)}
console.log(answerListJson);return answerListJson}
function GetQuestionData(redirect_url)
{var q_type=$(".test_q_type:checked").val();var qData={"test_id":parseInt($(document).getUrlParam("tid")),"question_index":parseInt($(document).getUrlParam("q")),"question_text":"","question_type":"","no_of_choices":"","marks_attainable":"","answers":"","redirect_url":redirect_url};var question_text=$("#test_question").val();var question_type=q_type;var no_of_choices;var marks_attainable;var answersJsonArray;switch(q_type)
{case "single_choice":no_of_choices=parseInt($("#single_choices_count").val());marks_attainable=parseInt($("#s_question_marks").val());answersJsonArray=GetQuestionAnswerData("single");break;case "multiple_choice":no_of_choices=parseInt($("#multiple_choices_count").val());marks_attainable=parseInt($("#m_question_marks").val());answersJsonArray=GetQuestionAnswerData("multiple");break;default:console.log("Unknown question type")}
qData.question_text=question_text;qData.question_type=question_type;qData.no_of_choices=no_of_choices;qData.marks_attainable=marks_attainable;qData.answers=answersJsonArray;console.log("Question data \n");console.log(qData);return qData}
var question_data;$("#save_question").click(function(){question_data=GetQuestionData("#!");$.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(data,status){})});$("#prev_question").click(function(){var redirect_url=$(this).attr("data-redirect-url");question_data=GetQuestionData(redirect_url);$.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(data,status){window.location=(redirect_url)})});$("#next_question").click(function(){var redirect_url=$(this).attr("data-redirect-url");question_data=GetQuestionData(redirect_url);$.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(data,status){window.location=(redirect_url)})});$(".taker_next_url").click(function(){var redirect_url=$(this).attr("data-redirect-url");var answers_provided=($(".t_test_answer").is(":checked"));var missing_answer_message="Please provide at least one answer. Note : Unless you skip this question, you will not be able to come back to it. ";if(answers_provided)
{var qData={"test_id":parseInt($(document).getUrlParam("tid")),"question_index":parseInt($(document).getUrlParam("q")),"answers_provided":[],"skipped":($(this).is("#t_skip_que"))};$.each($(".t_test_answer:checked"),function(index,value)
{qData.answers_provided.push($(this).attr("id"))});console.log(qData);$.post("handlers/db_handler.php",{"action":"UpdateTestSubmission",qData},function(data,status){console.log("Successfully updated the test submission")})}
else{Materialize.toast(missing_answer_message,5000)}});$("#complete_test").click(function(){var redirect_url=$(this).attr("data-redirect-url");question_data=GetQuestionData(redirect_url);$.post("handlers/db_handler.php",{"action":"UpdateTestQuestion","q_data":question_data},function(data,status){});window.location.replace(redirect_url)});$("#t_complete_test").click(function(){var redirect_url=$(this).attr("data-redirect-url");var test_id=$(document).getUrlParam("tid");var answers_provided=($(".t_test_answer").is(":checked"));if(answers_provided)
{var qData={"test_id":parseInt($(document).getUrlParam("tid")),"question_index":parseInt($(document).getUrlParam("q")),"answers_provided":[],"skipped":!1};$.each($(".t_test_answer:checked"),function(index,value)
{qData.answers_provided.push($(this).attr("id"))});$.post("handlers/db_handler.php",{"action":"CompleteTakingTest",qData},function(data,status){console.log("Completed test")})}});$(".question_marks").change(function(){var cur_val=$(this).val();marks_alloc=parseInt($("#txt_marks_allocated").text());marks_alloc+=(cur_val-init_marks_val);init_marks_val=cur_val;$("#txt_marks_allocated").text(marks_alloc);UpdateMarksClasses(marks_alloc,max_grade)})});
        </script>

        <script type="text/javascript" src="js/test_functions.js"></script>

        <script type="text/javascript" src="js/materialize.js"></script>

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
