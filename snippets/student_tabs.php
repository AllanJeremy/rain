<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
require_once(realpath(dirname(__FILE__) . "/../handlers/date_handler.php")); #Date handler. Handles all date operations
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
                ?>  
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
                                <span class="card-title"><?php echo $assignment["ass_title"]; ?></span>

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
                                <p>Due date: <span class="php-data"><?php echo $date_due_fmt["date"]." at ".$date_sent_fmt["time"]; ?></span></p>
                                <p>Resources: 
                                <span class="php-data">
                                    <?php if ($ass_attachments=="None"): ?>
                                    No Attachments
                                    <?php else:?>
                                    <a href="#!resourceFile1" id="resourceFile"><?php echo $ass_attachments;?></a>
                                    <?php endif;?>
                                </span></p>
                            </div>
                            <div class="card-action">
                                <a href="#!"  id="submitAssignment" class="right">Submit</a>
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

                <!--Sent assignments-->
                <div class="row main-tab" id="sentAssignmentsTab">
                    <div class="col card-col" data-assignment-id="">
                        <div class="card white">
                            <div class="assignment-info right-align"><a href="#" class="deep-orange-text text-accent-3" onclick="")><i class="material-icons">message</i> 12</a></div>
                            <div class="card-content">
                                <span class="card-title">Assignment title</span>

                                <ul class="collapsible " data-collapsible="accordion">
                                    <li>
                                        <div class="collapsible-header">Instructions<i class="material-icons right">arrow_drop_down</i></div>
                                        <div class="collapsible-body">
                                            <p>1. Make a girl run three times using your 
fingers only then write a report of 2,500 words 
about how you came to achieve this.<br>2. Make a boy sing three times using your 
boobs only then write a report of 1,000 words 
about how you came to achieve this.</p>
                                        </div>
                                    </li>
                                </ul>
        
                                <p>From: <span class="php-data">Tr.Jessica</span></p>
                                <p>Subject: <span class="php-data">Physical education</span></p>
                                <p>Date sent: <span class="php-data">24th Aug 2016</span></p>
                                <p>Date handed in: <span class="php-data">1st Sept 2016</span></p>
                                <p>Due date: <span class="php-data">2nd Sept 2016</span></p>
                                <p>Resources: <span class="php-data"><a href="#!resourceFile1" id="resourceFile">Runner.pdf</a>, <a href="#!resourceFile2" id="resourceFile">Singer.pdf</a></span></p>
                            </div>
                            <div class="card-action center-align brookhurst-theme-primary assignment-results">
                                <p class="white-text">Grade given: <span class="php-data">90%</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row main-tab" id="takeATestTab">
                    <div class="row" id="tests">
                        <!-- LOAD TEST CARDS HERE -->
                        <div class="col card-col">
                            <div class="card blue-grey darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Test Title</span>
                                    <p>Subject: <span class="php-data">History</span></p>
                                    <p>Questions: <span class="php-data">30</span></p>
                                    <p>Time: <span class="php-data">2 hrs 40 min</span></p>
                                    <p>Difficulty: <span class="php-data">Average</span></p>
                                    <p>Pass mark: <span class="php-data">78%</span></p>
                                    <p class="students-taken php-data"><i>30 students in your class have taken this test</i></p>
                                </div>
                                <div class="card-action right-align">
                                    <a href="tests.php">Take test</a>
                                </div>
                            </div>
                        </div>
                        <div class="col card-col">
                            <div class="card indigo darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Test Title</span>
                                    <p>Subject: <span class="php-data">History</span></p>
                                    <p>questions: <span class="php-data">30</span></p>
                                    <p>Time: <span class="php-data">2 hrs 40 min</span></p>
                                    <p>Difficulty: <span class="php-data">Average</span></p>
                                    <p>Pass mark: <span class="php-data">78%</span></p>
                                    <p class="students-taken php-data"><i>30 students in your class have taken this test</i></p>
                                </div>
                                <div class="card-action right-align">
                                    <a href="tests.php">Take test</a>
                                </div>
                            </div>
                        </div>
                        <div class="col card-col">
                            <div class="card green darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Test Title</span>
                                    <p>Subject: <span class="php-data">History</span></p>
                                    <p>questions: <span class="php-data">30</span></p>
                                    <p>Time: <span class="php-data">2 hrs 40 min</span></p>
                                    <p>Difficulty: <span class="php-data">Average</span></p>
                                    <p>Pass mark: <span class="php-data">78%</span></p>
                                    <p class="students-taken php-data"><i>30 students in your class have taken this test</i></p>
                                </div>
                                <div class="card-action right-align">
                                    <a href="tests.php">Take test</a>
                                </div>
                            </div>
                        </div>
                        <div class="col card-col">
                            <div class="card pink darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Test Title</span>
                                    <p>Subject: <span class="php-data">History</span></p>
                                    <p>questions: <span class="php-data">30</span></p>
                                    <p>Time: <span class="php-data">2 hrs 40 min</span></p>
                                    <p>Difficulty: <span class="php-data">Average</span></p>
                                    <p>Pass mark: <span class="php-data">78%</span></p>
                                    <p class="students-taken php-data"><i>30 students in your class have taken this test</i></p>
                                </div>
                                <div class="card-action right-align">
                                    <a href="tests.php">Take test</a>
                                </div>
                            </div>
                        </div>
                        <div class="col card-col">
                            <div class="card brown darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Test Title</span>
                                    <p>Subject: <span class="php-data">History</span></p>
                                    <p>questions: <span class="php-data">30</span></p>
                                    <p>Time: <span class="php-data">2 hrs 40 min</span></p>
                                    <p>Difficulty: <span class="php-data">Average</span></p>
                                    <p>Pass mark: <span class="php-data">78%</span></p>
                                    <p class="students-taken php-data"><i>30 students in your class have taken this test</i></p>
                                </div>
                                <div class="card-action right-align">
                                    <a href="tests.php">Take test</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Test results-->
                <div class="row main-tab" id="testResultsTab">
                    <p>Test results will be displayed here</p>
                </div>

                <!--Grades-->
                <div class="row main-tab" id="myGradesTab">
                    <p>Your grades will be displayed here</p>
                </div>

                <!--Gradebooks-->
                <div class="row main-tab" id="gradeBookTab">
                    <p>Your Grade books will be displayed here</p>
                </div>
                <div class="row main-tab" id="chatTab">
                    <p>Chat will be displayed here</p>
                </div>
                <!--<p class="grey-text">Tests</p>
                <div class="divider"></div>-->
                <br>
                
            </div>
