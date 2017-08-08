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
        $pageTitle = "DASHBOARD";
    break;
    case SECTION_SU_STUDENTS:
        $students_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "STUDENT ACCOUNTS";
    break;
    case SECTION_SU_TEACHERS:
        $teachers_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "TEACHER ACCOUNTS";
    break;
    case SECTION_SU_PRINCIPALS:
        $principals_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "PRINCIPAL ACCOUNTS";
    break;
    case SECTION_SU_SUPERUSERS:
        $superusers_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "SUPERUSER ACCOUNTS";
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
            <a href="#!user" class="hide"><img class="circle" src="images/ppic.jpg"></a>
            <a href="#!name" class="no-padding"><span class="white-text name">
            <?php echo $_SESSION["admin_username"] ?></span></a>
            <br>
        </div>
    </li>
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

    <!--Chat and groups ~ Future feature-->
    <!--<li>
        <a href="<?php echo GetSectionLink(SECTION_CHAT);?>" class="hide" id="chat" data-activates="superuserChatTab">Chat</a>
    </li>
    <li>
        <a href="<?php echo GetSectionLink(SECTION_GROUPS);?>" class="hide" id="groups" data-activates="superuserGroupsTab">Groups</a>
    </li>-->

    <!--Account nav-->
    <li <?php echo $account_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_ACCOUNT);?>" class="">Account</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="?action=admin_logout">Logout</a>
    </li>
</ul>
