<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
require_once(realpath(dirname(__FILE__) . "/../classes/resources.php")); #Resources class. Handles all resource related operations
?>            
            
            <div class="container">
                <!--<p class="grey-text">Assignments (overview headers)</p>
                <div class="divider"></div>
                <br>-->
                <!--Principal stats overview tab-->
                <div class="row main-tab" id="statsOverviewTab">
                    
                    <div class="col card-col">
                        <div class="card blue-grey lighten-2">
                            
                            <div class="card-content">
                                <span class="card-title ">Schedules overview</span>

                                <p>total Schedules: <span class="php-data">20,000</span></p>
                                
                                <p>Done schedules: <span class="php-data">19,878</span></p>
                                
                                <p>Schedules not done: <span class="php-data">100</span></p>
                                
                                <p>Overdue schedules: <span class="php-data">22</span></p>
                                
                            </div>
                            <div class="card-action right-align">
                                <a href="#" class="">more details</a>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!--Principal schedules tab-->
                <div class="row main-tab" id="principalSchedulesTab">
                    Principal Schedules tab
                </div>
                
                <!--Principal assignments tab-->
                <div class="row main-tab" id="principalAssignmentsTab">
                    Principal Assignments tab
                </div>
                
                <!--Principal student grades tab-->
                <!--<div class="row main-tab" id="principalStudentGradesTab">
                    Principal Student Grades tab
                </div>-->
                
                <!--Principal gradebook tab-->
                <!--<div class="row main-tab" id="principalGradebookTab">
                    Principal Gradebook tab
                </div>-->
                
                <!--Principal students tab-->
                <!--<div class="row main-tab" id="principalStudentsTab">
                    <p class="grey-text">Students</p>
                    <div class="divider"></div><br>
                </div>-->
                
                <!--Principal teachers tab-->
                <!--<div class="row main-tab" id="principalTeachersTab">
                    <p class="grey-text">Teachers</p>
                    <div class="divider"></div><br>
                </div>-->
                <!--Principal resources tab-->
                <div class="row main-tab" id="principalResourcesTab">
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
                
                <!--Principal chat tab-->
                <!--<div class="row main-tab" id="principalChatTab">
                    Principal Chat tab
                </div>-->

                <!--Principal groups tab-->
                <!--<div class="row main-tab" id="principalGroupsTab">
                    Principal Groups tab
                </div>-->
                
                <!--Principal account tab-->
                <div class="row main-tab" id="principalAccountTab">
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
                
            </div>
