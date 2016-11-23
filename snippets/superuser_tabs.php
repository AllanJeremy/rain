<?php 
    require_once (realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Allows retrieving information from database
?>

<div class="container">
    <div class="row main-tab active-bar" id="dashboardTab">
        <p class="grey-text">Account Information</p>
        <div class="divider"></div>
        <br>

        <div class="card-panel">
            <p><b>First Name: </b><span> <?php echo $_SESSION["admin_first_name"]; ?> </span></p>
            <p><b>Last Name: </b><span> <?php echo $_SESSION["admin_last_name"]; ?> </span></p>
            <p><b>Email Address: </b><span> <?php echo $_SESSION["admin_email"]; ?> </span></p>
            <p><b>Phone Number: </b><span> <?php echo  $_SESSION["admin_phone"]; ?> </span></p>
            <p><b>Username: </b><span> <?php echo $_SESSION["admin_username"]; ?> </span></p>
            <p><b>Account type: </b><span> <?php echo $_SESSION["admin_account_type"]; ?> </span></p>
        </div>
        <br>
        <br>
        <div class="">
            <div class="col m10 s12 offset-m1 card-panel indigo lighten-5">
                <div class="">
                    <br>
                    <h5>Change username</h5>
                    <br>
                    <p class="light">You can change your username here.</p>
                    
                    <form method="post" action="">
                        <div class="row">
                            <div class="input-field col s12 m5 offset-m1">
                                <input id="newUsername" type="text" class="validate" name="new_username">
                                <label for="newUsername">New username</label>
                            </div>
                            <div class="input-field col s12 m5 right-align">
                                <a type="submit" class="btn">Change username</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col s12 m10 offset-m1 card-panel amber lighten-5">
                <div class="">
                    <br>
                    <h5>Change password</h5>
                    <br>
                    <p class="light">You can change your username here.</p>
                    <form method="post" action="" class=" m8 s10 offset-m2 offset-s1 ">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="currentPassword" type="password" class="validate" name="current_password">
                                <label for="currentPassword">Current password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                <input id="newPassword" type="password" class="validate" name="new_password">
                                <label for="newPassword">New password</label>
                            </div>
                            <div class="input-field col m6 s12">
                                <input id="inputPasswordConfirm" type="password" class="validate" name="input-password-confirm">
                                <label for="inputPasswordConfirm">Confirm new password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 right-align">
                                <a type="submit" class="btn">Change password</a>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
        
    </div>
    <!---->
    <div class="row main-tab" id="studentsTab">
        <div class="col s12 m10 offset-m1">
        <ul class="tabs">
            <li class="tab col s4">
                <a href="#createStudent">Create</a>
            </li>
            <li class="tab col s4">
                <a  href="#importStudent" >Import</a>
            </li>
            <li class="tab col s4">
                <a class="active" href="#manageStudent" >Manage</a>
            </li>
        </ul>
        </div>
        <div id="createStudent" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <br>
                <form class="col s12" method="post" action="">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentId" type="number" class="validate" name="new_student_id" required>
                            <label for="newStudentId">Student id<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentFirstName" type="text" class="validate" name="new_student_first_name" required>
                            <label for="newStudentFirstName" >First name<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentLastName" type="text" class="validate" name="new_student_last_name" required>
                            <label for="newStudentLastName">Last name<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentUsername" type="text" class="validate" name="new_student_username" required>
                            <label for="newStudentUsername" >Username<sup>*</sup></label>
                        </div>
                    </div>

                    <div class="row">  
                        <div class="input-field col s12">
                            <button class="right btn" type="submit" >Create account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="importStudent" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <div class="col s12 no-data-message valign-wrapper grey lighten-3">
                    <h6 class="center-align valign grey-text " id="importMessage">
                        Some info here
                        <br>
                        Lorem ipsum
                    </h6>
                </div>
                <br>
                <br>
                <br>
                <br>
                <form class="col s12" method="post" action="">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="importDatabaseName" type="text" class="validate" name="import-database-name">
                            <label for="importDatabaseName" required>Database name<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="importTableName" type="text" class="validate" name="import-table-name">
                            <label for="importTableName" required>Table name<sup>*</sup></label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="input-field col s12">
                            <button class="right btn" type="submit" >Import students</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="manageStudent" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>

            <?php
                #Try to get students true if students found in database
                if($students = DbInfo::GetAllStudents()):
            ?>
                <div class="row" id="studentFilterList">
                    <form class="col s12" action="">
                        <div class="row">
                            <div class="input-field col m5 s12">
                                <select>
                                    <option value="" disabled selected>Bulk action</option>
                                    <option value="super_student_delete">Delete Account(s)</option>
                                    <option value="super_student_reset">Reset Account(s)</option>
                                    <option value="super_student_none">None</option>
                                </select>
                                <label>Bulk action</label>
                            </div>
                            <div class="input-field col m5 s9">
                                <input id="filterListSearch" type="text" class="validate" name="filter-list-search">
                                <label for="filterListSearch">Search</label>
                            </div>
                            <div class="input-field col m2 s3">
                                <a class="btn btn-floating waves-effect waves-light" type="submit"><i class="material-icons">search</i></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s2">
                                <p>Filter: </p>
                            </div>

                            <div class="input-field col s10">
                                <div class="row">
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-1" />
                                            <label for="filled-box-1">Adm no.</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-2" />
                                            <label for="filled-box-2">Name(s)</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-3" />
                                            <label for="filled-box-3">Username</label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!--Student list table-->
                <table class="bordered responsive-table" id="super_student_list_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th data-field="id">Adm. No</th>
                            <th data-field="name">Name(s)</th>
                            <th data-field="name">Username</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                        foreach($students as $student):#for each student - do this
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" value="<?php echo $student['acc_id']?>"
                                 class="filled-in" id="filled-box-student-<?php echo $student['acc_id']?>" />
                                <label for="filled-box-student-<?php echo $student['acc_id']?>"></label>
                            </td>
                            <td><?php echo $student["adm_no"]?></td>
                            <td><?php echo $student["first_name"] . " " . $student["last_name"] ?></td>
                            <td><?php echo $student["username"]?></td>
                        </tr>
                    <?php
                        endforeach;
                            
                        else: # No students were found
                    ?>
                    <div class="row">
                        <div class="col s12 m10 offset-m1">
                            <div class="card-panel blue-grey darken-3 blue-grey-text text-lighten-4">
                                <h5 class="center">NO STUDENT ACCOUNTS FOUND</h5>
                                <div class="divider blue-grey lighten-2"></div>
                                <p class="blue-grey-text text-lighten-3">No student accounts were found. Student accounts will appear here once added.</p>
                            </div>
                        </div>
                    </div>
                    <?php
                        endif;
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!---->
    <div class="row main-tab" id="teachersTab">
        <div class="col s12 m10 offset-m1">
        <ul class="tabs">
            <li class="tab col s6">
                <a href="#createTeacher" class="active">Create</a>
            </li>
            <li class="tab col s6">
                <a class="" href="#manageTeacher" >Manage</a>
            </li>
        </ul>
        </div>
        <div id="createTeacher" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <br>
                <form class="col s12" method="post" action="">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherFirstName" type="text" class="validate" name="new_teacher_first_name" required>
                            <label for="newTeacherFirstName">First name<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherLastName" type="text" class="validate" name="new_teacher_last_name" required>
                            <label for="newTeacherLastName">Last name<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherEmail" type="email" class="validate" name="new_teacher_email" required>
                            <label for="newTeacherEmail">Email<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherPhone" type="text" class="validate" name="new_teacher_phone">
                            <label for="new_teacher_phone">Phone (Optional)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherUsername" type="text" class="validate" name="new_teacher_username" required>
                            <label for="new_teacher_username">Username<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStaffId" type="number" class="validate" name="new_teacher_staff_id">
                            <label for="newStaffId" required>Staff ID<sup>*</sup></label>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="input-field col s12">
                            <button class="right btn" type="submit" >Create account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="manageTeacher" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <?php
                    #Try to get principals : true if principals found in database
                    if($teachers = DbInfo::GetAllTeachers()):
                ?>

                <div class="row" id="teacherFilterList">
                    <form class="col s12" action="">
                        <div class="row">
                            <div class="input-field col m5 s12">
                                <select>
                                    <option value="" disabled selected>Bulk action</option>
                                    <option value="super_teacher_delete">Delete Account(s)</option>
                                    <option value="super_teacher_reset">Reset Account(s)</option>
                                    <option value="super_teacher_none">None</option>
                                </select>
                                <label>Bulk action</label>
                            </div>
                            <div class="input-field col m5 s9">
                                <input id="filterListSearch" type="text" class="validate" name="filter-list-search">
                                <label for="filterListSearch">Search</label>
                            </div>
                            <div class="input-field col m2 s3">
                                <a class="btn btn-floating waves-effect waves-light" type="submit"><i class="material-icons">search</i></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s2">
                                <p>Filter: </p>
                            </div>

                            <div class="input-field col s10">
                                <div class="row">
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-1" />
                                            <label for="filled-box-1">Staff ID</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-2" />
                                            <label for="filled-box-2">Name</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-3" />
                                            <label for="filled-box-3">Username</label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!--Teacher list table-->
                <table class="bordered responsive-table" id="super_teacher_list_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th data-field="id">Staff ID</th>
                            <th data-field="name">Name(s)</th>
                            <th data-field="name">Username</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php 
                        foreach ($teachers as $teacher):
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" value="<?php echo $teacher['acc_id']?>"
                                 class="filled-in" id="filled-box-admin-<?php echo $teacher['acc_id']?>" />
                                <label for="filled-box-admin-<?php echo $teacher['acc_id']?>"></label>
                            </td>
                            <td><?php echo $teacher["staff_id"] ?></td>
                            <td><?php echo $teacher["first_name"] . " " . $teacher["last_name"]  ?></td>
                            <td><?php echo $teacher["username"] ?></td>
                        </tr>
                    <?php
                        endforeach;

                        else:#no principal accounts were found 
                    ?>

                    <div class="row">
                        <div class="col s12 m10 offset-m1">
                            <div class="card-panel blue-grey darken-3 blue-grey-text text-lighten-4">
                                <h5 class="center">NO TEACHER ACCOUNTS FOUND</h5>
                                <div class="divider blue-grey lighten-2"></div>
                                <p class="blue-grey-text text-lighten-3">No teacher accounts were found. Teacher accounts will appear here once added.</p>
                            </div>
                        </div>
                    </div>

                    <?php
                        endif;#end if statement to used check if there are teachers
                    ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!---->
    <div class="row main-tab" id="principalTab">
        <div class="col s12 m10 offset-m1">
        <ul class="tabs">
            <li class="tab col s6">
                <a class="active" href="#createPrincipal">Create</a>
            </li>
            <li class="tab col s6">
                <a href="#managePrincipal" >Manage</a>
            </li>
        </ul>
        </div>
        <div id="createPrincipal" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <br>
                <form class="col s12" method="post" action="">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipalFirstName" type="text" class="validate" name="new_principal_first_name" required>
                            <label for="newPrincipalFirstName">First name<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipalLastName" type="text" class="validate" name="new_principal_last_name" required>
                            <label for="newPrincipalLastName">Last name<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipalEmail" type="email" class="validate" name="new_principal_email" required>
                            <label for="newPrincipalEmail">Email<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipalPhone" type="text" class="validate" name="new_principal_phone">
                            <label for="newPrincipalPhone">Phone (Optional)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipalUsername" type="text" class="validate" name="new_principal_username" required>
                            <label for="newPrincipalUsername">Username<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStaffId" type="number" class="validate" name="new_principal_staff_id" required>
                            <label for="newStaffId">Staff ID<sup>*</sup></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <p>
                                <input type="checkbox" id="createTeacherAccountFromPrincipal" name="create_corresponding_teacher_account"/>
                                <label for="createTeacherAccountFromPrincipal">Create a corresponding teacher account</label>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <button class="right btn" type="submit" >Create account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="managePrincipal" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>

            <?php
                #Try to get principals : true if principals found in database
                if($principals = DbInfo::GetAllPrincipals()):
            ?>
                <div class="row" id="principalFilterList">
                    <form class="col s12" action="">
                        <div class="row">
                            <div class="input-field col m4 s6">
                                <a class="btn btn-flat waves-effect waves-light" type="submit">Edit account</a>
                            </div>
                            <div class="input-field col m4 s6">
                                <a class="btn btn-flat waves-effect waves-light" type="submit">Reset account</a>
                            </div>
                            <div class="input-field col m4 s6">
                                <a class="btn btn-flat waves-effect waves-light" type="submit">Delete account</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!--Principal list table-->
                <table class="bordered responsive-table" id="super_principal_list_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th data-field="id">Staff ID</th>
                            <th data-field="name">Name(s)</th>
                            <th data-field="name">Username</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php 
                        foreach ($principals as $principal):
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" value="<?php echo $principal['acc_id']?>"
                                 class="filled-in" id="filled-box-admin-<?php echo $principal['acc_id']?>" />
                                <label for="filled-box-admin-<?php echo $principal['acc_id']?>"></label>
                            </td>
                            <td><?php echo $principal["staff_id"] ?></td>
                            <td><?php echo $principal["first_name"] . " " . $principal["last_name"] ?></td>
                            <td><?php echo $principal["username"] ?></td>
                        </tr>
                    <?php
                        endforeach;

                        else:#no principal accounts were found 
                    ?>

                    <div class="row">
                        <div class="col s12 m10 offset-m1">
                            <div class="card-panel blue-grey darken-3 blue-grey-text text-lighten-4">
                                <h5 class="center">NO PRINCIPAL ACCOUNTS FOUND</h5>
                                <div class="divider blue-grey lighten-2"></div>
                                <p class="blue-grey-text text-lighten-3">No principal accounts were found. Principal accounts will appear here once added.</p>
                            </div>
                        </div>
                    </div>

                    <?php
                        endif;
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>