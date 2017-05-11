<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
require_once(realpath(dirname(__FILE__) . "/../classes/resources.php")); #Resources class. Handles all resource related operations

//Get number text ~ rounds off number after 1000 to show shorter version eg. 1K instead of 1000
 function GetNumberText($number)
 {
     $num_text = "";
    if($number>=1000)
    {
        $num_text = round(($number/1000),2)."K";
    }
    else
    {
        $num_text = $number;
    }

    return $num_text;
 }

 function AddTimeframeDropdown($id="")
 {
     $id_attr = "";
    if(!empty($id))
    {
        $id_attr = 'id="'.$id.'"';
    }
?>
<div class="timeframe_container container">
    <label for="<?php echo $id_attr;?>">Timeframe</label>
    <select class="esomo_timeframe" <?php echo $id_attr;?>>
        <option value="all">All time</option>
        <option value="today">Today so far</option>
        <option value="yesterday">Yesterday</option>
        <option value="last7days" selected>Last 7 days</option>
        <option value="this_month">This month</option>
        <option value="last_month">Last month</option>
    </select>
</div>
<?php
 }
?>            
            
            <div class="container">
                <!--<p class="grey-text">Assignments (overview headers)</p>
                <div class="divider"></div>
                <br>-->
                <!--Principal stats overview tab-->
                <div class="row main-tab" id="statsOverviewTab">

                    <!--Schedules card section-->
                    <div class="col s12 l6">
                        <div class="card">
                            <div class="card-content row">
                                <span class="card-title">SCHEDULES</span>
                                <div class="divider"></div><br>
                                
                                <?php
                                    AddTimeframeDropdown("schedule_overview_timeframe");

                                    $schedules = DbInfo::Get7DaySchedules();
                                    $total_schedule_count = $done_schedule_count = $unattended_schedule_count = 0;
                                    if($schedules && @$schedules->num_rows>0)
                                    {
                                        $total_schedule_count = $schedules->num_rows;
                                        
                                        //Returns an array that contains associative arrays corresponding to db records
                                        $done_schedules = DbInfo::GetDoneSchedules($schedules);
                                        $unattended_schedules = DbInfo::GetUnattendedSchedules($schedules);
                                        // var_dump($done_schedules);
                                        // var_dump($unattended_schedules);

                                        $done_schedule_count = count($done_schedules);
                                        $unattended_schedule_count = count($unattended_schedules);
                                    }
                                ?>
                                
                                <!--Total schedules-->
                                <div class="card col s12 m4">
                                    <div class="card-content">
                                        <p><b>Total</b></p>
                                        <h4 class="center php-data" id="stats_total_schedules"><?php echo $total_schedule_count;?></h4> 
                                        <!--<a href="javascript:void(0)" class="btn ">VIEW</a>                 -->
                                    </div>
                                </div>

                                <!--Done schedules-->
                                <div class="card col s12 m4">
                                    <div class="card-content">
                                        <p><b>Done</b></p>
                                        <h4 class="center php-data" id="stats_done_schedules"><?php echo $done_schedule_count;?></h4> 
                                        <!--<a href="javascript:void(0)" class="btn ">VIEW</a>                 -->
                                    </div>
                                </div>

                                <!--Unattended schedules-->
                                <div class="card col s12 m4">
                                    <div class="card-content">
                                        <p><b>Unattended</b></p>
                                        <h4 class="center php-data" id="stats_unattended_schedules"><?php echo $unattended_schedule_count;?></h4> 
                                        <!--<a href="javascript:void(0)" class="btn ">VIEW</a>                 -->
                                    </div>
                                </div>

                                <!--Assignment Tips-->
                                <div class="card col s12 blue-grey lighten-4">
                                    <div class="card-content">
                                        <p class="center blue-grey-text text-darken-2"><b>Quick Tip</b><br>Unattended schedules are schedules that were not marked as attended by teachers.</p>               
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!--Assignments card section-->
                    <div class="col s12 l6">
                        <div class="card">
                            <div class="card-content row">
                                <span class="card-title">ASSIGNMENTS</span>
                                <div class="divider"></div><br>
                                
                                <?php
                                    AddTimeframeDropdown("ass_overview_timeframe");

                                    $assignments = DbInfo::Get7DayAssignments();
                                    $total_ass_sent = $total_ass_subs = $total_graded_ass_subs = 0;

                                    if($assignments && @$assignments->num_rows>0)
                                    {
                                        $total_ass_sent = $assignments->num_rows;

                                        $ass_subs = DbInfo::GetMultipleAssSubmissions($assignments);
                                        $graded_subs = DbInfo::GetGradedAssSubmissions($assignments);
                                        
                                        $total_ass_subs = count($ass_subs);
                                        $total_graded_ass_subs = count($graded_subs);
                                    }
                                ?>
                                
                                <!--Total assignments sent-->
                                <div class="card col s12 m4">
                                    <div class="card-content">
                                        <p><b>Total sent</b></p>
                                        <h4 class="center php-data" id="stats_total_ass_sent"><?php echo $total_ass_sent;?></h4> 
                                        <!--<a href="javascript:void(0)" class="btn ">VIEW</a>                 -->
                                    </div>
                                </div>

                                <!--Assignment submissions-->
                                <div class="card col s12 m4">
                                    <div class="card-content">
                                        <p><b>Submissions</b></p>
                                        <h4 class="center php-data" id="stats_total_ass_subs"><?php echo $total_ass_subs;?></h4> 
                                        <!--<a href="javascript:void(0)" class="btn ">VIEW</a>                 -->
                                    </div>
                                </div>

                                <!--Graded assignments-->
                                <div class="card col s12 m4">
                                    <div class="card-content">
                                        <p><b>Graded</b></p>
                                        <h4 class="center php-data" id="stats_total_graded_ass_subs"><?php echo $total_graded_ass_subs;?></h4> 
                                        <!--<a href="javascript:void(0)" class="btn">VIEW</a>                 -->
                                    </div>
                                </div>

                                <!--Assignment Tips-->
                                <div class="card col s12 blue-grey lighten-4">
                                    <div class="card-content">
                                        <p class="center blue-grey-text text-darken-2"><b>Quick Tip</b><br>Assignments are only considered graded once the teacher has returned them.</p>               
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!--Principal schedules tab-->
                <div class="row main-tab" id="principalSchedulesTab">
                    <div class="col s12 m6 right">
                        <?php
                            AddTimeframeDropdown("schedules_timeframe");
                        ?>
                    </div>
                    <div class="col s12 divider"></div><br>
                    <div class="col s12">
                        <p class="grey-text text-darken-2">Schedules</p>

                        <table class="table striped bordered" id="schedules_tab_list">
                            <tr class="table-titles">
                                <th>Title</th>
                                <th>Teacher</th>
                                <th>Classroom</th>
                                <th>Scheduled on</th>
                                <th>Due date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <!--Table body contents-->
                        </table>
                        
                    </div>
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
