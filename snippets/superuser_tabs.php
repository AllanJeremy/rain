<div class="container">
    <div class="row main-tab active-bar" id="dashboardTab">
        <p class="grey-text">Account</p>
        <div class="divider"></div>
        <br>
        <?php
        
        $userData['username'] = 'GaMuchiri';
        $userData['accType'] = 'superuser';
        
        ?>
        
        <p>Username: <span> <?php echo $userData['username']; ?> </span></p>
        <p>Account type: <span> <?php echo $userData['accType']; ?> </span></p>
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
                                <label for="new_username">New username</label>
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
                                <label for="current_password">Current password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                <input id="newPassword" type="password" class="validate" name="new_password">
                                <label for="new_password">New password</label>
                            </div>
                            <div class="input-field col m6 s12">
                                <input id="inputPasswordConfirm" type="password" class="validate" name="input-password-confirm">
                                <label for="input-password-confirm">Confirm new password</label>
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
    <!----->
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
                            <input id="newStudentId" type="number" class="validate" name="new_student_id">
                            <label for="new_student_id">Student id</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentFirstName" type="text" class="validate" name="new_student_first_name">
                            <label for="new_student_first_name">First name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentSecondName" type="text" class="validate" name="new_student_second_name">
                            <label for="new_student_second_name">Second name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentUsername" type="text" class="validate" name="new_student_username">
                            <label for="new_student_username">Username</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentPassword" type="password" class="validate" name="new_student_password">
                            <label for="new_student_password">Password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStudentConfirmPassword" type="password" class="validate" name="new_student_confirm_password">
                            <label for="new_student_confirm_password">Confirm password</label>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="input-field col s12">
                            <a class="right btn" type="submit" >Create account</a>
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
                            <label for="import-database-name">Database name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="importTableName" type="text" class="validate" name="import-table-name">
                            <label for="import-table-name">Table name</label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="input-field col s12">
                            <a class="right btn" type="submit" >Import students</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="manageStudent" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <div class="row" id="studentFilterList">
                    <form class="col s12" action="">
                        <div class="row">
                            <div class="input-field col m5 s12">
                                <select>
                                    <option value="" disabled selected>Bulk action</option>
                                    <option value="1">action 1</option>
                                    <option value="2">action 2</option>
                                    <option value="3">action 3</option>
                                </select>
                                <label>Bulk action</label>
                            </div>
                            <div class="input-field col m5 s9">
                                <input id="filterListSearch" type="text" class="validate" name="filter-list-search">
                                <label for="filter-list-search">Search</label>
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
                                            <label for="filled-box-1">Admin no.</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-2" />
                                            <label for="filled-box-2">F. name</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-3" />
                                            <label for="filled-box-3">L. name</label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <table class="bordered responsive-table">
                    <thead>
                        <tr>
                            <th data-field="id">Admin no.</th>
                            <th data-field="name">First Name</th>
                            <th data-field="name">Last Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>3012</td>
                            <td>Alvin</td>
                            <td>Eclair</td>
                        </tr>
                        <tr>
                            <td>3013</td>
                            <td>Jonathan</td>
                            <td>Lollipop</td>
                        </tr>
                        <tr>
                            <td>3014</td>
                            <td>Alvin</td>
                            <td>Eclair</td>
                        </tr>
                        <tr>
                            <td>3015</td>
                            <td>Alan</td>
                            <td>Jellybean</td>
                        </tr>
                        
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!----->
    <div class="row main-tab" id="teachersTab">
        <div class="col s12 m10 offset-m1">
        <ul class="tabs">
            <li class="tab col s6">
                <a href="#createTeacher">Create</a>
            </li>
            <li class="tab col s6">
                <a class="active" href="#manageTeacher" >Manage</a>
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
                            <input id="newTeacherFirstName" type="text" class="validate" name="new_teacher_first_name">
                            <label for="new_teacher_first_name">First name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherLastName" type="text" class="validate" name="new_teacher_last_name">
                            <label for="new_teacher_last_name">Last name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherEmail" type="email" class="validate" name="new_teacher_email">
                            <label for="new_teacher_email">Email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherUsername" type="text" class="validate" name="new_teacher_username">
                            <label for="new_teacher_username">Username</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStaffId" type="number" class="validate" name="new_staff_id">
                            <label for="new_staff_id">Staff ID</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherPassword" type="password" class="validate" name="new_teacher_password">
                            <label for="new_teacher_password">Password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newTeacherConfirmPassword" type="password" class="validate" name="new_teacher_confirm_password">
                            <label for="new_teacher_confirm_password">Confirm password</label>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="input-field col s12">
                            <a class="right btn" type="submit" >Create account</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="manageTeacher" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <div class="row" id="teacherFilterList">
                    <form class="col s12" action="">
                        <div class="row">
                            <div class="input-field col m5 s12">
                                <select>
                                    <option value="" disabled selected>Bulk action</option>
                                    <option value="1">action 1</option>
                                    <option value="2">action 2</option>
                                    <option value="3">action 3</option>
                                </select>
                                <label>Bulk action</label>
                            </div>
                            <div class="input-field col m5 s9">
                                <input id="filterListSearch" type="text" class="validate" name="filter-list-search">
                                <label for="filter-list-search">Search</label>
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
                                            <label for="filled-box-1">Admin no.</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-2" />
                                            <label for="filled-box-2">F. name</label>
                                        </p>
                                    </div>
                                    <div class="input-field col s6 m4">
                                        <p>
                                            <input type="checkbox" class="filled-in" id="filled-box-3" />
                                            <label for="filled-box-3">L. name</label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <table class="bordered responsive-table">
                    <thead>
                        <tr>
                            <th data-field="id">Admin no.</th>
                            <th data-field="name">First Name</th>
                            <th data-field="name">Last Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>5712</td>
                            <td>Alvin</td>
                            <td>Eclair</td>
                        </tr>
                        <tr>
                            <td>5713</td>
                            <td>Jonathan</td>
                            <td>Lollipop</td>
                        </tr>
                        <tr>
                            <td>5714</td>
                            <td>Alvin</td>
                            <td>Eclair</td>
                        </tr>
                        <tr>
                            <td>5715</td>
                            <td>Alan</td>
                            <td>Jellybean</td>
                        </tr>
                        
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!----->
    <div class="row main-tab" id="principalTab">
        <div class="col s12 m10 offset-m1">
        <ul class="tabs">
            <li class="tab col s6">
                <a class="active" href="#createPrinciple">Create</a>
            </li>
            <li class="tab col s6">
                <a href="#managePrinciple" >Manage</a>
            </li>
        </ul>
        </div>
        <div id="createPrinciple" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <br>
                <form class="col s12" method="post" action="">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipleFirstName" type="text" class="validate" name="new_principle_first_name">
                            <label for="new_principle_first_name">First name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipleLastName" type="text" class="validate" name="new_principle_last_name">
                            <label for="new_principle_last_name">Last name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipleEmail" type="email" class="validate" name="new_principle_email">
                            <label for="new_principle_email">Email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipleUsername" type="text" class="validate" name="new_principle_username">
                            <label for="new_principle_username">Username</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newStaffId" type="number" class="validate" name="new_staff_id">
                            <label for="new_staff_id">Staff ID</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrinciplePassword" type="password" class="validate" name="new_principle_password">
                            <label for="new_principle_password">Password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="newPrincipleConfirmPassword" type="password" class="validate" name="new_principle_confirm_password">
                            <label for="new_principle_confirm_password">Confirm password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <p>
                                <input type="checkbox" id="createTeacherAccountFromPrinciple" />
                                <label for="createTeacherAccountFromPrinciple">Create a corresponding teacher account</label>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <a class="right btn" type="submit" >Create account</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="managePrinciple" class="col s12 offset-m1 m10 ">
            <div class="row">
                <br>
                <br>
                <div class="row" id="principleFilterList">
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
                <table class="bordered responsive-table">
                    <thead>
                        <tr>
                            <th data-field="id">Admin no.</th>
                            <th data-field="name">First Name</th>
                            <th data-field="name">Last Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>5712</td>
                            <td>Alvin</td>
                            <td>Eclair</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>