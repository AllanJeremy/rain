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
    <li class="active" >
        <a href="#!" data-activates="classroomTab" id="classroom" class="">Classrooms</a>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li class="">
            <a class="collapsible-header">Assignments<span class="new badge">4</span></a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a id="createAssignments" data-activates="createAssignmentsTab" class="" href="">Create an assignment</a>
                    </li>
                    <li>
                        <a id="sentAssignments" data-activates="sentAssignmentsTab" class="" href="">Sent assignments</a>
                    </li>
                    <li>
                        <a id="submittedAssignments" class="" data-activates="submittedAssignmentsTab" href="">Submissions</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <li>
        <a href="#!" id="schedules" data-activates="schedulesTab" class="">Schedules</a>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li class="">
            <a class="collapsible-header">Tests</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a class="" id="createTest" data-activates="createTestTab" href="">Create test</a>
                    </li>
                    <li>
                        <a href="" id="testResults" data-activates="viewStudentsTestResultTab" class="">View test results</a>
                    </li>
                    <li>
                        <a href="" id="takeATest" data-activates="takeTestTab" class="">Take a test</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="hide">
            <a class="collapsible-header">Grades</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a href="" id="mySubjectGrades" data-activates="mySubjectGradesTab" class="">My subject grades</a>
                    </li>
                    <li>
                        <a href="" id="gradeBook" data-activates="gradeBookTab" class="">Gradebook</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>

    <li>
        <a href="#!" class="" id="resources" data-activates="teacherResourcesTab">Resources</a>
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
        <a href="#!" class="" id="account" data-activates="teacherAccountTab">Account</a>
    </li>
    
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="?action=admin_logout">Logout</a>
    </li>
</ul>
