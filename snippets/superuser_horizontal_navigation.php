<?php
/*
Constants to be used by the superuser navigation. ~ names of the sections and tabs
Note: SU is short for Superuser
Naming convention being used it TypeOfTheConst_OwnerOfTheConst_NameOfTheConst
*/

//Sections
const SECTION_SU_BASE = "dashboard";
const SECTION_SU_STUDENTS = "students";
const SECTION_SU_TEACHERS = "teachers";
const SECTION_SU_PRINCIPALS = "principals";
const SECTION_SU_SUPERUSERS = "superusers";

//Navigation active classes
$dashboard_class = $students_class = $teachers_class = $principals_class = $superusers_class = $resources_class = $account_class = "";

global $pageTitle;#Get the global variable representing the page title
switch($section)
{
    case SECTION_SU_BASE:
        $dashboard_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Dashboard";
    break;
    case SECTION_SU_STUDENTS:
        $students_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Student accounts";
    break;
    case SECTION_SU_TEACHERS:
        $teachers_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Teacher accounts";
    break;
    case SECTION_SU_PRINCIPALS:
        $principals_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Principal accounts";
    break;
    case SECTION_SU_SUPERUSERS:
        $superusers_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Superuser accounts";
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
    <li  <?php echo $dashboard_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_SU_BASE);?>" class="">Dashboard</a>
    </li>
    <li  <?php echo $students_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_SU_STUDENTS);?>"class="">Students</a>
    </li>
    <li  <?php echo $teachers_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_SU_TEACHERS);?>" class="">Teachers</a>
    </li>
    <li  <?php echo $principals_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_SU_PRINCIPALS);?>" class="">Principal</a>
    </li>
    <li  <?php echo $superusers_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_SU_SUPERUSERS);?>" class="">Superuser</a>
    </li>
</ul>
</div>

