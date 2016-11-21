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
        <a href="#" id="s_statsOverview_link" data-activates="statsOverviewTab">Stats overview</a>
    </li>
    <li class="">
        <a href="#" id="s_schedules_link" data-activates="principalSchedulesTab">Schedules</a>
    </li>
    <li class="">
        <a href="#" id="s_Assignments_link" data-activates="principleAssignmentsTab">Assignments</a>
    </li>
    <ul class="collapsible collapsible-accordion">
        <li class="">
            <a class="collapsible-header">Grades</a>
            <div class="collapsible-body">
                <ul>
                    <li class="">
                        <a href=""  id="s_StudentGrades_link" data-activates="principleStudentGradesTab" class="">Student grades</a>
                    </li>
                    <li>
                        <a href=""  id="s_Gradebook_link" data-activates="principleGradebookTab" class="">Gradebook</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <li>
        <a href="#!" class="" id="s_Students_link" data-activates="principalStudentsTab">Students</a>
    </li>
    <li>
        <a href="#!" class="" id="s_Teachers_link" data-activates="principalTeachersTab">Teachers</a>
    </li>
    <li>
        <a href="#!" class="hide" id="s_Groups_link" data-activates="principalGroupsTab">Groups</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="waves-effect admin_logout_link" href="?action=admin_logout">Logout</a>
    </li>
</ul>