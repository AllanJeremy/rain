<ul id="slide-out" class="side-nav fixed">
    <li>
        <div class="userView">
            <img class="background" src="images/MASAI-13.jpg" width="300">
            <!--<a href="#" onclick="hideSideNav()" data-activates="slide-out" class="button-collapse right"><i class="material-icons">menu</i></a>
            -->
            <a id="name" href="#!name" class="no-padding"><span class="white-text name">Student Name</span></a>
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
                        <a id="r_Assignments_link" data-activates="recievedAssignments" class="" href="">received assignments</a>
                    </li>
                    <li>
                        <a hidden="" id="s_Assignments_link" data-activates="sentAssignments" class="" href="">sent assignments</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="">
            <a class="collapsible-header waves-effect">Tests</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a class="" id="" data-activates="takeATest" href="">Take a test</a>
                    </li>
                    <li>
                        <a href="" id="" data-activates="testResults" class="">Test results</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="">
            <a class="collapsible-header waves-effect waves-light">Grades</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a href="" id="" data-activates="myGrades" class="">My grades</a>
                    </li>
                    <li>
                        <a href="" id="" data-activates="gradeBook" class="">Gradebook</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <li>
        <a href="#!" class="hide" data-activates="chat">Chat</a>
    </li>
    <li>
        <a href="#!" class="hide" data-activates="groups">Groups</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect student_logout_link" href="#!">Logout</a>
    </li>
</ul>