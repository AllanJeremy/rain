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

$(document).ready(function(){function b(a,b){var c=$("#txt_marks_allocated");c.removeClass();var d="";d=a<b?"green-text text-accent-3":a>b?"red-text text-accent-2":"cyan-text",c.addClass(d)}function e(){var e=$(".test_q_type:checked").val(),f=0;switch(e){case"single_choice":f=$("#s_question_marks").val();break;case"multiple_choice":f=$("#m_question_marks").val();break;default:console.log("Unknown question type")}d=f,c+=parseInt(f),$("#txt_marks_allocated").text(c),b(c,a)}function f(a){$(".single_choice_question").attr("data-qid",function(){$(this).attr("data-qid")==a&&$(this).toggleClass("hide")}),$(".multiple_choice_question").attr("data-qid",function(){$(this).attr("data-qid")==a&&$(this).toggleClass("hide")})}function j(a,b){var c=$(a).children().length,d="radio",e="";switch(a){case".s_que_answer_container":d="radio",e="s_";break;case".m_que_answer_container":d="checkbox",e="m_";break;default:console.log("Unknown input type requested in UpdateChoicesCount")}if(marks_attainable_selector="#"+e+"_question_marks",b>c){for(var f=b-c,g=0;g<f;g++){var h=c+g+1;$(a).append("<div class='test_answer_container' data-ans-index='"+h+"'><input type='"+d+"' name='"+e+"option_group' id='"+e+"option_"+h+"' class='valign'><label for='"+e+"option_"+h+"' class='test_answer_label'>Option "+h+"</label><input placeholder='Option "+h+"' class='test_answer'></div>")}console.log("Number of test label objects is "+$(".test_answer_label").length)}else if(b<c){for(var i=c-b,j=[],g=0;g<i;g++){$ans_container=$(a).children(":last-of-type"),$answers=$($ans_container).children(".test_answer_container");var k={question_index:parseInt($(document).getUrlParam("q")),answer_text:$($ans_container).children("input.test_answer").val(),answer_index:parseInt($($ans_container).attr("data-ans-index")),right_answer:"",marks_attainable:""};correct_answer_count=$($answers).children("input:"+d+":checked").length,$($ans_container).children("input:"+d).is(":checked")?k.right_answer=1:k.right_answer=0,".m_que_answer_container"==a?k.marks_attainable=$(marks_attainable_selector).val()/correct_answer_count:k.marks_attainable=parseInt($(marks_attainable_selector).val()),j.push(k),$ans_container.remove()}$.post("handlers/db_handler.php",{action:"DeleteQuestionAnswer",answers_data:j},function(a){console.log("Delete answer ajax status : "+a)})}}function k(a){var b="",c="",d="";switch(a){case"single":b=".s_que_answer_container",c="#s_question_marks",d="radio";break;case"multiple":b=".m_que_answer_container",c="#m_question_marks",d="checkbox";break;default:console.log("Unknown Question type. Unable to retrieve question answers")}for(var e=$(b).children(".test_answer_container"),f=[],g=$(e).children("input:"+d+":checked").length,h=0;h<e.length;h++){var i={answer_text:"",answer_index:"",right_answer:"",marks_attainable:""},j=e[h],k=$(j).children("input.test_answer").val();""!=k&&null!=k||(k=$(j).children(".test_answer_label").text()),i.answer_text=k,i.answer_index=parseInt($(j).attr("data-ans-index")),$(j).children("input:"+d).is(":checked")?i.right_answer=1:i.right_answer=0,"multiple"==a?i.marks_attainable=$(c).val()/g:i.marks_attainable=parseInt($(c).val()),f.push(i)}return console.log(f),f}function l(a){var f,g,h,b=$(".test_q_type:checked").val(),c={test_id:parseInt($(document).getUrlParam("tid")),question_index:parseInt($(document).getUrlParam("q")),question_text:"",question_type:"",no_of_choices:"",marks_attainable:"",answers:"",redirect_url:a},d=$("#test_question").val(),e=b;switch(b){case"single_choice":f=parseInt($("#single_choices_count").val()),g=parseInt($("#s_question_marks").val()),h=k("single");break;case"multiple_choice":f=parseInt($("#multiple_choices_count").val()),g=parseInt($("#m_question_marks").val()),h=k("multiple");break;default:console.log("Unknown question type")}return c.question_text=d,c.question_type=e,c.no_of_choices=f,c.marks_attainable=g,c.answers=h,console.log("Question data \n"),console.log(c),c}jQuery.fn.extend({getUrlParam:function(a){a=escape(unescape(a));var b=new Array,c=null;if("#document"==$(this).attr("nodeName"))window.location.search.search(a)>-1&&(c=window.location.search.substr(1,window.location.search.length).split("&"));else if("undefined"!=$(this).attr("src")){var d=$(this).attr("src");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}else{if("undefined"==$(this).attr("href"))return null;var d=$(this).attr("href");if(d.indexOf("?")>-1){var e=d.substr(d.indexOf("?")+1);c=e.split("&")}}if(null==c)return null;for(var f=0;f<c.length;f++)escape(unescape(c[f].split("=")[0]))==a&&b.push(c[f].split("=")[1]);return 0==b.length?null:1==b.length?b[0]:b}});var a=parseInt($("#test_max_grade").text()),c=parseInt($("#txt_marks_allocated").text()),d=0;e(),$(".test_q_type").change(function(){var a=$(this).attr("data-toggle-qid"),b=$(this).attr("value");switch(b){case"single_choice":f(a);break;case"multiple_choice":f(a);break;default:console.log("Unknown question type!")}}),$(document.body).on("input",".test_answer",function(){$(this).siblings(".test_answer_label").html($(this).val())});$("#single_choices_count").val(),$("#multiple_choices_count").val(),$(".s_que_answer_container").children().length;$(".option_count").change(function(){$(this).val()<parseInt($(this).attr("min"))?$(this).val($(this).attr("min")):$(this).val()>parseInt($(this).attr("max"))&&$(this).val($(this).attr("max"));var a=$(this).val(),b=$(this).attr("id");switch(b){case"single_choices_count":j(".s_que_answer_container",a);break;case"multiple_choices_count":j(".m_que_answer_container",a);break;default:console.log("Unknown question type selected")}});var m;$("#save_question").click(function(){m=l("#!"),$.post("handlers/db_handler.php",{action:"UpdateTestQuestion",q_data:m},function(a,b){})}),$("#prev_question").click(function(){var a=$(this).attr("data-redirect-url");m=l(a),$.post("handlers/db_handler.php",{action:"UpdateTestQuestion",q_data:m},function(b,c){window.location=a})}),$("#next_question").click(function(){var a=$(this).attr("data-redirect-url");m=l(a),$.post("handlers/db_handler.php",{action:"UpdateTestQuestion",q_data:m},function(b,c){window.location=a})}),$("#complete_test").click(function(){var a=$(this).attr("data-redirect-url");m=l(a),$.post("handlers/db_handler.php",{action:"UpdateTestQuestion",q_data:m},function(a,b){}),window.location.replace(a)}),$(".question_marks").change(function(){var e=$(this).val();c=parseInt($("#txt_marks_allocated").text()),c+=e-d,d=e,$("#txt_marks_allocated").text(c),b(c,a)})});

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