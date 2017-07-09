<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
require_once(realpath(dirname(__FILE__) . "/../handlers/date_handler.php")); #Date handler. Handles all date operations
require_once(realpath(dirname(__FILE__) . "/../classes/uploader.php")); #Uploader class
require_once(realpath(dirname(__FILE__) . "/../classes/resources.php")); #Uploader class
?>

<?php 
    $user_info = MySessionHandler::GetLoggedUserInfo();
    $loggedInTeacherId = $user_info["user_id"];

    if(isset($section) && !empty($section)):
        $subjects_found = DbInfo::GetAllSubjects();
        //Get all assignments that belong to the logged in teacher
        $assignments = DbInfo::GetSpecificTeacherAssignments($loggedInTeacherId);
        $assignments = DbInfo::ReverseResult($assignments);
        $classrooms = DbInfo::GetSpecificTeacherClassrooms($loggedInTeacherId);
?>           
            <div class="container">
            
            <?php
                switch($section):
                    case SECTION_TR_BASE:
            ?>
                <!--CLASSROOMS SECTION-->
                <div class="row main-tab" id="classroomTab">
                    
                    <div class="row no-bottom-margin">
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
                        if ($classrooms):   
                     ?>
                    <div class="row" id="classroomCardList">
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
                                            <a id="openStudentsClassList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of students in this classroom" href="javascript:void(0)" >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Assignments sent:
                                        <span class="php-data"><?php echo $ass_count;?>
                                            <a id="openAssignmentsClassList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of assignments sent to this classroom" href="javascript:void(0)" >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Subject: <span class="php-data"><?php echo $subject_name ?></span></p>
                                    <p>Stream:  <span class="php-data"><?php echo $stream_name ?></span></p>
                                </div>
                                <div class="card-action">
                                    <a href="javascript:void(0)" id="editClassroom">Edit</a>
                                    <a href="javascript:void(0)" >View</a>
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
                    break;
                    case SECTION_TR_ASS_CREATE:#Create assignments
                ?>
                <!--Create assignment-->
                <div class="row main-tab" id="createAssignmentsTab">
                    <br>
                    <br>
                    <div class="container assignment-doc">
                        <br>
                        <form action="" id="createAssignmentForm" class="row" enctype="multipart/form-data" method="POST">
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
                            <div class=" input-field col s6 ">
                                
                                <input type="date" class="datepicker" id="assDueDate" name="ass_due_date">
                                <label for="assDueDate">Due date</label>
                            </div>
                            <div class=" input-field col s6 ">

                                <input type="number" id="assMaxGrade" value="100" name="ass_max_grade">
                                <label for="assMaxGrade">Max grade</label>
                            </div>
                            
                            <div class=" input-field col s12 file-field ">
                                
                                <div class="btn">
                                    <span>resources</span>
                                    <input type="file" multiple name="ass_resources">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Upload one or more files">
                                </div>
                            </div>
                            <div class="input-field col s12">
                            <br>
                                <button type="submit" class="btn right" id="createNewAssignment">Create assignment</button>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>

                <?php
                    break;
                    case SECTION_TR_ASS_SENT:#Sent assignments
                ?>
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
                                <span class="card-title truncate" title="<?php echo $assignment["ass_title"];?>"><?php echo $assignment["ass_title"]; ?></span>

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
                                    <a href="_students-assignments.php?id=<?php echo $assignment["ass_id"]; ?>&sect=resources" target="_blank"><?php echo $ass_attachments;?></a>
                                    <?php endif;?>
                                </span></p>
                            </div>
                            <div class="card-action">
                                <a href="javascript:void(0)" class="js-edit-assignment">Edit</a>
                                <a href="javascript:void(0)" class="right">Call Back</a>
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
                                    <a id="openSentAssignmentList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Classroom the assignment was sent to" href="javascript:void(0)" >
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

                <?php
                    break;
                    case SECTION_TR_ASS_SUBS:#Assignment submissions
                ?>
                <!--Submitted assignments-->
                <div class="row main-tab" id="submittedAssignmentsTab">  
                    <?php
                        if($classrooms):
                    ?>
                    <div class="row" id="classroomCardsContainer">

                        <?php
                            $count=0;#Iterator for the foreach loop below (looping through classrooms)

                            //for each classroom found, create a classroom card
                            foreach($classrooms as $classroom):
                                #unique container id, used for controlling display of assignment containers in js
                                $container_id = "ass_classroom_".$classroom["class_id"];

                                $selected_class = "";#Selected class, determines if the card is 'selected' visually
                                if($count==0)
                                {
                                    $selected_class = "selected";
                                }
                                $count++;#increment the iterator named $count
                        ?>
                        <!--CARD_TEMPLATE_START-->
                        <div class="col s12 m6 l4 card-col ass-classroom-card <?php echo $selected_class;?>" data-content-trigger="<?php echo $container_id;?>">
                            <div class=" card tiny  <?php echo $classroom['classes'].' '.$selected_class;?> hoverable" title="<?php echo $classroom['class_name']?>" >
                                <div class="card-content row">
                                    <span class="card-title white-text truncate col s8">
                                        <?php echo $classroom['class_name']?>
                                    </span>
                                    <span class="new badge col s4 hide">4</span>
                                </div>
                            </div>
                        </div>
                        <!--CARD_TEMPLATE_END-->
                        <?php
                            endforeach;#End looping through  classrooms
                        ?>
                        
                    </div>
                    <div class="divider"></div>

                    <?php
                        //Find assignments sent to each classroom
                        foreach($classrooms as $classroom):
                            $class_id = &$classroom["class_id"];
                            $class_name = &$classroom["class_name"];

                            #unique container id, used for controlling display of assignment containers in js
                            $container_id = "ass_classroom_".$class_id;
                            
                            #assignments in this classroom
                            $ass_in_classroom = DbInfo::GetTeacherAssInClass($class_id,$loggedInTeacherId);
                    ?>
                        <div class="row classroom-ass-container hide" id="<?php echo $container_id;?>">
                    <?php
                            //If there are any assignments in the classroom, display them, if not display appropriate message
                            if($ass_in_classroom):
                    ?>
                            <div class="col s12" id="header">
                                <p class="title">Assignments for <i><?php echo $class_name;?></i></p>
                            </div>
                            <div class="col s12 body">
                                <!--PERMISSION-->
                                <ul class="collapsible popout" data-collapsible="accordion">
                                    <?php
                                        //Returned assignments
                                        $returned_ass = DbInfo::GetReturnedAssSubmissions($loggedInTeacherId);

                                        //Unreturned assignments
                                        $unreturned_ass = DbInfo::GetUnreturnedAssSubmissions($loggedInTeacherId);

                                        //Loop through all assignments and display them
                                        foreach($ass_in_classroom as $ass):
                                            $ass_title = $ass["ass_title"];
                                            $ass_description = $ass["ass_description"];
                                            $ass_id = $ass["ass_id"];
                                    ?>
                                    <!--TEMPLATE_START-->
                                    <li class="js-assignment-collapsible" data-assignment-id="<?php echo $ass_id; ?>">
                                        <div class="collapsible-header ">
                                            <span><?php echo $ass_title;?></span>
                                            <div class="right hide">
                                                <span class="margin-horiz-8 badge new">0</span>
                                                <p class="margin-horiz-8 right">
                                                    <span class="js-submitted">0</span> submitted
                                                </p>
                                                <p class="margin-horiz-8 right">
                                                    <span class="js-not-submitted">0</span> not submitted
                                                </p>
                                            </div>
                                            <div class="row">
                                                <div class="col s12 m8">
                                                    <span class="no-margin line-height-0">Description</span>
                                                    <p class="js-assignment-description no-margin line-height-0"><?php echo $ass_description;?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Assignment submissions-->
                                        <div class="collapsible-body">

                                        <?php
                                            $ass_submissions = DbInfo::GetAssSubmissionsByAssId($ass_id);
                                            $no_submissions_message = "No new assignment submissions were found.";

                                            //Check if assignment submission exist for this specific assignment
                                            if($ass_submissions): #if assignment submissions were found

                                                //Check if there are any returned assignments ~ if there are, show the return button
                                                $returned_btn_disabled = "disabled";

                                                //If there are any returned assignments ~ enable the returned button
                                                if($returned_ass && $returned_ass->num_rows>0)
                                                {
                                                    $returned_btn_disabled = "";
                                                }

                                        ?>
                                            <div class="filter-bar pad-8">
                                                <a class="btn btn-flat btn-small <?php echo $returned_btn_disabled;?>" <?php echo $returned_btn_disabled;?>>Returned</a>
                                            </div>

                                            <div class="row submitted-assignment-list padding-horiz-16">
                                                <div class="new-submissions col s12 padding-horiz-8">
                                                    <div class="header ">
                                                        <p class="pad-8">New submissions</p>
                                                        <div class="divider margin-horiz-16"></div>
                                                    </div>
                                                    <ul class="row ass-submission-container">

                                        <?php
                                            //If there are any unreturned assignment submissions
                                            if($unreturned_ass && $unreturned_ass->num_rows>0):
                                                //Loop through each assignment submission for this assignment
                                                foreach($ass_submissions as $ass_sub):
                                                    $student_id = $ass_sub["student_id"];
                                                    $student = DbInfo::GetStudentByAccId($student_id);

                                                    $student_name = "Unknown student";
                                                    $student_adm_no = "---";

                                                    //If the student was found
                                                    if($student)
                                                    {
                                                        $student_adm_no = $student["adm_no"];
                                                        $student_name = $student["full_name"];
                                                    }
                                                        //var_dump($ass_sub);

                                                    //If the submission is submitted and not returned yet ~ display it
                                                    if($ass_sub["submitted"] && (!$ass_sub["returned"])):
                                        ?>
                                                        <!--Assignment submissions
                                                            TODO: consider making this full width
                                                        -->
                                                        <li class="col s12 pad-8 ass-submission-item">

                                                            <div class=" container">
                                                                <a class="black-text pad-8 student-name no-margin" href="javascript:void(0)" title="<?php echo $student_name."'s ".$ass_title." submission. Click to view (Opens a new window)";?>" target="_blank">
                                                                    <?php echo $student_name;?>
                                                                    <span class="js-student-id primary-text-color">(Adm No: <?php echo $student_adm_no;?>)</span>
                                                                </a>
                                                                <a href="javascript::void(0)" target="_blank" class="js-view-ass-submission grey-text text-lighten-2">
                                                                     | read
                                                                </a>
                                                                <br>
                                                                <div class="input-field inline comment" data-submission-id="<?php echo $ass_sub['submission_id']; ?>" data-id="<?php echo $ass_sub['submission_id']; ?>">
                                                                    <input data-user-id="<?php echo $student_adm_no; ?>" type="text" placeholder="comment" class="js-comment-bar browser-default normal longer" name="comment">
                                                                    <label for="comment">
                                                                        <i class="material-icons">comment</i>
                                                                    </label>
                                                                    <br>
                                                                    <a class=' btn-inline js-add-comment js-no-modal' data-root-hook="ass_submission" href="javascript:void(0)">send</a>
                                                                    <a class=' btn-inline js-get-comments' data-root-hook="ass_submission" href="javascript:void(0)">all</a>
                                                                </div>
                                                                <span class="right">
                                                                    <span class="padding-horiz-16 margin-horiz-16 primary-text-color">
                                                                        <input  type="number" min="0" max="<?php echo $ass['max_grade']?>" value="0" class="ass-grade-achieved browser-default tiny grader"  title="Assignment grade achieved. Double click to edit" class="browser-default inline-input">
<!--                                                                            <span class="editable js-marks-given chip" data-max-grade="<?php //echo $ass['max_grade']?>" title="Assignment grade achieved. Double click to edit"><big>--</big></span>-->
                                                                        <span class="grey-text"> / </span> <big><?php echo $ass['max_grade']?></big>
                                                                    </span>
                                                                    <a class="btn btn-small right return-ass-submission" href="javascript:void(0)" title="Return the graded assignment to the student. Note: You will not be able to recall the assignment once returned to the student" data-submission-id="<?php echo $ass_sub['submission_id']; ?>" data-student-name="<?php echo $student_name;?>">Return</a>
                                                                </span>
                                                            </div>
                                                        </li>
                                        <?php
                                                    endif;#end if assignment submission is not returned
                                                endforeach;

                                            else: #No unreturned assignments found ~ display appropriate message
                                        ?>
                                            <p><?php echo $no_submissions_message;?></p>
                                        <?php
                                            endif;#end if there are any unreturned assignments
                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php
                                            else: #assignment submissions not found for this assignment submission
                                        ?>
                                            <p><?php echo $no_submissions_message;?></p>
                                        <?php
                                            endif;
                                        ?>
                                            <!--Returned assignment submissions-->
                                            <div class="row returned-assignment-list padding-horiz-16">
                                                <div class="returned-submissions col s12 padding-horiz-8">
                                                    <div class="header ">
                                                        <p class="pad-8">Returned submissions</p>
                                                        <div class="divider margin-horiz-16"></div>
                                                    </div>
                                                    <ul class="row returned-ass">
                                                        <?php
                                                            $no_returned_msg = "No returned assignments found";
                                                            if($returned_ass && $returned_ass->num_rows>0):
                                                                foreach($returned_ass as $sub):
                                                                    $student = DbInfo::GetStudentByAccId($sub["student_id"]);

                                                                    $student_name = "Unknown student";
                                                                    $student_adm_no = "---";

                                                                    //If the student was found
                                                                    if($student)
                                                                    {
                                                                        $student_adm_no = $student["adm_no"];
                                                                        $student_name = $student["full_name"];
                                                                    }
                                                                    ?>
                                                                    <li class="container col s12 m6 ass-submission-item">
                                                                        <span class="student-name"><?php echo $student_name." (Adm No. $student_adm_no) "?> </span>
                                                                        <span class="chip"><?php echo $sub["grade"]."/".$sub["max_grade"];?></span>
                                                                        <div class="input-field inline comment" data-id="<?php echo $sub['submission_id'] ?>" data-submission-id="<?php echo $sub['submission_id'] ?>">
                                                                            <input data-student-id="<?php echo $student_adm_no; ?>" type="text" placeholder="comment" class="js-comment-bar browser-default normal" name="comment">
                                                                            <label for="comment">
                                                                                <i class="material-icons">comment</i>
                                                                            </label>
                                                                            <br>
                                                                            <a class='right btn-inline js-add-comment js-no-modal' data-root-hook="ass_submission" href="javascript:void(0)">send</a>
                                                                            <a class='right btn-inline js-get-comments' data-root-hook="ass_submission" href="javascript:void(0)">all</a>
                                                                        </div>
                                                                    </li>
                                                                    <?php
                                                                endforeach;
                                                            else:
                                                                    ?>
                                                            <p><?php echo $no_returned_msg;?></p>
                                                        <?php
                                                            endif;
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </li>


                                    <?php
                                        endforeach;#end assignment loop
                                    ?>
                                </ul>

                            </div>
                        <?php
                            else:#No assignments found in classroom
                        ?>
                        <!--No assignments found for this classroom-->
                            <div class="center">
                                <h6 class="grey-text flow-text">No assignments were found for the classroom <i><?php echo $class_name;?></i></h6>
                            </div>
                            <?php
                            endif;
                            ?>
                        </div>
                    <?php
                    //Close row assignment container class-ass-container

                        endforeach;
                    else:#classrooms not found
                ?>
                    <!--No classrooms were found-->
                    <div>
                        <p>No classroom was found. You can create one is the classroom section</p>
                        <a class="btn btn-flat" href="<?php echo GetSectionLink(SECTION_TR_BASE);?>">CREATE CLASSROOM</a>
                    </div>
                    <?php
                        endif;
                    ?>

                </div>

                <?php
                    break;
                    case SECTION_TR_SCHEDULES:#Create assignments
                        //Messages to be shown incase of no data
                        $noPendingSchedulesMessage = "<tbody data-tbody-number='0' ><tr class='js-dummy-schedule-data'><td>We can't find any pending schedule</td><td>--</td><td>--</td><td>--</td></tr></tbody>";
                        
                        $noAttendedSchedulesMessage = "<tbody data-tbody-number='0' ><tr class='js-dummy-schedule-data'><td>We can't find any attended schedule</td><td>--</td><td>--</td><td>--</td></tr></tbody>";
                ?>
                <!--SCHEDULE SECTION-->
                <div class="row main-tab" id="schedulesTab">
                    
                    <div class="row no-bottom-margin" id="createScheduleCont">
                        <br>
                        <div class="col s11 right pull-s1">
                            <a class="btn btn-flat right" id="openScheduleForm"><span class="hide-on-small-only">Add a schedule</span>
                            <span class="hide-on-med-and-up">
                            <i class="material-icons">add</i>
                            </span>
                            </a>
                            
                            <a class="btn-icon right hide" href="javascript:void(0)" id="closeScheduleForm">
                                <i class="material-icons">close</i>
                            </a>
                        </div>
                        
                        <div class="col s12 " id="scheduleCreateFormContainer">
                            <div class="row">
                                <form class="col s12">
                                    <div class="row">
                                        <div class="input-field col m5 s10 push-s1 push-m1">
                                            <input placeholder="Schedule title" id="schedule_title" type="text" class="validate" length="20">
                                            <label for="first_name">Schedule title</label>
                                        </div>
                                        <div class="input-field col m5 s10 push-s1 push-m1">
                                            <select id="schedule_classroom">
                                                <option value="null" disabled selected>Classroom</option>
                                                <?php
                                                
                                                if(!$classrooms) {
                                                    echo '<option value="null" disabled selected>No classrooms found</option>';

                                                } else {

                                                    $teacher_acc_id = $_SESSION['admin_acc_id'];
                                                
                                                    $classrooms = DBInfo::GetSpecificTeacherClassrooms($teacher_acc_id);
                                                
                                                    foreach ($classrooms as $classroom) {
                                                        
                                                        $newResult = array(
                                                            "id" => $classroom['class_id'],
                                                            "name" => $classroom['class_name']
                                                        );

                                                        //print_r($newResult);
                                                        echo '<option value="'.$newResult["id"].'">'.$newResult["name"].'</option>';
                                                        
                                                        $subject = $classroom['subject_id'];
                                                        $stream = $classroom['stream_id'];
                                                        
                                                    }
                                                }
                                                
                                                ?>
                                            </select>
                                            <label>Choose classroom for the schedule</label>
                                            
                                            <div id="extraClassroomInfo" class="row no-margin">
                                                <p class="col s6 php-data left"  id="ClassroomSubject">Subject: <span></span></p>
                                                <p class="col s6 php-data right-align" id="ClassroomStream">Stream: <span></span></p>
                                            </div>
                                        </div>
                                        <div class="input-field col m5 s10 push-s1 push-m1 " id="descriptionFormPanel">
                                            
                                            <textarea id="descriptionTextarea" class="materialize-textarea"></textarea>
                                            <label for="descriptionTextarea">Description</label>
                                        </div>
                                        <div class="input-field col m5 push-m1 s10 push-s1 z-depth-1" id="objectivesFormPanel">
                                            <h6>Objectives</h6>
                                            <ul id="objectivesList">
                                                
                                            </ul>
                                            
                                            <input id="objectivesInput" class="materialize-textarea">
                                            <div class="row no-margin">
                                                <div class="col s4">
                                                    <a class="btn-flat mini-link" id="addNewScheduleObjective" href="javascript:void(0)">Add</a>
                                                </div>
                                                <div class="col s8 input-field" id="selectContainerHook">
                                                    <?php
                                                    if ($classrooms) {

                                                        foreach ($classrooms as $Classroom) {
                                                            
                                                            $subjectResult = array(
                                                                "id" => $Classroom['subject_id'],
                                                                "classid" => $Classroom['class_id'],
                                                            );
                                                            
                                                            $subjectData = DBInfo::GetSubjectById($subjectResult['id']);

                                                            $topics = DBInfo::GetTopicBySubjectId($subjectResult['id']);
                                                            
                                                            echo '<select id="schedule_classroom_'.$subjectResult['classid'].'" class="hide">';
                                                            echo '<option disabled selected>Sub-topics for subject '. $subjectData['subject_name'] .'</option>';
                                                            
                                                            foreach($topics as $topic) {

                                                                $topicResult = array (
                                                                    "id" => $topic['topic_id'],
                                                                    "name" => $topic['topic_name']
                                                                );
                                                                
                                                                echo '<optgroup id="'.$topicResult['id'].'" label="'.$topicResult['name'].'">';
                                                                
                                                                $subtopics = DBInfo::GetSubTopicByTopicId($topicResult['id']);
                                                                
                                                                if($subtopics) {
                                                                    
                                                                foreach($subtopics as $subtopic) {
                                                                    
                                                                    $subTopicResult = array (
                                                                        "id" => $subtopic['sub_topic_id'],
                                                                        "name" => $subtopic['sub_topic_name']
                                                                    );
                                                                    
                                                                    echo '<option value="'. $subTopicResult['id'] .'">'. $subTopicResult['name'] .'</option>';
                                                                    
                                                                }
                                                                } else {
                                                                    
                                                                    echo '<option disabled>Found no sub-topics for '.$topicResult['name'].'</option>';
                                                                    
                                                                }
                                                                
                                                                echo '</optgroup>';
                                                                
                                                                //print_r($topicResult);
                                                                
                                                            }
                                                            
                                                            echo '</select>';
                                                            
                                                        }

                                                    } else {
                                                        echo '<select class="schedule_classroom_default">'
                                                            + '<option value="" disabled selected>Sub-topics</option>'
                                                            + '<option value="" disabled >Choose a classroom first</option>'
                                                            + '</select>';
                                                    }
                                                        ?>
                                                    <select id="schedule_classroom_default">
                                                        <option value="" disabled selected>Sub-topics</option>
                                                        <option value="" disabled >Choose a classroom first</option>
                                                    </select>
                                                    <label>Add sub-topics as objectives</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-field col s10 push-s1">
                                            <div class="row no-margin">
                                                <div class="input-field col s6 date-picker-container">
                                                    <input type="date" class="datepicker" id="scheduleDate">
                                                    <label for="scheduleDate">Schedule a date</label>
                                                </div>
                                                <div class="input-field col s6 time-picker-container">
                                                    <input type="time" class="timepicker" id="scheduleTime">
                                                    <label for="scheduleTime">Schedule the time</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12 center-align">
                                            <a class="btn " type="submit" id="submitNewSchedule">Create Schedule</a>
                                            <a class="btn hide" type="submit" id="updateSchedule">Update Schedule</a>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row no-bottom-margin" id="pendingScheduleCont">
                        <div class="col s5">
                            <p class="grey-text">Pending schedules</p>
                        </div>
                        <div class="col s7 ">
                            <a class=" right btn-icon" href="javascript:void(0)" id="showAllPendingSchedules">
                                <i class="material-icons">done_all</i>
                            </a>
                            <a class=" right btn-icon" href="javascript:void(0)" id="showAllPendingSchedules">
                                <i class="material-icons">search</i>
                            </a>
                        </div>
                        <br>
                        <table class="bordered-light responsive-table" id="pendingScheduleTable" data-paginate-through="6">
                            <thead>
                                <tr>
                                    <th data-field="name" class="center-align">Schedule title</th>
                                    <th data-field="description" class="center-align">schedule description</th>
                                    <th data-field="due" class="center-align">Due date</th>
                                    <th data-field="action" class="center-align">Action</th>
                                </tr>
                            </thead>
                            <?php

                            $teacherSchedules = DBInfo::GetSpecificTeacherSchedules($_SESSION['admin_acc_id']);

                            //var_dump($teacherSchedules);
    //                        $teacher_acc_id = $_SESSION['admin_acc_id'];

                            if ($teacherSchedules == true) {

                            $i = 0;

                            $teacherSchedules = DBInfo::ReverseResult($teacherSchedules);

                            foreach($teacherSchedules as $pendingschedules) {

                                if($pendingschedules['attended_schedule'] == 0) {

                                    $pendingSchedulesData[$i] = $pendingschedules;

                                    $i++;
                                }

                            }

                            $paginationtype = 'table';
                            $numberperrows = 6;
                            $active = 1;
                            $type = 'pending';
                            $listdata = '1';

                            if ($i == 0) {
                                    echo $noPendingSchedulesMessage;
                                } else {


                                    $listdata = $pendingSchedulesData;

                                    DBInfo::Paginate($listdata, $paginationtype, $numberperrows, $active, $type);

                                }
                            } else {

                                echo $noPendingSchedulesMessage;

                            }

                            ?>
                        </table>
                        <div class="row">
                            <?php

                            if ($teacherSchedules == true) {

                                $numberOfTbody = ceil(((count($listdata) - 1) / $numberperrows));

                                if ($numberOfTbody == 0) {
                                    $numberOfTbody = 1;
                                }

                                $position = 'center';

                                DBInfo::PaginateControl($active, $position, $numberOfTbody, 'pendingScheduleTable');

                            }
                            ?>

                        </div>
                    </div>
                    <div class="row no-bottom-margin" id="attendedScheduleCont">
                        <div class="col s5">
                            <p class="grey-text">Schedules attended</p>
                        </div>
                        <div class="col s7">
                            <a class="btn-icon right hide" href="javascript:void(0)" id="showAllDoneSchedules">
                                <i class="material-icons">expand_more</i>
                            </a>
                            <a class="btn-icon right" href="javascript:void(0)" id="showAllDoneSchedules">
                                <i class="material-icons">search</i>
                            </a>
                        </div>
                        <br>
                        <table class="bordered-light responsive-table" id="attendedScheduleTable" data-paginate-through="6">
                            <thead >
                                <tr>
                                    <th data-field="name" class="center-align">Schedule title</th>
                                    <th data-field="description" class="center-align">schedule description</th>
                                    <th data-field="due" class="center-align">Due date</th>
                                    <th data-field="action" class="center-align">Action</th>
                                </tr>
                            </thead>
                            <?php

                            $i = 0;

                            $teacherSchedules = DBInfo::ReverseResult(DBInfo::GetSpecificTeacherSchedules($_SESSION['admin_acc_id']));

                            if ($teacherSchedules == true) {

                            foreach($teacherSchedules as $attendedschedules) {

                                if($attendedschedules['attended_schedule'] == 1) {

                                    $attendedSchedulesData[$i] = $attendedschedules;

                                    $i++;
                                }

                            }

                            $paginationtype = 'table';
                            $numberperrows = 6;
                            $active = 1;
                            $type = 'done';
                            $listdata = '1';


                                if ($i == 0) {
                                    echo $noAttendedSchedulesMessage;
                                } else {

                                    $listdata = $attendedSchedulesData;

                                    DBInfo::Paginate($listdata, $paginationtype, $numberperrows, $active, $type);
                                }
                            } else {

                                echo $noAttendedSchedulesMessage;
                            }

                            ?>
                        </table>
                        <div class="row">
                            <?php

                            if ($teacherSchedules == true) {

                            $numberOfTbody = ceil(((count($listdata) - 1) / $numberperrows));

                            $position = 'center';

                            DBInfo::PaginateControl($active, $position, $numberOfTbody, 'attendedScheduleTable');

                            }
                            ?>

                        </div>
                    </div>
                </div>

</div>
                <!--TESTS SECTION-->
                <?php
                    break;
                    case SECTION_TR_TEST_CREATE:#Create a test
                        include_once("classes/test.php");#Include the test class
                ?>
                <!--Create a test-->
                <div class="row main-tab" id="createTestTab">

                    <div class="col s12">
                        <p class="grey-text">Create test</p>
                    <div class="col s12 grey-text">
                        <p class="grey-text">Create Test. Once a test is created you will be redirected to the test editing page page</p>
                        <div class="divider"></div>
                    <br>
                    </div>


                    <div class="container">
                        <form id="createTestForm" class="row" method="POST">
                                <div class=" input-field col s12 m6">
                                    <input type="text" id="createTestTitle" name="create_test_title" placeholder="Test Title" class="validate" required>
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
                                <div class=" input-field col s12 m6">
                                    <input type="number" id="createTestQuestionCount" name="create_test_question_count" min="1" max="50" value="10" class="validate" required>
                                    <label for="createTestQuestionCount">No. of questions</label>
                                </div>


                                <div class=" input-field col s12 m6">
                                    <select id="createTestDifficulty" name="create_test_difficulty" class="validate">
                                        <option value="Very Easy">Very Easy</option>
                                        <option value="Easy">Easy</option>
                                        <option value="Moderate">Moderate</option>
                                        <option value="Difficult">Difficult</option>
                                        <option value="Very Difficult">Very Difficult</option>
                                    </select>
                                    <label for="createTestDifficulty">Difficulty</label>
                                </div>

                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestMaxGrade" name="create_test_max_grade" min="10" max="100" value="100" class="validate" required>
                                    <label for="createTestMaxGrade">Max grade</label>
                                </div>

                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestPassGrade" name="create_test_pass_grade" min="10" max="100" value="50" class="validate" required>
                                    <label for="createTestPassGrade">Passing grade</label>
                                </div>


                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestCompletionTime" step="5" name="create_test_completion_time" class="validate" min="10" max="45" value="30" required>
                                    <label for="createTestCompletionTime">Time (Minutes)</label>
                                </div>

                                <!--Retake delays-->
                            <!--
                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestRetakeDelay_days" name="create_test_rDelay_days" min="0" max="100" value="0" class="validate" required>
                                    <label for="createTestRetakeDelay_days">Retake Delay (Days)</label>
                                </div>

                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestRetakeDelay_hours" name="create_test_rDelay_hours" min="0" max="100" value="0" class="validate" required>
                                    <label for="createTestRetakeDelay_hours">Retake Delay (Hours)</label>
                                </div>

                                <div class=" input-field col s12 m4">
                                    <input type="number" id="createTestRetakeDelay_min" name="create_test_rDelay_min" min="10" max="100" value="30" class="validate" required>
                                    <label for="createTestRetakeDelay_min">Retake Delay (Minutes)</label>
                                </div>
                            -->

                                <div class=" input-field col s12 ">
                                    <textarea id="createTestInstructions" class="materialize-textarea" placeholder="Instructions students will get for the test"></textarea>
                                    <label for="createTestInstructions">Test instructions</label>
                                </div>

                                <a class="btn col s10 m4 right pull-s1" href="javascript:void(0)" id="create_test_btn">Create Test</a>
                        </form>
                        </div>
                    </div>
                </div>

                <?php
                    break;
                    case SECTION_TR_TEST_VIEW_RESULTS:#View student test results
                    #TODO: Check the functionality of this section and polish it
                        include_once("classes/test.php");#Include the test class
                ?>
                <!--Test results-->
                <div class="container row main-tab" id="viewStudentsTestResultTab">
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
                    case SECTION_TR_TEST_TAKE:#Take a test
                        include_once("classes/test.php");#Include the test class
                ?>
                <!--Take a test-->
                <div class="row main-tab" id="takeTestTab">
                    <?php
                        $tests = DbInfo::GetSpecificTeacherTests($loggedInTeacherId);
                        if($tests):
                    ?>
                    <!--<div class="row">
                        <div class="input-field col s8">
                            <label for="search_take_test">Search Tests</label>
                            <input type="search" id="search_take_test" class="validate" placeholder="Search Here"/>
                        </div>
                        <div class="col s4">
                            <a class="btn btn_search_take_test" href="javascript:void(0)">Search</a>
                        </div>
                    </div>-->
                    <h5 class="grey-text">Your tests</h5>
                    <br>
                    <div class="divider"></div>
                    <br>
                    <div class="row">
                    <?php
                        $redirect_url = "";#url the test redirects to
                        $test_id = 0; #init test_id
                        $no_of_takers = 0;#init number of takers
                        $subject = null;#init subject 
                        $pass_mark = 0;
                        
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

                            //Tooltip variables tt is shorthand for tooltip
                            $tt_edit_test = "Edit the default general test settings";
                            $tt_delete_test= "Delete the test";
                            $tt_take_test= "Take the test. Opens a new page";
                            $tt_edit_questions = "Edit the test questions. Opens a new page";
                    ?>
                        <div class="col s12 m6 l4 take_test_container" data-test-id="<?php echo $test_id;?>">
                            <div class="card blue-grey darken-1">
                                <div class="card-content white-text">
                                    <div class="row">
                                        <div class="col s6">
                                            <span class="card-title"><a href="javascript:void(0)" class="btn-floating editTest tooltipped" data-position="top" data-delay="50" data-tooltip="<?php echo $tt_edit_test?>" data-test-id="<?php echo $test['test_id']?>"><i class="material-icons">settings</i></a></span>
                                        </div>
                                        <div class="col s6 right-align">
                                            <span class="card-title">
                                                <a href="javascript:void(0)" class="btn-floating red deleteTest tooltipped" data-position="top" data-delay="50" data-tooltip="<?php echo $tt_delete_test?>" data-test-id="<?php echo $test['test_id']?>"><i class="material-icons">delete</i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="card-title truncate takeTestTitle"><?php echo $test["test_title"];?></span>
                                    <p>Subject: <span class="php-data"><?php echo $subject["subject_name"];?></span></p>
                                    <p>Questions: <span class="php-data"><?php echo $test["number_of_questions"]?></span></p>
                                    <p>Time: <span class="php-data"><?php echo $test["time_to_complete"]?> min</span></p>
                                    <p>Difficulty: <span class="php-data"><?php echo $test["difficulty"]?></span></p>
                                    <p>Pass mark: <span class="php-data"><?php echo $pass_mark["percentage"]."%";?></span></p>
                                    <p class="students-taken php-data"><i>This test has been taken <?php echo $no_of_takers;?> time(s)</i></p>
                                </div>
                                <div class="card-action ">
                                    <a class="btn btn-flat blue-grey-text text-lighten-4 editTest tooltipped" data-position="top" data-delay="50" data-tooltip="<?php echo $tt_edit_questions?>" href="<?php echo 'tests.php?tid='.$test_id.'&edit=1'?>">EDIT TEST</a>
                                    <a href="<?php echo 'tests.php?tid='.$test_id?>" class="tooltipped" data-position="top" data-delay="50" data-tooltip="<?php echo $tt_take_test?>">Take Test</a>
                                </div>
                            </div>
                        </div>
                    <?php
                            endforeach;
                            //Close the row container
                    ?>
                    </div>
                    <?php
                        else: # Could not find the tests
                    ?>
                    <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                        <h6 class="center-align valign grey-text " id="testResultsMessage">
                            No tests were found
                            <a class="btn btn-flat" href="<?php echo GetSectionLink(SECTION_TR_TEST_CREATE);?>">CREATE ONE</a>
                        </h6>

                    </div>
                    <?php
                        endif;

                        #Add the edit test modal to the DOM
                        Test::DisplayEditTestModal();
                    ?>
                </div>

                <?php
                    break;
                    case SECTION_RESOURCES:#Resources
                ?>
                <!--RESOURCES SECTION-->
                <div class="row main-tab" id="teacherResourcesTab">
                    <div class="col s12 tab-header">
                        <div class="row no-bottom-margin">
                            <div class="col s5">
                                <p class="grey-text">Resources</p>
                            </div>
                            <div class="col s7">
                                <a class="btn right" id="addResource">Add<span class="hide-on-small-only"> resources</span>
                                <span class="hide-on-med-and-up">
                                    <i class="material-icons right">&#xE226;</i>
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
                    </div>
                    
                    <div class="col s12 tab-content">
                    <?php
                        // TODO: [OPTIMIZATION] Could Create a function for quick retrieval of resources (use 1 column in database and limit selection length. Or check for whether values exist or not and return true or false)
                        //If there are resources available 
                        if($resources = DbInfo::GetAllResources())
                        {
                            EsomoResource::DisplayEditResources($user_info);
                        }
                        else#Resources not found
                        {
                            EsomoResource::DisplayMissingDataMessage();
                        }
                    ?>
                    </div>
                </div>

                <?php
                    break;
                    case SECTION_ACCOUNT:#Account
                ?>
                <!--ACCOUNT SECTION-->
                <div class="row main-tab" id="teacherAccountTab">
                    <div class="row no-bottom-margin">
                        <div class="col s12">
                            <p class="grey-text">Your account</p>
                        </div>
                    </div>
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
                <?php
                    break;
                    default:#Any other option
                ?>
<script>window.location = "<?php echo GetSectionLink(SECTION_TR_BASE);?>";</script>
            <?php
                endswitch;
            ?>
<?php
    else:#No section was provided
?>
<script>window.location = "<?php echo GetSectionLink(SECTION_TR_BASE);?>";</script>
<?php
    endif;#End check for if it exists
?>