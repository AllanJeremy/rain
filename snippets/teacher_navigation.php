<?php
/*
Constants to be used by the teacher navigation. ~ names of the sections and tabs
Note: TR is short for Teacher
Naming convention being used it TypeOfTheConst_OwnerOfTheConst_NameOfTheConst
*/

//Sections
const SECTION_TR_BASE = "classrooms";
const SECTION_TR_ASS_CREATE = "create-assignment";
const SECTION_TR_ASS_SENT = "sent-assignments";
const SECTION_TR_ASS_SUBS = "assignment-submissions";
const SECTION_TR_SCHEDULES = "schedules";
const SECTION_TR_TEST_CREATE = "create-test";
const SECTION_TR_TEST_VIEW_RESULTS = "view-test-results";
const SECTION_TR_TEST_TAKE = "take-test";


//Navigation active classes
$classrooms_class = $ass_class = $create_ass_class = $sent_ass_class = $ass_sub_class = $schedules_class = $tests_class = $create_test_class = $view_test_results_class = $take_test_class = $resources_class = $account_class = "";

global $pageTitle;#Get the global variable representing the page title
switch($section)
{
    case SECTION_TR_BASE:
        $classrooms_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "CLASSROOMS";
    break;
    case SECTION_TR_ASS_CREATE:
        $ass_class = BASE_ACTIVE_CLASS;
        $create_ass_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "CREATE ASSIGNMENT";
    break;
    case SECTION_TR_ASS_SENT:
        $ass_class = BASE_ACTIVE_CLASS;
        $sent_ass_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "SENT ASSIGNMENTS";
    break;
    case SECTION_TR_ASS_SUBS:
        $ass_class = BASE_ACTIVE_CLASS;
        $ass_sub_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "ASSIGNMENT SUBMISSIONS";
    break;
    case SECTION_TR_SCHEDULES:
        $schedules_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "SCHEDULES";
    break;
    case SECTION_TR_TEST_CREATE:
        $tests_class = BASE_ACTIVE_CLASS;
        $create_test_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "CREATE A TEST";
    break;
    case SECTION_TR_TEST_VIEW_RESULTS:
        $tests_class = BASE_ACTIVE_CLASS;
        $view_test_results_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "VIEW TEST RESULTS";
    break;
    case SECTION_TR_TEST_TAKE:
        $tests_class = BASE_ACTIVE_CLASS;
        $take_test_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "TAKE TEST";
    break;
    case SECTION_RESOURCES:
        $resources_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = PAGE_TITLE_RESOURCES;
    break;
    case SECTION_ACCOUNT:
        $account_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = PAGE_TITLE_ACCOUNT;
    break;
}
?>
<ul id="slide-out" class="side-nav fixed">
    <li>
        <div class="userView">
            <img class="background" src="images/esomo-nav-bg-02.jpg" width="300">
            <!--<a href="#" onclick="hideSideNav()" data-activates="slide-out" class="button-collapse right"><i class="material-icons">menu</i></a>
            -->
            <a href="#!name" class="no-padding">
                <span class="white-text name"><?php echo $_SESSION["admin_username"] ?></span>
            </a>
            <!--<p><span class="white-text class">class 10B</span></p>-->
            <br>
        </div>
    </li>
    <li <?php echo $classrooms_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_TR_BASE);?>">Classrooms</a>
    </li>
    <ul class="collapsible collapsible-accordion" >
        <li>
            <a class="collapsible-header <?php echo $ass_class;?>">Assignments</a>
            <div class="collapsible-body">
                <ul>
                    <li <?php echo $create_ass_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_TR_ASS_CREATE);?>">Create an assignment</a>
                    </li>
                    <li <?php echo $sent_ass_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_TR_ASS_SENT);?>">Sent assignments</a>
                    </li>
                    <li <?php echo $ass_sub_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_TR_ASS_SUBS);?>">Submissions</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <li <?php echo $schedules_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_TR_SCHEDULES);?>">Schedules</a>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li>
            <a class="collapsible-header <?php echo $tests_class;?>">Tests</a>
            <div class="collapsible-body">
                <ul>
                    <li <?php echo $create_test_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_TR_TEST_CREATE);?>">Create test</a>
                    </li>
                    <li <?php echo $view_test_results_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_TR_TEST_VIEW_RESULTS);?>">View test results</a>
                    </li>
                    <li <?php echo $take_test_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_TR_TEST_TAKE);?>">Take a test</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>

    <li <?php echo $resources_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_RESOURCES);?>">Resources</a>
    </li>

    <!--Chat and groups ~ Future feature-->
    <!--<li>
        <a href="#!" class="hide">Chat</a>
    </li>
    <li>
        <a href="#!" class="hide">Groups</a>
    </li>-->

    <!--Account nav-->
    <li <?php echo $account_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_ACCOUNT);?>">Account</a>
    </li>
    
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="?action=admin_logout">Logout</a>
    </li>
</ul>
