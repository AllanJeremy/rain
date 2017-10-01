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
        $pageTitle = "Received assignments";
    break;
    case SECTION_ST_ASS_SENT:
        $ass_class = BASE_ACTIVE_CLASS;
        $sent_ass_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Sent assignments";
    break;
    case SECTION_ST_TEST_TAKE:
        $tests_class = BASE_ACTIVE_CLASS;
        $take_test_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Take a test";
    break;
    case SECTION_ST_TEST_RESULTS:
        $tests_class = BASE_ACTIVE_CLASS;
        $test_results_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Test results";
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
<div class="_s12 container">
    <h4 class="page-title light white-text" id="pageTitle">
        <?php echo ucwords(@$pageTitle);?>
    </h4>
</div>
<div class="horizontal-overflow-wrapper">
<ul id="slide-out" class="horizontal-nav fixed">
    <li class="<?php echo $ass_class;?>">
        <a class="center dropdown-button <?php echo $ass_class;?>" data-beloworigin="true" href="#" data-activates="assDropDown">
        Assignments
            <i class="material-icons ">&#xE5C5;</i>
        </a>
        <ul id="assDropDown" class="dropdown-content ">
            <li <?php echo $received_ass_class;?>>
                <a href="<?php echo GetSectionLink(SECTION_ST_BASE);?>">Received</a>
            </li>
            <li <?php echo $sent_ass_class;?>>
                <a href="<?php echo GetSectionLink(SECTION_ST_ASS_SENT);?>">Sent</a>
            </li>
        </ul>
    </li>
    <li class="<?php echo $tests_class;?>">
        <a class="center dropdown-button <?php echo $tests_class;?>" data-beloworigin="true" href="#" data-activates="testsDropDown">
        Tests
            <i class="material-icons ">&#xE5C5;</i>
        </a>
        <ul id="testsDropDown" class="dropdown-content">
            <li <?php echo $take_test_class;?>>
                <a href="<?php echo GetSectionLink(SECTION_ST_TEST_TAKE);?>">Take test</a>
            </li>
            <li <?php echo $test_results_class;?>>
                <a href="<?php echo GetSectionLink(SECTION_ST_TEST_RESULTS);?>">View results</a>
            </li>
        </ul>
    </li>
    <li <?php echo $resources_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_RESOURCES);?>">Resources</a>
    </li>
</ul>
</div>

