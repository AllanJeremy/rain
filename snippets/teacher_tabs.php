<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
?>
            
            <div class="container">
                <!---->
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
                    </div>
                    

                    
                    <div class="divider"></div>
                    <br>
                    
                    <?php
                        if ($classrooms = DbInfo::GetSpecificTeacherClassrooms($_SESSION["admin_acc_id"])):   
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
                            if($assignments = DbInfo::GetTeacherAssInClass($classroom['class_id'],$_SESSION["admin_acc_id"]))
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
                                            <a class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of students in this classroom" href="#" >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Assignments sent:
                                        <span class="php-data"><?php echo $ass_count;?>
                                            <a class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of assignments sent to this classroom" href="#" >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Subject: <span class="php-data"><?php echo $subject_name ?></span></p>
                                    <p>Stream:  <span class="php-data"><?php echo $stream_name ?></span></p>
                                </div>
                                <div class="card-action">
                                    <a href="#" data-target="" class="modal-trigger" id="editClassroom">Edit</a>
                                    <a href="#"  class="right">View</a>
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
                <!---->
                <div class="row main-tab " id="createAssignmentsTab">
                    Create Assignments tab
                </div>
                <div class="row main-tab" id="sentAssignmentsTab">
                    Sent Assignments tab
                </div>
                <div class="row main-tab" id="submittedAssignmentsTab">
                    Submitted Assignments tab
                </div>
                <!---->
                <div class="row main-tab" id="createTestTab">
                    Create a test tab
                </div>
                <div class="row main-tab" id="viewStudentsTestResultTab">
                    test results tab
                </div>
                <div class="row main-tab" id="takeTestTab">
                    Take a test tab
                </div>
                <!---->
                <div class="row main-tab" id="mySubjectGradesTab">
                    myGrades tab
                </div>
                <div class="row main-tab" id="gradeBookTab">
                    gradeBook tab
                </div>
                <!---->
                <div class="row main-tab" id="schedulesTab">
                    schedules tab
                </div>
                <!---->
                <div class="row main-tab" id="myAccountTab">
                    myAccount tab
                </div>
                
            </div>