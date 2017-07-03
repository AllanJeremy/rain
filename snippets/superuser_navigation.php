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
const SECTION_SU_PRINCIPALS = "princiapls";
const SECTION_SU_SUPERUSERS = "superusers";


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
    <li class="active">
        <a href="#!" id="dashboard" data-activates="dashboardTab" class="">Dashboard</a>
    </li>
    <li class="">
        <a href="#!" id="students" data-activates="studentsTab" class="">Students</a>
    </li>
    <li class="">
        <a href="#!" id="teachers" data-activates="teachersTab" class="">Teachers</a>
    </li>
    <li class="">
        <a href="#!" id="principal" data-activates="principalTab" class="">Principal</a>
    </li>
    <li class="">
        <a href="#!" id="superuser" data-activates="superuserTab" class="">Superuser</a>
    </li>

    <!--Chat and groups ~ Future feature-->
    <li>
        <a href="#!" class="hide" id="chat" data-activates="superuserChatTab">Chat</a>
    </li>
    <li>
        <a href="#!" class="hide" id="groups" data-activates="superuserGroupsTab">Groups</a>
    </li>

    <!--Account nav-->
    <li>
        <a href="#!" class="" id="account" data-activates="superuserAccountTab">Account</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="?action=admin_logout">Logout</a>
    </li>
</ul>
