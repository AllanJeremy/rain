<ul id="slide-out" class="side-nav fixed">
    <li>
        <div class="userView">
            <img class="background" src="images/esomo-nav-bg-02.jpg" width="300">
            <!--<a href="#" onclick="hideSideNav()" data-activates="slide-out" class="button-collapse right"><i class="material-icons">menu</i></a>
            -->
            <a id="name" href="#!name" class="no-padding"><span class="white-text name"><?php echo $_SESSION["student_username"] ?></span></a>
            <p><span class="white-text class">class 10B</span></p>
            <br>
        </div>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li class="">
            <a class="collapsible-header active">Assignments<span class="new badge">14</span></a>
            <div class="collapsible-body">
                <ul>
                    <li class="active">
                        <a id="recievedAssignments" data-activates="recievedAssignmentsTab" class="active-bar " href="">received assignments</a>
                    </li>
                    <li>
                        <a hidden="" id="sentAssignments" data-activates="sentAssignmentsTab" class="" href="">sent assignments</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="">
            <a class="collapsible-header waves-effect">Tests</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a class="" id="takeATest" data-activates="takeATestTab" href="">Take a test</a>
                    </li>
                    <li>
                        <a href="" id="testResults" data-activates="testResultsTab" class="">Test results</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="">
            <a class="collapsible-header waves-effect waves-light">Grades</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a href="" id="myGrades" data-activates="myGradesTab" class="">My grades</a>
                    </li>
                    <li>
                        <a href="" id="gradeBook" data-activates="gradeBookTab" class="">Gradebook</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <li>
        <a href="#!" class="hide" id="chat" data-activates="chatTab">Chat</a>
    </li>
    <li>
        <a href="#!" class="hide" id="groups" data-activates="groupsTab">Groups</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect student_logout_link" href="?action=student_logout">Logout</a>
    </li>
</ul>
