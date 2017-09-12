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

global $pageTitle;#Get the global variable representing the page title
switch($section)
{
    case SECTION_PR_BASE:
        $stats_overview_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Stats overview";
    break;
    case SECTION_PR_SCHEDULES:
        $schedules_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Schedules";
    break;
    case SECTION_PR_ASSIGNMENTS:
        $ass_class = SetClass(BASE_ACTIVE_CLASS);
        $pageTitle = "Assignments";
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
    <li <?php echo $stats_overview_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_PR_BASE);?>">Stats overview</a>
    </li>
    <li <?php echo $schedules_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_PR_SCHEDULES);?>">Schedules</a>
    </li>
    <li <?php echo $ass_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_PR_ASSIGNMENTS);?>" class="<?php echo $ass_class;?>">Assignments</a>
    </li>
    <li <?php echo $resources_class;?>>
        <a href="<?php echo GetSectionLink(SECTION_RESOURCES);?>">Resources</a>
    </li>
</ul>
</div>

