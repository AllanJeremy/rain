<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
?>
            
            <div class="container">
                <!---->
                <div class="row main-tab active-bar" id="classroomTab">
                    <!--
                    <div class="col s12 m10 offset-m1">
                        <ul class="tabs">
                            <li class="tab col s6">
                                <a href="#createClassroom" class="active">Create</a>
                            </li>
                            <li class="tab col s6">
                                <a class="" href="#manageClassroom" >Manage</a>
                            </li>
                        </ul>
                    </div>
                    <div id="createClassroom" class="col s12 offset-m1 m10 ">
                        <div class="row">
                            <br>
                            <form  id="createNewClassroom" class="col s12 m8 offset-m2" method="post" action="">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="newClassroomName" type="text" class="validate" name="new_classroom_name" required>
                                        <label for="newClassroomName">Class name</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="class_stream" required class='grey-text text-lighten-2'>
                                            <option value="9A" selected>9A</option>
                                            <option value="9B">9B</option>
                                            <option value="9C">9C</option>
                                            <option value="9D">9D</option>
                                            <option value="10A">10A</option>
                                            <option value="10B">10B</option>
                                        </select>
                                        <label>Stream</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="class_subject" required class='grey-text text-lighten-2'>
                                            <optgroup label="Sciences">
                                                <option value="Mathematics" selected>Mathematics</option>
                                                <option value="Biology">Biology</option>
                                                <option value="Physics">Physics</option>
                                                <option value="Chemistry">Chemistry</option>
                                            </optgroup>
                                            <optgroup label="Languages">
                                                <option value="English">English</option>
                                                <option value="Kiswahili">Kiswahili</option>
                                                <option value="French">French</option>
                                                <option value="Literature">Literature</option>
                                            </optgroup>
                                            <optgroup label="Humanities">
                                                <option value="Religion">Religion</option>
                                                <option value="Geography">Geography</option>
                                                <option value="History">History</option>
                                                <option value="Sociology">Sociology</option>
                                            </optgroup>
                                            <optgroup label="Extra subjects">
                                                <option value="Tourism">Travel and Tourism</option>
                                                <option value="Art">Art and Design</option>
                                                <option value="ICT">ICT</option>
                                                <option value="PhysicalEducation">Physical Education</option>
                                                <option value="Music">Music</option>
                                                <option value="Business">Business Studies</option>
                                            </optgroup>
                                        </select>
                                        <label>Subject</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <p>
                                            <input type="checkbox" id="addStudentsToClassroom" name="add_students_to_classroom" />
                                            <label for="addStudentsToClassroom">Add students before creating</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <button class="right btn" type="submit" >Create classroom</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="manageClassroom" class="col s12">
                        <div class="row">
                            <br>
                            <br>


                        </div>
                    </div>
                    -->
                    <div class="row no-margin">
                        <div class="col s5">
                            <p class="grey-text">Your classrooms</p>
                        </div>
                        <div class="col s7">
                            <a class="btn right" id="createClassroom">Create a classroom</a>
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

                        foreach($reversed_classrooms as $classroom):
                     ?> 
                        <div class="col card-col new-class" data-classroom-id="<?php echo $classroom['class_id'] ?>">
                            <div class="card cyan darken-4">
                                <div class="card-content white-text">
                                    <span class="card-title"><?php echo $classroom['class_name'] ?></span>
                                    <p>Number of students:
                                        <span class="php-data">10  
                                            <a class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of students in this classroom" href="#" >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Assignments sent:
                                        <span class="php-data">26  
                                            <a onclick="openAssignmentClassList()" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of assignments sent to this classroom" href="#" >
                                                <i class="material-icons">info</i>
                                            </a>
                                        </span> 
                                    </p>
                                    <p>Subject: <span class="php-data">Biology</span></p>
                                    <p>Stream:  <span class="php-data">Alpha</span></p>
                                </div>
                                <div class="card-action">
                                    <a href="#" data-target="" class="modal-trigger" id="editClassroom">Edit</a>
                                    <a href="#"  class="right">View</a>
                                </div>
                            </div>
                        </div>

                    <?php endforeach;?> 
                    </div>
                    <?php 
                    else: ?>
                        <p> No classrooms available for this teacher</p>
                    
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