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
    <li class="active">
        <a href="#" id="statsOverview" data-activates="statsOverviewTab">Stats overview</a>
    </li>
    <li class="">
        <a href="#" id="schedules" data-activates="principalSchedulesTab">Schedules</a>
    </li>
    <li class="">
        <a href="#" id="assignments" data-activates="principalAssignmentsTab">Assignments</a>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li class="">
            <a class="collapsible-header">Grades</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a href=""  id="studentGrades" data-activates="principalStudentGradesTab" class="">Student grades</a>
                    </li>
                    <li>
                        <a href=""  id="gradeBook" data-activates="principalGradebookTab" class="">Gradebook</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <li>
        <a href="#!" class="" id="students" data-activates="principalStudentsTab">Students</a>
    </li>
    <li>
        <a href="#!" class="" id="teachers" data-activates="principalTeachersTab">Teachers</a>
    </li>
    <li>
        <a href="#!" class="" id="resources" data-activates="principalResourcesTab">Resources</a>
    </li>

    <!--Chat and groups ~ Future feature-->
    <li>
        <a href="#!" class="hide">Chat</a>
    </li>
    <li>
        <a href="#!" class="hide">Groups</a>
    </li>

    <!--Account nav-->
    <li>
        <a href="#!" class="" id="account" data-activates="principalAccountTab">Account</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="?action=admin_logout">Logout</a>
    </li>
</ul>
