<main>
    <div class=" container">
        <?php

        if (!isset($_SESSION["student_adm_no"])) {
            ?>
        <div class="row">
            <div class=" container">
                <br>
                <br>
                <h5 class="grey-text text-darken-3 center-align">You need to log in first</h5>
                <br>
                <h6 class="grey-text text-lighten-2 center-align">Redirecting you...</h6>
                <div class=" col s6 offset-s3 container valign-wrapper">
<!--                <div class="" style="width:200px">-->
                    <div class="valign progress" >
                            <div class="indeterminate" style="width:0%;"></div>
                    </div>
<!--                </div>-->
                </div>
                <br>
                <br>
                <br>
                <br>
                <h6 class="right-align">
                    If you are not directed to the login page <a class="inline" href="../index.php"> click here. </a>
                </h6>
            </div>
        </div>
        <script src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript">
            setTimeout(function () {
                location.replace('login.php');
            }, 3400)

        </script>
        <?php
//            redirect
        } else {

        $loggedInStudentId = $_SESSION["student_adm_no"];

        //Get all assignments that belong to the logged in teacher
        $assignments = DbInfo::GetAllStudentAssignments($loggedInStudentId);
        // var_dump($assignments);

        ?>
        <div class="row">
            <h5>Oops! We got a broken assignment link<br>
                Please choose again the assignment below.
            </h5>
            <h6>
                <a class="js-report btn-flat">Report</a> if the link is still broken.
            </h6>
        </div>
        <div class="row" id="recievedAssignmentsTab">

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
            <div class="col card-col" data-assignment-id="<?php echo $assignment["ass_id"]; ?>">
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
                                <a href="?id=<?php echo $assignment["ass_id"]; ?>&sect=resources" id="resourceFile"><?php echo $ass_attachments;?></a>
                                <?php endif;?>
                            </span>
                        </p>
                    </div>
                    <div class="card-action">
                        <a href="?id=<?php echo $assignment["ass_id"]; ?>"  id="" class="right">Submit</a>
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
    </div>
</main>

<script src="js/jquery-2.0.0.js"></script>
<script src="js/dashboard/assignment_events.js"></script>

<script type="text/javascript">
    AssignmentEvents = new AssignmentEvents();

</script>
<?php
        }
