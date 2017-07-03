<?php
/*
Constants to be used by the principal navigation. ~ names of the sections and tabs
Note: PR is short for Principal
Naming convention being used it TypeOfTheConst_OwnerOfTheConst_NameOfTheConst
*/

//Sections
const SECTION_PR_BASE = "stats-overview";
const SECTION_PR_SCHEDULES = "schedules";
const SECTION_PR_ASSIGNMENTS = "assignments";

//Navigation active classes
$stats_overview_class = $schedules_class = $ass_class = $resources_class = $account_class = "";

global $pageTitle;
switch($section)
{
    case SECTION_PR_BASE:
        $stats_overview_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "STATS OVERVIEW";
    break;
    case SECTION_PR_SCHEDULES:
        $schedules_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "SCHEDULES";
    break;
    case SECTION_PR_ASSIGNMENTS:
        $ass_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "ASSIGNMENTS";
    break;
    case SECTION_RESOURCES:
        $resources_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "RESOURCES";
    break;
    case SECTION_ACCOUNT:
        $account_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "ACCOUNT";
    break;
}

?>

<ul id="slide-out" class="side-nav fixed">
    <li>
        <div class="userView">
            <img class="background" src="images/esomo-nav-bg-02.jpg" width="300">
            <!--<a href="#" onclick="hideSideNav()" data-activates="slide-out" class="button-collapse right"><i class="material-icons">menu</i></a>-->
            
            <a href="#!user" class="hide"><img class="circle" src="images/ppic.jpg"></a>

            <a href="#!name" class="no-padding"><span class="white-text name">
            <?php echo $_SESSION["admin_username"] ?></span></a>

    
            <!--<p><span class="white-text class">class 10B</span></p>-->
            <br>
        </div>
    </li>
    <li <?php echo $stats_overview_class;?>>
        <a href="<?php echo './?section='.SECTION_PR_BASE;?>">Stats overview</a>
    </li>
    <li <?php echo $schedules_class;?>>
        <a href="<?php echo './?section='.SECTION_PR_SCHEDULES;?>">Schedules</a>
    </li>
    <li <?php echo $ass_class;?>>
        <a href="<?php echo './?section='.SECTION_PR_ASSIGNMENTS;?>">Assignments</a>
    </li>
    <!--<ul class="collapsible collapsible-accordion hide">
        <li class="">
            <a class="collapsible-header">Grades</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a href=""  id="studentGrades" class="">Student grades</a>
                    </li>
                    <li>
                        <a href=""  id="gradeBook" class="">Gradebook</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>-->
    <!--<li>
        <a href="#!" class="hide" id="students" data-activates="principalStudentsTab">Students</a>
    </li>
    <li>
        <a href="#!" class="hide" id="teachers" data-activates="principalTeachersTab">Teachers</a>
    </li>-->
    <li <?php echo $resources_class;?>>
        <a href="<?php echo './?section='.SECTION_RESOURCES;?>" class="">Resources</a>
    </li>

    <!--Chat and groups ~ Future feature-->
    <li>
        <a href="#!" class="hide">Chat</a>
    </li>
    <li>
        <a href="#!" class="hide">Groups</a>
    </li>

    <!--Account nav-->
    <li <?php echo $account_class;?>>
        <a href="<?php echo './?section='.SECTION_ACCOUNT;?>" class="">Account</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="?action=admin_logout">Logout</a>
    </li>
</ul>
