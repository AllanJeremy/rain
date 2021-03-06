    <?php
if (!isset($_SESSION["student_adm_no"]) && !isset($_SESSION['admin_acc_id'])) {

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
    ?>

<?php
    //Find if student had already submitted the assignment
    //Will show the files the student submitted instead of the tinyMCE.
    //Faster
    $ass_submission_exists = DbInfo::GetAssSubmissionsByAssId($assignment['ass_id']);

    if($ass_submission_exists) {

        foreach($ass_submission_exists as $ass_submission_exist) {
            //$ass_submission_exists = true;
            if($ass_submission_exist['submitted'] == 1) {
                $submitted = '1';
            } else {
                $submitted = '0';
            }
        }
    } else {
            $submitted = '0';
    }
?>
<header data-comments-enabled="<?php echo $assignment['comments_enabled']; ?>" data-submitted="<?php echo $submitted; ?>" data-assignment-id="<?php echo $assignment['ass_id']; ?>" class="">
    <div class="no-margin progress pace js-progress-bar" style="width:0%;">
        <div class="determinate" style="width:0%;"></div>
    </div>

    <div class="rain-theme-primary">
        <div class="container ">
            <br>
            <div class="row no-bottom-margin">
                <h5 class="col s10 php-data white-text">Title: <?php echo $assignment['ass_title']; ?></h5>
                <p class="col s12 php-data white-text margin-vert-8">From <?php echo $assignment['teacher_name']['first_name'] .' '. $assignment['teacher_name']['last_name'] ; ?></p>
                <h5 class="col s12 php-data white-text">Description</h5>
                <p class="col s11 php-data white-text"><?php echo $assignment['ass_description']; ?></p>
            </div>
            <br>
        </div>
        <div class="assignment-action-header marg-16">
            <?php
            if(!isset($_SESSION['admin_acc_id'])) :
            if($submitted == '1' && isset($_SESSION["student_adm_no"])) :
            ?>
            <a class="btn green btn-large js-submit-assignment disabled"  href="#submitAssignment"><i class="material-icons right white-text">done</i>Submitted</a>
            <p class="center-align js-assignment-due green lighten-3 pad-6 white-text">Submitted on <?php echo EsomoDate::GetOptimalDateTime($ass_submission_exist['date_submitted'])['day'] .' '. EsomoDate::GetOptimalDateTime($ass_submission_exist['date_submitted'])['date']; ?></p>
            <?php
            if($ass_submission_exist['returned'] == '1') :
            ?>
            <h6 class="right-align green-text text-lighten-5">Graded</h6>
            <h5 class="right-align green-text text-accent-2"><?php echo $ass_submission_exist['grade']; ?></h5>
            <?php
            else:
            ?>
            <h6 class="right-align green-text text-lighten-5">Not marked yet</h6>
            <?php
            endif;
            else:
            ?>

            <a class="btn btn-large js-submit-assignment disabled" href="#submitAssignment">Submit</a>
            <p class="center-align js-assignment-due <?php echo EsomoDate::GetDueText($assignment['due_date'])['due_class']; ?> pad-6 white-text"><?php echo EsomoDate::GetDueText($assignment['due_date'])['due_text']; ?></p>
            <?php
            endif;
            else:
            ?>
            <p class="marg-16 center-align green lighten-2 js-assignment-due pad-16 white-text">Sent on <?php echo EsomoDate::GetOptimalDateTime($assignment['due_date'])['day'] .' '. EsomoDate::GetOptimalDateTime($assignment['due_date'])['date']; ?></p>
            <?php
            endif;
            ?>
        </div>
    </div>
    <div class="rain-theme-primary lighten-1 row pin-nav-top">

        <div class="col s12">
            <a class="white-text btn-flat btn marg-6 hide"><i class="material-icons">arrow_back</i></a>
            <ul class="tabs inline tabs-transparent">
                <?php
    if(isset($_GET['sect'])) {
        if($_GET['sect'] == 'resources' && isset($_GET['sect'])) {
            $res_tab_class = 'active';
            $ass_tab_class = '';
        } else {
            $res_tab_class = '';
            $ass_tab_class = 'active';
        }
    } else {
        $res_tab_class = '';
        $ass_tab_class = 'active';
    }
    if(isset($_SESSION['admin_acc_id'])) {
        $res_tab_class = 'active';
        $ass_tab_class = '';

    }
    if(!isset($_SESSION["admin_acc_id"])):
                ?>

                <li class="tab col s6"><a class="<?php echo $ass_tab_class; ?>" href="#myAssignment">My assignment</a></li>
                <?php
    endif;
    ?>
                <li class="tab col <?php if(isset($_SESSION['student_adm_no'])) {echo 's6';} else { echo 's12';} ?>"><a href="#resources" class="<?php echo $res_tab_class; ?>">resources</a></li>
            </ul>

        <?php
        if(!isset($_SESSION['admin_acc_id'])) :

            if($submitted != '1') :
           ?>
            <a class="white-text btn-flat hide btn right marg-6 js-download-assignment" title="download the whole assignment">download</a>
            <a class="white-text btn-flat btn right marg-6 js-upload-assignment" title="upload your assignment">upload </a>
        <?php
           endif;
           endif;
        ?>
        </div>
    </div>

</header>
<main>
    <?php
    if(!isset($_SESSION['admin_acc_id'])) :
    if($submitted == '1') :
    ?>
    <?php //Get the resources uploaded, loop them hear?>

    <div id="myAssignment" class="row container">
        <p>Documents you submitted</p>
        <div class="divider"></div>
        <br>
        <?php

        $attachments = explode(',',$ass_submission_exist['attachments']);
        array_pop($attachments);

        for($i = 0; $i < count($attachments); $i++) :
            //echo $attachments[$i];
        ?>

        <div class="col m4 s6"  data-index="<?php echo $i; ?>">
            <div class="card document-view">
                <!--icon for the type of media?-->
                <i class="material-icons">&#xE24D;</i>
                <div class="info row no-margin">
                    <div class="col s12">
                        <p class="title"><?php echo $attachments[$i]; ?> </p>
                        <p class="format"><?php echo explode(".",$attachments[$i])[count(explode(".",$attachments[$i])) - 1]; ?></p>
                        <p class="size"> unknown size</p>
                        <a target="_blank" href="./uploads/ass_submissions/<?php echo $attachments[$i]; ?>" class="btn-inline right">View</a>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endfor;
    ?>
    </div>
    <?php
    else:
    ?>

    <div id="myAssignment" class="row container">
        <div class="m12 s12 col l8 assignment-tinymce">
            <div hidefocus="0" class="rain-theme-primary row tinymce-toolbar inline-toolbar" id="mytoolbar">
            </div>
            <div class="header white row z-depth-2">
                <div class="col s6 input-field no-margin">
                    <input type="text" class="no-margin js-myAssignment-title" placeholder="assignment title" title="type your assignment title">

                </div>
                <div class="col s6">
                    <a class="js-save-myAssignment btn-flat marg-8 right" title="save your assignment">Save <i class="material-icons right">save</i></a>
                </div>
            </div>
            <div name="body" id="body" class="z-depth-1-half pad-16 inline-editor row tinymce-document">

            </div>
            <div id="editor"></div>
        </div>
        <div class="m12 col l3 offset-l1 hide-on-med-and-down">
            <div class="row resources-bar marg-16">
                <p class="grey-text">Resources</p>
                <div class="divider"></div>
                <div class="resource-bar">
                    <ul>
                        <?php

                        $attachments = explode(",",$assignment['attachments']);
                        array_pop($attachments);
                        foreach($attachments as $attachment):
                        ?>
                        <li class="margin-vert-8">
                            <a href="" class="black-text"><?php echo $attachment; ?></a>
                            <p class="no-margin grey-text text-lighten-1 secondary-title"><?php echo explode(".",$attachment)[count(explode(".",$attachment)) - 1]; ?></p>
                            <p class="no-margin grey-text text-lighten-1 secondary-title">unknown size</p>
                        </li>
                        <li class="divider"></li>
                        <?php
                        endforeach;
                        ?>

                        <!--TEMPLATE-->
                        <!--<li class="margin-vert-8">
                            <a href="" class="black-text">This type of resources</a>
                            <p class="no-margin grey-text text-lighten-1 secondary-title">PDF.</p>
                            <p class="no-margin grey-text text-lighten-1 secondary-title">23kb</p>
                        </li>
                        <li class="divider"></li>-->

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php
       endif;
       endif;
    ?>

    <div id="resources" class="row container">
        <div class="col s12">
            <div class="resources-bar"></div>
            <ul>
                <?php

                $attachments = explode(",",$assignment['attachments']);

                array_pop($attachments);
                foreach($attachments as $attachment):
                ?>
                <li class="margin-vert-8">
                    <a href="./uploads/assignments/<?php echo $attachment; ?>" target="_blank" class="black-text"><?php echo $attachment; ?></a>
                    <p class="no-margin grey-text text-lighten-1 secondary-title"><?php echo explode(".",$attachment)[count(explode(".",$attachment)) - 1]; ?></p>
                    <p class="no-margin grey-text text-lighten-1 secondary-title">23kb</p>
                </li>
                <li class="divider"></li>
                <?php
                endforeach;
                ?>

                <!--TEMPLATE-->
                <!--
                <li class="margin-vert-8">
                    <a href="" class="black-text">This type of resources</a>
                    <p class="no-margin grey-text text-lighten-1 secondary-title">PDF.</p>
                    <p class="no-margin grey-text text-lighten-1 secondary-title">23kb</p>
                </li>
                <li class="divider"></li>
                -->
            </ul>
        </div>
    </div>

    <div id="assignmentUpload" class="modal modal-fixed-footer">
        <div class="modal-content">
            <div class="js-drag-drop-area">
                <h4 class="white-text">
                    Upload assignment documents
                </h4>
                <div class="row no-margin">
                    <div class="js-assignment-total-info col m6 s12">
                        <h6 class=" op-4">To upload</h6>
                        <h4 class="white-text">
                            <span class="js-total-assignments">0</span> files
                        </h4>
                        <br>
                        <div class="progress js-progress-bar" style="width:0%;">
                            <div class="determinate" style="width:0%;"></div>
                        </div>
                        <h6 class="num-progress hide secondary-text-color">
                            <i>Uploading <span class="js-upload-num-progress">0%</span></i>
                        </h6>
                    </div>
                    <div class="col m6 s12">
                        <form id="addAssignmentForm">
                            <div class=" input-field col s12 file-field ">
                                <div class="btn right">
                                    <span>add assignment</span>
                                    <input type="file" multiple name="assignments">
                                </div>
                            </div>
                            <input type="submit" name="submitBtn" class="hide btn material-icons btn-floating btn-large upload-btn" value="&#xE2C6;" />
                        </form>
                        <div style="padding-top:20px;margin-top:20px;" class="hide-on-med-and-down">
                            <br>
                            <h6 class="right-align op-4">or drag and drop on the colored area.</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-margin js-ass-error-container">
                <ul></ul>
            </div>
            <div class="row js-ass-submission-list">
                <div class="container row" >
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" id="modalFooterCloseAction" class=" modal-action modal-close waves-effect waves-blue btn-flat">okay</a>
            <a href="#!" id="submitAssignment_Modal" class=" modal-action waves-effect waves-green btn disabled js-submit-assignment"><i class="material-icons right">send</i>submit</a>
        </div>
    </div>
    <div id="assignmentUploadConfirm" class="modal modal-fixed-footer">
        <div class="modal-content">
            <div class="js-drag-drop-area accent">
                <h5 class="white-text center-align">
                    Confirm assignment upload
                </h5>
                <div class="row no-margin">
                    <div class="js-assignment-total-info col s12">
                        <h6 class="center-align op-4">To upload</h6>
                        <h4 class="center-align white-text">
                            <span class="js-total-assignments">0</span> files
                        </h4>
                        <br>
                        <div class="row">
                            <div class="col s6 offset-s3">
                                <div class="progress js-progress-bar" style="width:0%;">
                                    <div class="determinate" style="width:0%;"></div>
                                </div>
                                <h6 class="center-align num-progress hide secondary-text-color">
                                    <i>Uploading <span class="js-upload-num-progress">0%</span></i>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col s12">
                        <div class="row container">
                            <div class="col s12 input-field no-margin">
                                <textarea class="op-4 materialize-textarea js-submission-text" title="Type a text for the teacher" name="submission_text"></textarea>
                                <label class="op-4" for="submission_text">Add a comment to your assignment for <?php echo $assignment['teacher_name']['first_name'] .' '. $assignment['teacher_name']['last_name'] ; ?> to read first.</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-margin js-ass-error-container">
                <ul></ul>
            </div>
            <div class="row js-ass-submission-list">
                <div class="container row" >
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" id="modalFooterCloseAction" class=" modal-action modal-close waves-effect waves-blue btn-flat">okay</a>
            <a href="#!" id="submitAssignment_Confirm_Modal" class=" modal-action waves-effect waves-green btn disabled js-confirm-submit-assignment"><i class="material-icons right">send</i>confirm</a>
        </div>
    </div>
</main>
<script type="text/javascript" src="js/jquery-2.0.0.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
<script type="text/javascript" src="js/js_print.js"></script>
<script type="text/javascript" src="js/dashboard/result.js"></script>
<script type="text/javascript" src="js/dashboard/lists_templates.js"></script>
<script type="text/javascript" src="js/dashboard/assignment_events.js"></script>
<script type="text/javascript" src="js/dashboard/resources_events.js"></script>
<script type="text/javascript" src="js/dashboard/comments_events.js"></script>
<script type="text/javascript" src="js/dashboard/events.js"></script>
<script type="text/javascript" src="js/dashboard/test_events.js"></script>
<script type="text/javascript" src="js/dashboard.js"></script>

<!--DON'T MESS WITH-->
<script src="tinymce/jquery.tinymce.min.js"></script>
<script src="tinymce/tinymce.min.js"></script>
<script src="js/materialize.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '#body',
        theme: 'modern',
        inline: true,
        skin: 'lightgray',
        //event_root: '#root',
        fixed_toolbar_container: '#mytoolbar',
        width: 'auto',
        height: 'auto',
        auto_focus: true,
        subfolder:"",
        toolbar1: 'forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
        toolbar2: 'undo redo | link unlink anchor image | fullscreen',
        forced_root_block: 0,
        relative_urls: false,
        plugins: [
            "advlist autolink autosave textpattern link image lists charmap preview hr anchor pagebreak",
            "table contextmenu directionality paste textcolor"
        ],
        image_advtab: false,
        toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect forecolor backcolor | link unlink anchor | image media"
    });
    //init the dashboard application except on login page
    if (location.pathname.split('/').pop() != 'login.php') {
        console.log(location);
        console.log(location.search);
        var dashboard = new Dashboard();
    }
    $(document).ready(function () {
        $('.modal').modal();
        
        var $target = $('.pin-nav-top'),
            $target2 = $('.inline-toolbar'),
            $target3 = $('.resources-bar'),
            $zeroOffset = $target.offset().top;

        $target.pushpin({
            top: $target.offset().top,
            bottom: $('main').outerHeight(),
            offset: 0
        });
        $target2.pushpin({
            top: $zeroOffset + $target.outerHeight(),
            bottom: $('main').outerHeight(),
            offset: $target.outerHeight()
        });
        $target3.pushpin({
            top: $zeroOffset + $target.outerHeight(),
            bottom: $('main').outerHeight(),
            offset: $target.outerHeight() + 160
        });
    });
</script>
<!--DON'T EVEN THINK ABOUT IT -->

<?php
}
?>
