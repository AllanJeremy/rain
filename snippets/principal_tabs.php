<?php 
require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database
require_once(realpath(dirname(__FILE__) . "/../classes/resources.php")); #Resources class. Handles all resource related operations

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
        <option value="last30days">Last 30 days</option>
    </select>
</div>
<?php
 }
?>            
            
            <div class="container">
                <!--<p class="grey-text">Assignments (overview headers)</p>
                <div class="divider"></div>
                <br>-->
       
        <?php
            if(isset($section)):
        ?>
           
        <?php
                switch($section):
                    case SECTION_PR_BASE:
        ?>
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

                                    $schedules = DbInfo::Get7DaySchedules();#Default timeframe of schedules to get 

                                    $total_schedule_count = $done_schedule_count = $unattended_schedule_count = 0;
                                    if(@$schedules && @$schedules->num_rows>0)
                                    {
                                        $total_schedule_count = $schedules->num_rows;
                                        
                                        //Returns an array that contains associative arrays corresponding to db records
                                        $done_schedules = DbInfo::GetDoneSchedules($schedules);
                                        $unattended_schedules = DbInfo::GetUnattendedSchedules($schedules);

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

                                <!--Schedule Tips-->
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

                                    $assignments = DbInfo::Get7DayAssignments();#Default timeframe of assignments to get

                                    $total_ass_sent = $total_ass_subs = $total_graded_ass_subs = 0;

                                    if(@$assignments && @$assignments->num_rows>0)
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
                <?php
                    break;
                    case SECTION_PR_SCHEDULES:
                ?>
                <!--Principal schedules tab-->
                <div class="row main-tab" id="principalSchedulesTab">
                    <div class="col s12 m6 right">
                        <?php
                            AddTimeframeDropdown("schedules_tab_timeframe");
                        ?>
                    </div>
                    <div class="col s12 divider"></div><br>
                    <div class="col s12">
                        <p class="grey-text text-darken-2">Schedules</p>

                        <table class="table striped bordered" id="schedules_tab_list">
                            <tr class="table-headers">
                                <th>Title</th>
                                <th>Teacher</th>
                                <th>Classroom</th>
                                <th>Scheduled on</th>
                                <th>Due date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <!--Table body contents-->
                            <?php
                                if(@$schedules && @$schedules->num_rows>0):
                                    foreach($schedules as $schedule):
                                        $schedule_id = $schedule["schedule_id"];
                                        $teacher_id = $schedule["teacher_id"];
                                        $teacher_found = DbInfo::GetTeacherById($teacher_id);

                                        $classroom_id = $schedule["class_id"];
                                        $classroom_found = DbInfo::ClassroomExists($classroom_id);

                                        //Direct values
                                        $schedule_title = $schedule["schedule_title"];
                                        $schedule_date = $schedule["schedule_date"];
                                        $schedule_date = EsomoDate::GetOptimalDateText($schedule_date);

                                        $schedule_due_date = $schedule["due_date"];
                                        $schedule_due_date = EsomoDate::GetOptimalDateText($schedule_due_date);

                                        //Foreign key values
                                        $schedule_teacher = "Unknown teacher";
                                        if($teacher_found)#If the teacher was found in the database
                                        {
                                            $schedule_teacher = $teacher_found["first_name"]." ".$teacher_found["last_name"];
                                        }
                                        $schedule_classroom = "Uknown classroom";
                                        if($classroom_found)#If the classroom was found in the database
                                        {
                                            $schedule_classroom = $classroom_found["class_name"];
                                        }
                                        
                                        $schedule_status = "";

                                        //If the schedule has been attended
                                        if($schedule["attended_schedule"])
                                        {   
                                            $schedule_status = "Done";
                                        }
                                        else
                                        {
                                            $schedule_status = "Unattended";
                                        }
                            ?>
                            <tr class="schedule-table-list-row" data-schedule-id="<?php echo $schedule_id;?>">
                                <td><?php echo $schedule_title;?></td>
                                <td><?php echo $schedule_teacher;?></td>
                                <td><?php echo $schedule_classroom;?></td>
                                <td><?php echo $schedule_date;?></td>
                                <td><?php echo $schedule_due_date;?></td>
                                <td><?php echo $schedule_status;?></td>
                                <td>
                                    <a href="javascript:void(0)" data-schedule-id="<?php echo $schedule_id;?>" class="principal_view_schedule" title="View schedule (<?php echo $schedule_title;?>)"><i class="material-icons">visibility</i></a>
                                    <a href="javascript:void(0)" data-schedule-id="<?php echo $schedule_id;?>" class=" principal_comment_on_schedule" title="Comments for <?php echo $schedule_title;?>"><i class="material-icons lime-text">comment</i></a>
                                </td>
                            </tr>
                            <?php
                                    endforeach;
                                else:#No schedules found
                            ?>
                            <tr class="schedule-table-list-row"><td colspan="7">No schedules were found for the specified time period</td></tr>
                            <?php
                                endif;
                            ?>
                        </table>
                        
                    </div>
                </div>
                <?php
                    break;
                    case SECTION_PR_ASSIGNMENTS:
                ?>
                <!--Principal assignments tab-->
                <div class="row main-tab" id="principalAssignmentsTab">
                    <div class="col s12 m6 right">
                        <?php
                            AddTimeframeDropdown("assignments_tab_timeframe");
                        ?>
                    </div>
                    <div class="col s12 divider"></div><br>
                    <div class="col s12">
                        <p class="grey-text text-darken-2">Assignments</p>

                        <table class="table striped bordered" id="ass_tab_list">
                            <tr class="table-headers">
                                <th>Title</th>
                                <th>Teacher</th>
                                <th>Classroom</th>
                                <th>Date sent</th>
                                <th>Due date</th>
                                <th>Submissions</th>
                                <th>Graded</th>
                                <th>Unreturned</th>
                                <th>Action</th>
                            </tr>

                            <!--Table body contents-->
                            <?php
                                if(@$assignments && @$assignments->num_rows>0):

                                    foreach($assignments as $ass):
                                        $ass_id = $ass["ass_id"];

                                        $ass_title = $ass["ass_title"];

                                        $teacher_id = $ass["teacher_id"];
                                        $teacher_found = DbInfo::GetTeacherById($teacher_id);

                                        $classroom_id = $ass["class_id"];
                                        $classroom_found = DbInfo::ClassroomExists($classroom_id);

                                        //Foreign key values
                                        $ass_teacher = "Unknown teacher";
                                        if($teacher_found)#If the teacher was found in the database
                                        {
                                            $ass_teacher = $teacher_found["first_name"]." ".$teacher_found["last_name"];
                                        }
                                        $ass_classroom = "Unknown classroom";
                                        if($classroom_found)#If the classroom was found in the database
                                        {
                                            $ass_classroom = $classroom_found["class_name"];
                                        }

                                        $ass_date_sent = $ass["date_sent"];
                                        $ass_date_sent = EsomoDate::GetOptimalDateText($ass_date_sent);

                                        $ass_submissions = DbInfo::GetAssSubmissionsByAssId($ass_id);
                                        
                                        

                                        $ass_submission_count = $graded_submission_count = $unreturned_submission_count = 0;

                                        //If assignment submissions for this assignment were found
                                        if($ass_submissions && @$ass_submissions->num_rows>0)
                                        {
                                            $graded_submissions = DbInfo::GetGradedAssSubBasedOnAss($ass_submissions);
                                            $unreturned_submissions = DbInfo::GetUnreturnedAssSubBasedOnAss($ass_submissions);

                                            //Assignment submissions count
                                            $ass_submission_count = $ass_submissions->num_rows;
                                            $graded_submission_count = count($graded_submissions);
                                            $unreturned_submission_count = count($unreturned_submission);
                                        }

/*                                <th>Submissions</th>
                                <th>Graded</th>
                                <th>Unreturned</th>*/
                                        $ass_date_due = $ass["due_date"];
                                        $ass_date_due = EsomoDate::GetOptimalDateText($ass_date_due);
                                        
                            ?>
                            <tr class="ass-table-list-row" data-ass-id="<?php echo $ass_id;?>" >
                                <td title="Assignment title"><?php echo $ass_title;?></td>
                                <td title="Teacher that sent the assignment"><?php echo $ass_teacher;?></td>
                                <td title="Classroom the assignment was sent to"><?php echo $ass_classroom;?></td>
                                <td title="Date the assignment was sent"><?php echo $ass_date_sent;?></td>
                                <td title="Due date of the assignment"><?php echo $ass_date_due;?></td>
                                <td title="Number of submissions received from students for this assignment"><?php echo $ass_submission_count;?></td>
                                <td title="Number of submissions that were graded and returned by the teacher"><?php echo $graded_submission_count;?></td>
                                <td title="Number of submissions that have not yet been graded/returned by the teacher"><?php echo $unreturned_submission_count;?></td>
                                <td>
                                    <a href="javascript:void(0)" data-ass-id="<?php echo $ass_id;?>" class="principal_view_ass" title="View assignment (<?php echo $ass_title;?>)"><i class="material-icons">visibility</i></a>
                                </td>
                            </tr>
                            <?php
                                    endforeach;
                                else:#No schedules found
                            ?>
                            <tr class="ass-table-list-row"><td colspan="9">No assignments were found for the specified time period</td></tr>
                            <?php
                                endif;
                            ?>
                        </table>
                        
                    </div>
                </div>
                
                <?php
                    break;
                    case SECTION_RESOURCES:
                ?>
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
                <?php
                    break;
                    case SECTION_CHAT:
                ?>
                <!--Principal chat tab-->
                <!--<div class="row main-tab" id="principalChatTab">
                    Principal Chat tab
                </div>-->

                <?php
                    break;
                    case SECTION_ACCOUNT:
                ?>
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
                <?php
                    break;
                    default:
                ?>
        <script>window.location = "<?php echo './?section='.SECTION_PR_BASE;?>";</script>
        <?php
                endswitch;
            else:#The section has not been specified ~ redirect to the main section
        ?>
        <script>window.location = "<?php echo './?section='.SECTION_PR_BASE;?>";</script>
        <?php
            endif;
        ?>
            </div>
