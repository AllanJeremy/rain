<?php
/*
Constants to be used by the teacher navigation. ~ names of the sections and tabs
Note: TR is short for Teacher
Naming convention being used it TypeOfTheConst_OwnerOfTheConst_NameOfTheConst
*/

//Sections
const SECTION_ST_BASE = "received-assignments";
const SECTION_ST_ASS_SENT = "sent-assignments";
const SECTION_ST_TEST_TAKE = "take-test";
const SECTION_ST_TEST_RESULTS = "test-results";


//Navigation active classes
$ass_class = $received_ass_class = $sent_ass_class = $tests_class = $take_test_class = $test_results_class = $resources_class = $account_class = "";

global $pageTitle;#Get the global variable representing the page title
switch($section)
{
    case SECTION_ST_BASE:
        $ass_class = BASE_ACTIVE_CLASS;
        $received_ass_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "RECEIVED ASSIGNMENTS";
    break;
    case SECTION_ST_ASS_SENT:
        $ass_class = BASE_ACTIVE_CLASS;
        $sent_ass_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "SENT ASSIGNMENTS";
    break;
    case SECTION_ST_TEST_TAKE:
        $tests_class = BASE_ACTIVE_CLASS;
        $take_test_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "TAKE A TEST";
    break;
    case SECTION_ST_TEST_RESULTS:
        $tests_class = BASE_ACTIVE_CLASS;
        $test_results_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "VIEW TEST RESULTS";
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
            <a id="name" href="#!name" class="no-padding"><span class="white-text name"><?php echo $_SESSION["student_username"] ?></span></a>
        </div>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li>
            <a class="collapsible-header <?php echo $ass_class;?>">Assignments</a>
            <div class="collapsible-body">
                <ul>
                    <li <?php echo $received_ass_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_ST_BASE);?>">Received assignments</a>
                    </li>
                    <li <?php echo $sent_ass_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_ST_ASS_SENT);?>">Sent assignments</a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a class="collapsible-header waves-effect <?php echo $tests_class;?>">Tests</a>
            <div class="collapsible-body">
                <ul>
                    <li <?php echo $take_test_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_ST_TEST_TAKE);?>">Take a test</a>
                    </li>
                    <li <?php echo $test_results_class;?>>
                        <a href="<?php echo GetSectionLink(SECTION_ST_TEST_RESULTS);?>">Test results</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
    
    <li <?php echo $resources_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_RESOURCES);?>">Resources</a>
    </li>

    <!--Account nav-->
    <li <?php echo $account_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_ACCOUNT);?>">Account</a>
    </li>

    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect student_logout_link" href="?action=student_logout">Logout</a>
    </li>
</ul>
