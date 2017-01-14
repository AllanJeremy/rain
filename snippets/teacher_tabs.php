<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
require_once(realpath(dirname(__FILE__) . "/../handlers/date_handler.php")); #Date handler. Handles all date operations
?>
 <?php 
    $loggedInTeacherId = $_SESSION["admin_acc_id"];
 ?>           
            <div class="container">
                <!--CLASSROOMS SECTION-->
                <div class="row main-tab active-bar" id="classroomTab">
                    
                    <div class="row no-margin">
                        <div class="col s5">
                            <p class="grey-text">Your classrooms</p>
                        </div>
                        <div class="col s7">
                            <a class="btn right" id="createClassroom"><span class="hide-on-small-only">Create a classroom</span>
                            <span class="hide-on-med-and-up">
                            <i class="material-icons">add</i>
                            </span>
                            </a>
                        </div>
<!--
                        <div class="col s1">
                            <a class="btn-flat transparent btn center dropdown-button" data-beloworigin="false" href="#" data-activates="moreHoriz1"><i class="material-icons">more_vert</i></a>
                        </div>
                        
                        <ul id="moreHoriz1" class="dropdown-content">
                            <li class="waves-effect waves-green"><a class="more-card-options black-text" id="moreCardDelete"><i class="material-icons red-text">delete</i> delete</a></li>
                        </ul> 
-->
                        
                    </div>
                    

                    <div class="divider"></div>
                    <br>
                    
                    <?php
                        if ($classrooms = DbInfo::GetSpecificTeacherClassrooms($loggedInTeacherId)):   
                     ?>
                    <div class="row"id="classroomCardList" >
                    <?php

                       $reversed_classrooms = DbInfo::ReverseResult($classrooms);#an array that has the reversed values of the array, newest is the first
                        $count = 0;
                        foreach($reversed_classrooms as $classroom):
                            $student_ids = DbInfo::GetArrayFromList($classroom["student_ids"]);#array of the student ids
                            $student_count = count($student_ids); 
                            $subject_name = "Undefined subject";#default values incase we don't find the subject
                            $stream_name = "Undefined stream";#default values incase we don't find the stream

                            $assignments = array();
                            $ass_count = 0;
                            //If the subject is found in the database - set the appropriate subject name
                            if($subject = DbInfo::GetSubjectById($classroom["subject_id"]))
                            {
                                $subject_name = $subject["subject_name"];
                            }
                            
                            //If the stream is found in the database - set the appropriate stream name
                            if($stream = DbInfo::GetStreamById($classroom["stream_id"]))
                            {
                                $stream_name = $stream["stream_name"];
                            }

                            //Only return the assignments that belong to this teacher
                            if($assignments = DbInfo::GetTeacherAssInClass($classroom['class_id'],$loggedInTeacherId))
                            {
                                $ass_count = count($assignments);
                            }
                     ?> 
                        <div class="col card-col <?php if($count < 1) { echo 'new-class'; } ?>" data-classroom-id="<?php echo $classroom['class_id'] ?>">
                            <div class="card <?php echo $classroom['classes'] ?>">
                                <div class="card-content white-text">
                                    <span class="card-title"><?php echo $classroom['class_name'] ?></span>
                                    <p>Number of students:
                                        <span class="php-data"><?php echo $student_count; ?>  
                                            <a id="openStudentsClassList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of students in this classroom" href="#!
                                            " >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Assignments sent:
                                        <span class="php-data"><?php echo $ass_count;?>
                                            <a id="openAssignmentsClassList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of assignments sent to this classroom" href="#!" >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Subject: <span class="php-data"><?php echo $subject_name ?></span></p>
                                    <p>Stream:  <span class="php-data"><?php echo $stream_name ?></span></p>
                                </div>
                                <div class="card-action">
                                    <a href="#!" id="editClassroom">Edit</a>
                                    <a href="#!" >View</a>
<!--                                    <a class=" transparent php-data white-text right dropdown-button" data-beloworigin="false" href="#" data-activates="moreHoriz1"><i class="material-icons">more_vert</i></a>-->
                                </div>
                            </div>
                        </div>
                        <?php $count += 1; ?>
                    <?php 
                        endforeach;
                        unset($student_ids,$stream,$stream_name,$subject,$subject_name);#unset variables used in foreach
                    ?> 
                    </div>
                    <?php 
                    else: ?>
                          
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h5 class="center-align valign grey-text " id="noClassroomMessage">You don't have any classroom.<br><br><br><a class="btn btn-flat" id="createClassroom">Create one</a></h5>
                    </div>

                   <?php endif ?>
                </div>
                
                <!--ASSIGNMENTS SECTION-->
                <?php
                    //Get all assignments that belong to the logged in teacher
                    $assignments = DbInfo::GetSpecificTeacherAssignments($loggedInTeacherId);
                ?>  
                <div class="row main-tab" id="createAssignmentsTab">
                    <br>
                    <br>
                    <div class="container assignment-doc">
                        <br>
                        <form action="" id="createAssignmentForm" class="row">
                            <div class=" input-field col s12 ">
                                <input required class="validate" type="text" name="new_assignment_name" id="newAssignmentName">
                                <label for="new_assignment_name">Assignment name</label>
                            </div>
                            <div class=" input-field col s12 ">
                                <textarea id="assignmentInstructions" class="materialize-textarea"></textarea>
                                <label for="assignmentInstructions">Assignment instructions</label>
                            </div>
<!--
                            <div class=" input-field col s6 ">
                                <p>
                                    <input type="checkbox" id="addStudentsToAssignment" name="add_students_to_assignment" value="GetAllStudents" />
                                    <label for="addStudentsToAssignment">Add students to send assignment to</label>
                                </p>
                            </div>
-->
                            <div class=" input-field col s12 ">
                                <p>
                                    <input type="checkbox" id="addClassroomToAssignment" name="add_classroom_to_assignment" value="GetSpecificTeacherClassrooms" />
                                    <label for="addClassroomToAssignment">Add classroom to send assignment to</label>
                                </p>
                            </div>
                            <div class="col s12 classroom-list input-field"></div>
                            <div class=" input-field col s12 ">
                                
                                <input type="date" class="datepicker" id="assDueDate">
                                <label for="assDueDate">Due date</label>
                            </div>
                            
                            <div class=" input-field col s12 file-field ">
                                
                                <div class="btn">
                                    <span>resources</span>
                                    <input type="file" multiple>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Upload one or more files">
                                </div>
                            </div>
                            <div class="input-field col s12">
                            <br>
                                <a type="submit" class="btn right" id="createNewAssignment">Create assignment</a>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>

                <!--Sent assignments-->
                <div class="row main-tab" id="sentAssignmentsTab">
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
        
                                <p>From: <span class="php-data"><?php echo "Tr. " . $_SESSION["admin_first_name"] . " ". $_SESSION["admin_last_name"] ?></span></p>
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
                                <a href="#!" id="editClassroom">Edit</a>
                                <a href="#!" class="right">Call Back</a>
    <!--                                    <a class=" transparent php-data white-text right dropdown-button" data-beloworigin="false" href="#" data-activates="moreHoriz1"><i class="material-icons">more_vert</i></a>-->
                            </div>
                        </div>
                    </div>
 <!--
                <div class="col s12 m6 l4">
                    <div class="card teal">
                        <div class="card-content white-text">
                            <span class="card-title"></span>
                            <p>Class:
                                <span class="php-data">
                                    <?php echo $ass_class_name; ?>  
                                    <a id="openSentAssignmentList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Classroom the assignment was sent to" href="#!" >
                                        <i class="material-icons">info</i>
                                    </a>
                                </span> 
                            </p>
                            <p>Instructions:
                                <span class="php-data"><?php echo $assignment["ass_description"];?>
                                </span> 
                            </p>
                            <p>Date sent: <span class="php-data"></span></p>
                            <p>Due date: <span class="php-data"></span></p>
                            <p>Attachments:  <span class="php-data"><?php echo $assignment["attachments"]; ?></span> </p>
                        </div>

                    </div>
                </div>
-->
                <?php
                            endif;
                        endforeach;
                    else:#no assignments were found
                ?>
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h5 class="center-align valign grey-text " id="noSentAssignmentMessage">You haven't sent any assignments yet.<br><br><br><a class="btn btn-flat" id="createClassroom">Create one</a></h5>
                    </div>
                <?php
                    endif;
                ?>   
                </div>

                <!--Submitted assignments-->
                <div class="row main-tab" id="submittedAssignmentsTab">  
                    <?php
                        if($assignments): 
                            foreach($assignments as $assignment):
                                if($ass_submissions=DbInfo::GetAssSubmissionsByAssId($assignment["ass_id"])):
                                    foreach ($ass_submissions as $ass_submission):

                                        //If the assignment has been submitted and not been returned to the student
                                        if($ass_submission["submitted"] && !$ass_submission["returned"]):
                                            $ass_title = $assignment["ass_title"];

                                            $std_name = "Unknown Student";#Name of student the submission belongs to

                                            if($student = DbInfo::StudentIdExists($ass_submission["student_id"]))
                                            {
                                                $std_name = $student["full_name"];
                                            }
                                            
                                            $submission_title = $std_name . "'s ". $ass_title . " Submission";
                                            $submission_attachments = "None";
                                            $submission_description = "No description";

                                            //If the submission has a title, use it, otherwise use the generated assignment one
                                            if(!empty($ass_submission["submission_title"]) && isset($ass_submission["submission_title"]))
                                            {
                                                $submission_title = $ass_submission["submission_title"];
                                            }

                                            //If there are attachments, then set submission_attachments to the attachments
                                            if (!empty($ass_submission["attachments"]) && isset($ass_submission["attachments"]))
                                            {
                                                $submission_attachments = $ass_submission["attachments"];
                                            }

                                            //If there is a description set the description variable
                                            if (!empty($ass_submission["submission_text"]) && isset($ass_submission["submission_text"]))
                                            {
                                                $submission_description = $ass_submission["submission_text"];
                                            }

                    ?>
                    <div class="col s12">
                        <div class="card teal">
                            <div class="card-content white-text">
                                <span class="card-title"><?php echo $submission_title; ?></span>
                                <p>Assignment:
                                    <span class="php-data">
                                        <?php echo $ass_title; ?>  
                                    </span> 
                                </p>
                                <p>Student:
                                    <span class="php-data"><?php echo $std_name;?>
                                        <a id="openAssignmentsClassList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Student that submitted the assignment" href="#!" >
                                            <i class="material-icons">info</i>
                                        </a>
                                    </span> 
                                </p>
                                <p>Description: <span class="php-data"><?php echo $submission_description; ?></span></p>
                                <p>Attachments:  <span class="php-data"><?php echo $submission_attachments; ?></span> </p>
                            </div>
                            <div class="card-action">
                                <a href="#!" id="editClassroom">Comment</a>
                                <a href="#!" class="right">Return</a>
    <!--                                    <a class=" transparent php-data white-text right dropdown-button" data-beloworigin="false" href="#" data-activates="moreHoriz1"><i class="material-icons">more_vert</i></a>-->
                            </div>
                        </div>
                    </div>

                    <?php 
                        else:
                    ?>
                    
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h5 class="center-align valign grey-text " id="noReceivedAssSubmissionsMessage">No assignment submissions found.<br><br> When students submit assignments they will appear here</h5>
                    </div>  
                    
                    <?php
                        endif;
                        break;
                        endforeach;#ass_submissions
                        else:#if there are no assignment submissions
                    ?>
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h5 class="center-align valign grey-text " id="noReceivedAssSubmissionsMessage">No assignment submissions found.<br><br> When students submit assignments they will appear here</h5>
                    </div>               
                    <?php
                        endif;#ass_submissions
                        break;
                        
                        endforeach;#assignments
                        
                        else:#if no assignments were found
                    ?>
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h5 class="center-align valign grey-text " id="noReceivedAssSubmissionsMessage">No assignment submissions found.<br><br> When students submit assignments they will appear here</h5>
                    </div>     
                    <?php 
                        endif;#assignments
                    ?>
                </div>
                <!--TESTS SECTION-->
                <?php
                    $subjects_found = DbInfo::GetAllSubjects();
                ?>
                <!--Create a test-->
                <div class="row main-tab" id="createTestTab">
                    <div class="col s12">
                        <p class="grey-text">Create test</p>
                        <div class="divider"></div>
                    <br>
                    </div>

                    <div class="container">
                        <form action="" id="createTestForm" class="row">
                                <div class=" input-field col s12 m6">
                                    <input type="text" id="createTestTitle" name="create_test_title" class="validate" required></input>
                                    <label for="createTestTitle">Title</label>
                                </div>
                                <div class=" input-field col s12 m6">
                                    <select id="createTestSubject">
                                        <?php 
                                            foreach($subjects_found as $subject):
                                        ?>
                                        <option value="<?php echo $subject['subject_id']?>"><?php echo $subject["subject_name"] ?></option>
                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                    <label for="createTestSubject">Subject</label>
                                </div>
                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestQuestionCount" name="create_test_question_count"  min="1" max="50" value="10" class="validate" required></input>
                                    <label for="createTestQuestionCount">No. of questions</label>
                                </div>

                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestMaxGrade" name="create_test_max_grade" min="10" max="100" value="100" class="validate" required></input>
                                    <label for="createTestMaxGrade">Max grade</label>
                                </div>
                                <div class=" input-field col s12 m4">
                                    <select id="createTestDifficulty" name="create_test_difficulty" class="validate" required>
                                        <option value="1">Very Easy</option>
                                        <option value="2">Easy</option>
                                        <option value="3">Moderate</option>
                                        <option value="4">Difficult</option>
                                    </select>
                                    <label for="createTestDifficulty">Difficulty</label>
                                </div>
                                <div class=" input-field col s12 ">
                                    <textarea id="createTestInstructions" class="materialize-textarea"></textarea>
                                    <label for="createTestInstructions">Assignment instructions</label>
                                </div>
                                
                                <button type="submit" class="btn col s10 m4 right pull-s1">Create Test</button>
                        </form>
                        </div>
                    </div>

                <!--Test results-->
                <div class="row main-tab" id="viewStudentsTestResultTab">
                    test results tab
                </div>

                <!--Take a test-->
                <div class="row main-tab" id="takeTestTab">
                    Take a test tab
                </div>

                <!--GRADES SECTION-->
                <div class="row main-tab" id="mySubjectGradesTab">
                    myGrades tab
                </div>
                <div class="row main-tab" id="gradeBookTab">
                    gradeBook tab
                </div>

                <!--SCHEDULE SECTION-->
                <div class="row main-tab" id="schedulesTab">
                    schedules tab
                </div>

                <!--MY ACCOUNT SECTION-->
                <div class="row main-tab" id="myAccountTab">
                    myAccount tab
                </div>
                
            </div>