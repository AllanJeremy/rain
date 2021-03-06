<?php
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
require_once(realpath(dirname(__FILE__) . "/../handlers/date_handler.php")); #Date handler. Handles all date operations
require_once(realpath(dirname(__FILE__) . "/../handlers/grade_handler.php")); #Grade handler. Handles all grade operations
require_once(realpath(dirname(__FILE__) . "/../classes/resources.php")); #Resources class. Handles all resource related operations

$user_info = MySessionHandler::GetLoggedUserInfo();
?>
            <div class="container">
                <!--<p class="grey-text">Assignments (overview headers)</p>
                <div class="divider"></div>
                <br>-->

            <!--ASSIGNMENTS SECTION-->
            <?php
                $loggedInStudentId = $_SESSION["student_adm_no"];

                //Get all assignments that belong to the logged in teacher
                $assignments = DbInfo::GetAllStudentAssignments($loggedInStudentId);
                // var_dump($assignments);
                if(isset($section)):
                    switch($section):
                        case SECTION_ST_BASE:
            ?>
                <!--Received assignments-->
                <div class="row main-tab" id="recievedAssignmentsTab">
                <?php
                    if($assignments):
                        foreach($assignments as $assignment):
                            if($assignment["sent"]):#if the assignment is sent
                                $ass_class_name = "[Unknown Classroom]";
                                $ass_attachments = "None";
                                if($ass_class = DbInfo::ClassroomExists($assignment["class_id"]))
                                {
                                     $ass_class_name = $ass_class["class_name"];
                                }

                                if (!empty($assignment["attachments"]))
                                {
                                    $ass_attachments = $assignment["attachments"];
                                }

                                //Assignment date related functionality
                                $ass_date_due = $assignment["due_date"];
                                $ass_date_sent = $assignment["date_sent"];

                                $ass_date_diff = EsomoDate::GetDateDiff($ass_date_sent,$ass_date_due);
                                $ass_date_info = EsomoDate::GetDateInfo($ass_date_diff);

                                $date_sent_fmt = EsomoDate::GetOptimalDateTime($ass_date_sent);#formatted sent date
                                $date_due_fmt = EsomoDate::GetOptimalDateTime($ass_date_due);#formatted due date

                                $ass_due_info = EsomoDate::GetDueText($ass_date_due);

                                //Classroom
                                $classroom_name="Unknown Class";
                                if($classroom = DbInfo::ClassroomExists($assignment["class_id"]))
                                {
                                    $classroom_name = $classroom["class_name"];
                                }
                                $ass_teacher = DbInfo::GetTeacherById($assignment["teacher_id"]);

                ?>
                <div class="col card-col" data-assignment-id="">
                        <div class="card white">
                            <div class="assignment-info <?php echo $ass_due_info['due_class']?> z-depth-2"><p class="center grey-text text-lighten-3"><?php echo $ass_due_info["due_text"];?></p></div>
                            <div class="card-content">
                                <span class="card-title truncate" title="<?php echo $assignment["ass_title"]; ?>"><?php echo $assignment["ass_title"]; ?></span>

                                <ul class="collapsible " data-collapsible="accordion">
                                    <li>
                                        <div class="collapsible-header">Instructions<i class="material-icons right">arrow_drop_down</i></div>
                                        <div class="collapsible-body">
                                            <p><?php echo $assignment["ass_description"];?></p>
                                        </div>
                                    </li>
                                </ul>

                                <p>From: <span class="php-data"><?php echo "Tr. " . $ass_teacher["first_name"]." ".$ass_teacher["last_name"]; ?></span></p>
                                <p>Class: <span class="php-data"><?php echo $classroom_name;?></span></p>

                                <p>Subject: <span class="php-data">Physical education</span></p>
                                <p>Date sent: <span class="php-data"><?php echo $date_sent_fmt["date"]." at ".$date_sent_fmt["time"]; ?></span></p>
                                <p>Due date: <span class="php-data"><?php echo $date_due_fmt["date"]." at ".$date_due_fmt["time"]; ?></span></p>
                                <p>Resources:
                                <span class="php-data">
                                    <?php if ($ass_attachments=="None"): ?>
                                    No Attachments
                                    <?php else:?>
                                    <a href="_students-assignments.php?id=<?php echo $assignment["ass_id"]; ?>&sect=resources" target="_blank"><?php echo $ass_attachments;?></a>
                                    <?php endif;?>
                                </span></p>
                            </div>
                            <div class="card-action">
                                <a href="_students-assignments.php?id=<?php echo $assignment["ass_id"]; ?>&sect=assignment" class="right" target="_blank">Submit</a>
                                <br>
                            </div>
                        </div>
                    </div>
                <?php
                        endif;
                        endforeach;
                    else:#no assignments were found
                ?>
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h5 class="center-align valign grey-text " id="noAssignmentMessage">You have done all the assignments.<br>Keep it up!</h5>
                    </div>
                <?php

                    endif;
                ?>

                </div>

                <?php
                        break;
                        case SECTION_ST_ASS_SENT:
                ?>
                <!--Sent assignments -->
                <div class="row main-tab" id="sentAssignmentsTab">
                    <?php
                        $graded_subs = DbInfo::GetGradedAssSubmissions($assignments);
                        $ungraded_subs = DbInfo::GetUngradedAssSubmissions($assignments);
                    ?>

                    <!-- Graded submissions -->
                    <div class="row">
                        <div class="grey-text text-darken-1 ">
                            <h5>Graded submissions</h5>
                            <div class="divider"></div>
                        </div>
                    <?php
                        if(!@$graded_subs):
                            ErrorHandler::MsgBoxDefault("No graded assignments found. Once graded, the submissions will be found here.");
                        else:
                    ?>
                    
                    <?php
                            foreach($graded_subs as &$sub):
                                $ass = DbInfo::AssignmentExists($sub["ass_id"]);
                                $ass_teacher = DbInfo::GetTeacherById($ass["teacher_id"]);
                                
                                //TODO : Refactor these into their own functions to get classroom names ad subject names
                                $classroom_name="Unknown Class";
                                if($classroom = DbInfo::ClassroomExists($ass["class_id"]))
                                {
                                    $classroom_name = $classroom["class_name"];
                                }

                                $subject_name = "Unknown Subject";
                                if($subject = DbInfo::GetSubjectById($ass["subject_id"]))
                                {
                                    $subject_name = $subject["subject_name"];
                                }

                                $date_sent_fmt = EsomoDate::GetOptimalDateTime($ass["date_sent"]);#formatted sent date
                                $date_due_fmt = EsomoDate::GetOptimalDateTime($ass["due_date"]);#formatted due date
                                $date_submitted_fmt = EsomoDate::GetOptimalDateTime($sub["date_submitted"]);#formatted submission date
                    ?>
                    <div class="col card-col" data-assignment-id="<?php echo $sub["ass_id"];?>">
                        <div class="card white">
                            <div class="card-content">
                                <span class="card-title"><?php echo $ass["ass_title"]?></span>

                                <ul class="collapsible " data-collapsible="accordion">
                                    <li>
                                        <div class="collapsible-header">Instructions<i class="material-icons right">arrow_drop_down</i></div>
                                        <div class="collapsible-body">
                                            <p><?php echo $ass["ass_description"];?></p>
                                        </div>
                                    </li>
                                </ul>

                                <p>From: <span class="php-data"><?php echo "Tr. " . $ass_teacher["first_name"]." ".$ass_teacher["last_name"]; ?></span></p>
                                <p>Class: <span class="php-data"><?php echo $classroom_name;?></span></p>
                                <p>Subject: <span class="php-data"><?php echo $subject_name;?></span></p>
                                <p title="The date the assignment was sent">Date sent: <span class="php-data"><?php echo $date_sent_fmt["date"]." (".$date_sent_fmt["time"].")"; ?></span></p>
                                <p title="The due date for the assignment. Submit your assignment before this date to avoid being penalized">Due date: <span class="php-data"><?php echo $date_due_fmt["date"]." (".$date_due_fmt["time"].")"; ?></span></p>
                                <p title="The date you submitted the assignment">Date submitted: <span class="php-data"><?php echo $date_submitted_fmt["date"]." (".$date_submitted_fmt["time"].")"; ?></span></p>
  
                               <!-- TODO: MAKE THIS DYNAMIC -->
                               <p>Resources: <span class="php-data">
                                    <a href="#!resourceFile1" id="resourceFile">Runner.pdf</a>, 
                                    <a href="#!resourceFile2" id="resourceFile">Singer.pdf</a>
                                </span></p>
                            </div>
                            <div class="card-action center-align rain-theme-primary assignment-results">
                                <p class="white-text no-margin">Grade given: <span class="php-data"><?php echo $sub["grade"]."/".$ass["max_grade"]." ( ".floor(($sub["grade"]/$ass["max_grade"])*100)."% )";?></span></p>
                                <div class="js-assignment-comments assignment-info">
                                    <a href="#" data-root-hook="assignment" class="js-get-comments <!--deep-orange-text text-accent-3--> php-data white-text" onclick="" title="View comments for this assignment">
                                        <i class="material-icons center">message</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                            endforeach;
                        endif;
                    ?>
                    </div>

                    <!-- Awaiting grading submissions -->
                    <div class="row">
                        <div class="grey-text text-darken-1 ">
                            <h5>Awaiting grading</h5>
                            <div class="divider"></div>
                        </div>
                    <?php
                        if(!@$ungraded_subs):
                            ErrorHandler::MsgBoxDefault("No assignments waiting to be graded found. Any assignments awaiting grading will be found here.");
                        else:
                    ?>
                    
                    <?php
                            foreach($ungraded_subs as &$sub):
                                $ass = DbInfo::AssignmentExists($sub["ass_id"]);
                                $ass_teacher = DbInfo::GetTeacherById($ass["teacher_id"]);
                                
                                //TODO : Refactor these into their own functions to get classroom names ad subject names
                                $classroom_name="Unknown Class";
                                if($classroom = DbInfo::ClassroomExists($ass["class_id"]))
                                {
                                    $classroom_name = $classroom["class_name"];
                                }

                                $subject_name = "Unknown Subject";
                                if($subject = DbInfo::GetSubjectById($ass["subject_id"]))
                                {
                                    $subject_name = $subject["subject_name"];
                                }

                                $date_sent_fmt = EsomoDate::GetOptimalDateTime($ass["date_sent"]);#formatted sent date
                                $date_due_fmt = EsomoDate::GetOptimalDateTime($ass["due_date"]);#formatted due date
                                $date_submitted_fmt = EsomoDate::GetOptimalDateTime($sub["date_submitted"]);#formatted submission date
                    ?>
                    <div class="col card-col" data-assignment-id="<?php echo $sub["ass_id"];?>">
                        <div class="card white">
                            <div class="card-content">
                                <span class="card-title"><?php echo $ass["ass_title"]?></span>

                                <ul class="collapsible " data-collapsible="accordion">
                                    <li>
                                        <div class="collapsible-header">Instructions<i class="material-icons right">arrow_drop_down</i></div>
                                        <div class="collapsible-body">
                                            <p><?php echo $ass["ass_description"];?></p>
                                        </div>
                                    </li>
                                </ul>

                                <p>From: <span class="php-data"><?php echo "Tr. " . $ass_teacher["first_name"]." ".$ass_teacher["last_name"]; ?></span></p>
                                <p>Class: <span class="php-data"><?php echo $classroom_name;?></span></p>
                                <p>Subject: <span class="php-data"><?php echo $subject_name;?></span></p>
                                <p title="The date the assignment was sent">Date sent: <span class="php-data"><?php echo $date_sent_fmt["date"]." (".$date_sent_fmt["time"].")"; ?></span></p>
                                <p title="The due date for the assignment. Submit your assignment before this date to avoid being penalized">Due date: <span class="php-data"><?php echo $date_due_fmt["date"]." (".$date_due_fmt["time"].")"; ?></span></p>
                                <p title="The date you submitted the assignment">Date submitted: <span class="php-data"><?php echo $date_submitted_fmt["date"]." (".$date_submitted_fmt["time"].")"; ?></span></p>

                             <!-- TODO: MAKE THIS DYNAMIC -->
                                <p>Resources: <span class="php-data">
                                    <a href="#!resourceFile1" id="resourceFile">Runner.pdf</a>, 
                                    <a href="#!resourceFile2" id="resourceFile">Singer.pdf</a>
                                </span></p>   
                            </div>
                            <div class="card-action center-align rain-theme-primary assignment-results">
                                <div class="js-assignment-comments assignment-info">
                                    <a href="#" data-root-hook="assignment" class="js-get-comments <!--deep-orange-text text-accent-3--> php-data white-text" onclick="" title="View comments for this assignment">
                                        <i class="material-icons center">message</i> Comments
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                            endforeach;
                        endif;
                    ?>
                    </div>
                </div>

                <?php
                        break;
                        case SECTION_ST_TEST_TAKE:
                ?>
                <!--Take test-->
                <div class="row main-tab" id="takeATestTab">
                    <div class="row">
                        <h5 class="grey-text text-darken-1">Tests Available</h5>
                    </div>

                    <div class="divider"></div>

                    <?php
                        $redirect_url = "";#url the test redirects to
                        $test_id = 0; #init test_id
                        $no_of_takers = 0;#init number of takers
                        $subject = null;#init subject
                        $pass_mark = 0;

                        #Get all subjects
                        $subjects = DbInfo::GetAllSubjects();
                    if($subjects):
                        $count = 0;
                        foreach($subjects as $subject):
                            $tests = DbInfo::GetTestsBySubjectId($subject["subject_id"]);
                            if($tests):
                    ?>

                    <div class="row">
                        <h5 class="php-data" style="text-transform:uppercase;"><?php echo $subject["subject_name"]?> TESTS</h5>
                    <?php
                            foreach($tests as $test):
                                $test_id = &$test["test_id"];
                                $redirect_url = "tests.php?tid=".$test_id;
                                $subject = DbInfo::GetSubjectById($test["subject_id"]);
                                if(!$subject)
                                {
                                    $subject = "Unknown";
                                }
                                $pass_mark = GradeHandler::GetGradeInfo($test["passing_grade"],$test["max_grade"]);
                                $pass_mark["percentage"] = floor($pass_mark["percentage"]);

                                //Get the number of takers for this specific test
                                $test_results = DbInfo::GetSpecificTestResults($test_id);
                                if($test_results)
                                {
                                    $no_of_takers = $test_results->num_rows;
                                }
                                else
                                {
                                    $no_of_takers = 0;#init number of takers
                                }
                    ?>
                        <div class="col s12 m6 l4 take_test_container" data-test-id="<?php echo $test_id;?>">
                            <div class="card blue-grey darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title truncate"><?php echo $test["test_title"];?></span>
                                    <p>Subject: <span class="php-data"><?php echo $subject["subject_name"];?></span></p>
                                    <p>Questions: <span class="php-data"><?php echo $test["number_of_questions"]?></span></p>
                                    <p>Time: <span class="php-data"><?php echo $test["time_to_complete"]?> min</span></p>
                                    <p>Difficulty: <span class="php-data"><?php echo $test["difficulty"]?></span></p>
                                    <p>Pass mark: <span class="php-data"><?php echo $pass_mark["percentage"]."%";?></span></p>
                                    <p class="students-taken php-data"><i>This test has been taken <?php echo $no_of_takers;?> time(s)</i></p>
                                </div>
                                <div class="card-action right-align">
                                    <a href="<?php echo 'tests.php?tid='.$test_id?>" class="btn btn-flat blue-grey-text text-lighten-4">Take Test</a>
                                </div>
                            </div>
                        </div>
                    <?php
                            endforeach;
                            //Close the row container and add a divider after every subject's tests
                    ?>
                    </div>
                    <div class="divider"></div>
                    <?php
                            else:#No tests were found
                                if($count == 0):
                    ?>
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h5 class="center-align valign grey-text " id="noAssignmentMessage">No tests available. When tests are added, they will appear here.</h5>
                    </div>
                    <?php
                                endif;
                            endif;#if tests found
                            $count++;
                        endforeach;

                    else:#if subjects were not found
                    ?>
                        <p class="red-text">Could not retrieve subjects</p>
                    <?php
                    endif;#if subjects found
                    ?>
                </div>

                <?php
                        break;
                        case SECTION_ST_TEST_RESULTS:
                ?>
                <!--Test results-->
                <div class="row main-tab" id="testResultsTab">
                    <?php
                        $results = DbInfo::GetSpecificAccountResults($user_info);

                        //If results were found
                        if(@$results && $results->num_rows>0):
                    ?>
                    <p class="grey-text">Test results</p>
                    <!--<hr>-->
                    <table class="table bordered highlight responsive-table">
                        <tr>
                            <th>Test</th>
                            <th>Difficulty</th>
                            <th>Total marks</th>
                            <th>Marks Achieved</th>
                            <th>Grade</th>
                            <th>Verdict</th>
                            <th>Date taken</th>
                            <th>Download</th>
                        </tr>

                        <?php
                            foreach($results as $result):

                                $test = DbInfo::TestExists($result["test_id"]);

                                $test_name = "Unknown test";
                                $difficulty = "Unknown difficulty";
                                $total_marks = 100;

                                if($test)
                                {
                                    $test_name = $test["test_title"];
                                    $difficulty = $test["difficulty"];
                                    $total_marks = $test["max_grade"];
                                }

                                // $results_html = ;
                                $test_result_date = EsomoDate::GetDateInfo($result["date_generated"]);
                        ?>
                        <tr>
                            <td><?php
                                echo $test_name;
                            ?></td>
                            <td><?php
                                echo $difficulty;
                            ?></td>
                            <td><?php
                                 echo $total_marks;
                            ?></td>
                            <td><?php
                                echo $result["grade"];
                            ?></td>
                            <td><?php
                                echo $result["grade_text"];
                            ?></td>
                            <td><?php
                                echo $result["verdict"];
                            ?></td>
                            <td><?php
                                //Mon 5th May, 2016
                                echo $result["date_generated"];
                            ?></td>
                            <td>
                                <a href="javascript:void(0)" class="download-test-result btn btn-flat" title="Download results for <?php echo $test_name;?>"><i class="material-icons">archive</i></a>
                            </td>
                        </tr>
                        <?php
                            endforeach;
                        ?>
                    </table>
                    <?php
                        else:#No test results found
                    ?>
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h6 class="center-align valign grey-text " id="testResultsMessage">
                            No test results found. When you take a test, results will be available here
                        </h6>
                    </div>
                    <?php
                        endif;
                    ?>
                </div>

                <?php
                        break;
                        case SECTION_RESOURCES:
                ?>
                <!--Resources-->
                <div class="row main-tab" id="studentResourcesTab">
                    <?php
                        // TODO: [OPTIMIZATION] Could Create a function for quick retrieval of resources (use 1 column in database and limit selection length. Or check for whether values exist or not and return true or false)
                        //If there are resources available
                        if($resources = DbInfo::GetAllResources())
                        {
                            EsomoResource::DisplayResources();
                        }
                        else#Resources not found
                        {
                            EsomoResource::DisplayMissingDataMessage();
                        }
                    ?>
                </div>

                <?php
                        break;
                        case SECTION_ACCOUNT:
                ?>
                <!--Account-->
                <div class="row main-tab" id="studentAccountTab">
                    <div class="row no-bottom-margin">
                        <div class="col s12">
                            <p class="grey-text">Your account</p>
                        </div>

                    </div>
                    <?php
                    if ($user_info['using_original_pass']) :
                    ?>
                    <div class="row js-new-account-alert-card">
                        <div class="col s12">

                            <div class="card horizontal" style="
                            background-color: #6A1B9A;
                        ">
                              <div class="card-image" style="/* max-height: 300px; *//* width: auto; *//* overflow: hidden; *//* display: block; */">
                                <img src="images/stairway.jpg" style="
                            height: 100%;
                            width: 100%;
                            position: relative;
                            overflow: hidden;
                        ">
                              </div>
                              <div class="card-stacked">
                                <div class="card-content">

                                <h3 class="header white-text">Welcome Gabriel</h3><h5 class="white-text php-data">Vulnerable account.</h5><p class="white-text">Gabriel your username is your password, change your password in this tab
                        to secure your account.</p></div>
                                <div class="card-action">
                                  <a href="#">Help me</a>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                    <?php
                    endif;
                    ?>
                    <div class="row">
                        <br>
                        <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                            <h6 class="center-align valign grey-text " id="changePasswordMessage">
                                Change your password here
                                <br>
                                Note : Passwords must be at least 8 characters long
                                <br>
                                Tip: For security, we recommend that you change your password if you are using the default password
                            </h6>
                        </div>

                        <!--Change password-->
                        <div class="col s12">
                            <br>
                            <form class="form account_form">
                                <div class="input-container col s12 m4">
                                    <label for="oldPassword">Old Password</label>
                                    <input type="password"  id="oldPassword" placeholder="Old password">
                                </div>
                                <div class="input-container col s12 m4">
                                    <label for="newPassword">New Password</label>
                                    <input type="password"  id="newPassword" placeholder="New password">
                                </div>
                                <div class="input-container col s12 m4">
                                    <label for="confirmNewPassword">Confirm Password</label>
                                    <input type="password"  id="confirmNewPassword" placeholder="Confirm password">
                                </div>
                                <div class="input-container col s12">
                                    <a class="btn right" href="javascript:void(0)" id="btn_change_password">Change password</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
                <?php
                        break;
                        default:
                ?>
                <script>window.location = "<?php echo GetSectionLink(SECTION_ST_BASE);?>";</script>
                <?php
                        endswitch;
                    else:#No section specified
                ?>
                <script>window.location = "<?php echo GetSectionLink(SECTION_ST_BASE);?>";</script>
                <?php
                    /*ACCOUNT SECTION LOGIC*/
                    //check if the passwords are set
                    //check if the passwords are within the accepted length
                    //check if the passwords match
                    //check if the old password is the valid old password
                    //if all these tests are valid, change the password

                    endif;
                ?>
            </div>
