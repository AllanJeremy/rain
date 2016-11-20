<ul id="slide-out" class="side-nav fixed">
    <li>
        <div class="userView">
            <img class="background" src="images/MASAI-13.jpg" width="300">
            <!--<a href="#" onclick="hideSideNav()" data-activates="slide-out" class="button-collapse right"><i class="material-icons">menu</i></a>
            -->
            <a href="#!user" class="hide"><img class="circle" src="images/ppic.jpg"></a>
            <a href="#!name" class="no-padding"><span class="white-text name">Teacher Name</span></a>
            <!--<p><span class="white-text class">class 10B</span></p>-->
            <br>
        </div>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li class="">
            <a class="collapsible-header active">Assignments<span class="new badge">4</span></a>
            <div class="collapsible-body">
                <ul>
                    <li class="active">
                        <a id="createAssignments" data-activates="createAssignmentsTab" class="" href="">Create an assignment</a>
                    </li>
                    <li>
                        <a id="sentAssignments" data-activates="sentAssignmentsTab" class="" href="">Sent assignments</a>
                    </li>
                    <li>
                        <a id="receivedAssignments" class="" data-activates="receivedAssignmentsTab" href="">Received assignments</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="">
            <a class="collapsible-header">Tests</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a class="" id="createTest" data-activates="createTestTab" href="">Create test</a>
                    </li>
                    <li>
                        <a href="" id="testResults" data-activates="viewStudentsTestResultTab" class="">View students' test results</a>
                    </li>
                    <li>
                        <a href="" id="takeATest" data-activates="takeTestTab" class="">Take a test</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="">
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
        <a href="#!" id="schedules" data-activates="schedulesTab" class="">Schedules</a>
    </li>
    <li>
        <a href="#!" data-activates="classroomTab" id="classroom" class="">Classroom</a>
    </li>
    <li>
        <a href="#!" class="hide">Chat</a>
    </li>
    <li>
        <a href="#!" class="hide">Groups</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="#!">Logout</a>
    </li>
</ul>