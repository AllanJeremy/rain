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
        <footer>
        </footer>

        <script>
    $(document).ready(function(){function b(a,b){var c=$("#txt_marks_allocated");c.removeClass();var d="";d=a<b?"green-text text-accent-3":a>b?"red-text text-accent-2":"cyan-text",c.addClass(d)}function e(){var e=$(".test_q_type:checked").val(),f=0;switch(e){case"single_choice":f=$("#s_question_marks").val();break;case"multiple_choice":f=$("#m_question_marks").val();break;default:console.log("Unknown question type")}d=f,b(c,a)}function f(a){$(".single_choice_question").attr("data-qid",function(){$(this).attr("data-qid")==a&&$(this).toggleClass("hide")}),$(".multiple_choice_question").attr("data-qid",function(){$(this).attr("data-qid")==a&&$(this).toggleClass("hide")})}function j(a,b){var c=$(a).children().length,d="radio",e="";switch(a){case".s_que_answer_container":d="radio",e="s_";break;case".m_que_answer_container":d="checkbox",e="m_";break;default:console.log("Unknown input type requested in UpdateChoicesCount")}if(marks_attainable_selector="#"+e+"_question_marks",b>c){for(var f=b-c,g=0;g<f;g++){var h=c+g+1;$(a).append("<div class='test_answer_container' data-ans-index='"+h+"'><input type='"+d+"' name='"+e+"option_group' id='"+e+"option_"+h+"' class='valign'><label for='"+e+"option_"+h+"' class='test_answer_label'>Option "+h+"</label><input placeholder='Option "+h+"' class='test_answer'></div>")}console.log("Number of test label objects is "+$(".test_answer_label").length)}else if(b<c){for(var i=c-b,j=[],g=0;g<i;g++){$ans_container=$(a).children(":last-of-type"),$answers=$($ans_container).children(".test_answer_container");var k={question_index:parseInt($(document).getUrlParam("q")),answer_text:$($ans_container).children("input.test_answer").val(),answer_index:parseInt($($ans_container).attr("data-ans-index")),right_answer:"",marks_attainable:""};correct_answer_count=$($answers).children("input:"+d+":checked").length,$($ans_container).children("input:"+d).is(":checked")?k.right_answer=1:k.right_answer=0,".m_que_answer_container"==a?k.marks_attainable=$(marks_attainable_selector).val()/correct_answer_count:k.marks_attainable=parseInt($(marks_attainable_selector).val()),j.push(k),$ans_container.remove()}$.post("handlers/db_handler.php",{action:"DeleteQuestionAnswer",answers_data:j},function(a){console.log("Delete answer ajax status : "+a)})}}function k(a){var b="",c="",d="";switch(a){case"single":b=".s_que_answer_container",c="#s_question_marks",d="radio";break;case"multiple":b=".m_que_answer_container",c="#m_question_marks",d="checkbox";break;default:console.log("Unknown Question type. Unable to retrieve question answers")}for(var e=$(b).children(".test_answer_container"),f=[],g=$(e).children("input:"+d+":checked").length,h=0;h<e.length;h++){var i={answer_text:"",answer_index:"",right_answer:"",marks_attainable:""},j=e[h],k=$(j).children("input.test_answer").val();""!=k&&null!=k||(k=$(j).children(".test_answer_label").text()),i.answer_text=k,i.answer_index=parseInt($(j).attr("data-ans-index")),$(j).children("input:"+d).is(":checked")?i.right_answer=1:i.right_answer=0,"multiple"==a?i.marks_attainable=$(c).val()/g:i.marks_attainable=parseInt($(c).val()),f.push(i)}return console.log(f),f}function l(a){var f,g,h,b=$(".test_q_type:checked").val(),c={test_id:parseInt($(document).getUrlParam("tid")),question_index:parseInt($(document).getUrlParam("q")),question_text:"",question_type:"",no_of_choices:"",marks_attainable:"",answers:"",redirect_url:a},d=$("#test_question").val(),e=b;switch(b){case"single_choice":f=parseInt($("#single_choices_count").val()),g=parseInt($("#s_question_marks").val()),h=k("single");break;case"multiple_choice":f=parseInt($("#multiple_choices_count").val()),g=parseInt($("#m_question_marks").val()),h=k("multiple");break;default:console.log("Unknown question type")}return c.question_text=d,c.question_type=e,c.no_of_choices=f,c.marks_attainable=g,c.answers=h,console.log("Question data \n"),console.log(c),c}$("select").material_select(),jQuery.fn.extend({getUrlParam:function(a){a=escape(unescape(a));var b=new Array,c=null;if("#document"==$(this).attr("nodeName"))window.location.search.search(a)>-1&&(c=window.location.search.substr(1,window.location.search.length).split("&"));else if("undefined"!=$(this).attr("src")){var d=$(this).attr("src");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}else{if("undefined"==$(this).attr("href"))return null;var d=$(this).attr("href");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}if(null==c)return null;for(var f=0;f<c.length;f++)escape(unescape(c[f].split("=")[0]))==a&&b.push(c[f].split("=")[1]);return 0==b.length?null:1==b.length?b[0]:b}});var a=parseInt($("#test_max_grade").text()),c=parseInt($("#txt_marks_allocated").text()),d=0;1===parseInt($(document).getUrlParam("edit"))&&e(),$(".test_q_type").change(function(){var a=$(this).attr("data-toggle-qid"),b=$(this).attr("value");switch(b){case"single_choice":f(a);break;case"multiple_choice":f(a);break;default:console.log("Unknown question type!")}}),$(document.body).on("input",".test_answer",function(){$(this).siblings(".test_answer_label").html($(this).val())});$("#single_choices_count").val(),$("#multiple_choices_count").val(),$(".s_que_answer_container").children().length;$(".option_count").change(function(){$(this).val()<parseInt($(this).attr("min"))?$(this).val($(this).attr("min")):$(this).val()>parseInt($(this).attr("max"))&&$(this).val($(this).attr("max"));var a=$(this).val(),b=$(this).attr("id");switch(b){case"single_choices_count":j(".s_que_answer_container",a);break;case"multiple_choices_count":j(".m_que_answer_container",a);break;default:console.log("Unknown question type selected")}});var m,n="Successfully saved the question";$("#save_question").click(function(){m=l("#!"),$.post("handlers/db_handler.php",{action:"UpdateTestQuestion",q_data:m},function(a,b){console.log("status :",b),b&&Materialize.toast(n,2e3)})}),$(".redirect_save_btn").click(function(){var a=$(this).attr("data-redirect-url");m=l(a),$.post("handlers/db_handler.php",{action:"UpdateTestQuestion",q_data:m},function(b,c){window.location=a})}),$(".taker_next_url").click(function(){var a=$(this).attr("data-redirect-url"),b=$(".t_test_answer").is(":checked"),c="Please provide at least one answer. Note : Unless you skip this question, you will not be able to come back to it. ";if(b){var d={test_id:parseInt($(document).getUrlParam("tid")),question_index:parseInt($(document).getUrlParam("q")),answers_provided:[],skipped:$(this).is("#t_skip_que")};$.each($(".t_test_answer:checked"),function(a,b){d.answers_provided.push($(this).attr("id"))}),console.log(d),$.post("handlers/db_handler.php",{action:"UpdateTestSubmission",q_data:d},function(b,c){console.log("Successfully updated the test submission"),window.location=a})}else Materialize.toast(c,5e3)}),$("#complete_test").click(function(){var a=$(this).attr("data-redirect-url");m=l(a),$.post("handlers/db_handler.php",{action:"UpdateTestQuestion",q_data:m},function(a,b){}),window.location.replace(a)}),$("#t_complete_test").click(function(){var c=($(this).attr("data-redirect-url"),$(document).getUrlParam("tid"),$(".t_test_answer").is(":checked"));if(c){var d={test_id:parseInt($(document).getUrlParam("tid")),question_index:parseInt($(document).getUrlParam("q")),answers_provided:[],skipped:!1};$.each($(".t_test_answer:checked"),function(a,b){d.answers_provided.push($(this).attr("id"))}),$.post("handlers/db_handler.php",{action:"CompleteTakingTest",q_data:d},function(a,b){console.log("Completed test")})}}),$(".question_marks").change(function(){var e=$(this).val();c=parseInt($("#txt_marks_allocated").text()),c+=e-d,d=e,$("#txt_marks_allocated").text(c),b(c,a)}),$(".simple_redirect_btn").click(function(){var a=$(this).attr("data-redirect-url");window.location=a}),$("#start_test").click(function(){var a=$(this).attr("data-redirect-url"),b=$(document).getUrlParam("tid");$.post("handlers/timer_handler.php",{action:"StartTestTimer",test_id:b},function(){window.location=a})}),$(".skipped_questions_btn").click(function(){})});
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
