<!DOCTYPE html>

<html lang="en" >

<head>
        <?php require_once("handlers/header_handler.php");?>

        <title><?php echo MyHeaderHandler::GetPageTitle();?></title>

        <!--Site metadata-->
        <?php MyHeaderHandler::GetMetaData(); ?>
        
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="blue-grey darken-2">

    <main >
    <div class="container">
        <blockquote>Someone needs to start paying for services rendered to them</blockquote>
<?php

/* EXPERIMENTAL FILE, TO BE DELETED AT THE END OF PROJECT. ANY EXPERIMENTS AND TESTS CAN BE DONE HERE*/
    require_once("handlers/db_info.php");

/*DbInfo functions tested here*/

/*
    //Function list: these return mysqli::result on success, false on fail and null if query could not be prepare
    #ADMIN FUNCTIONS
    TeacherUsernameExists($admin_username)
    PrincipalUsernameExists($admin_username)
    SuperuserUsernameExists($admin_username)
    
    TeacherExists($admin_email)
    PrincipalExists($admin_email)
    SuperuserExists($admin_email)

    TeacherStaffIdExists($admin_staff_id)
    PrincipalStaffIdExists($admin_staff_id)
    SuperuserStaffIdExists($admin_staff_id)

    #STUDENT FUNCTIONS
    StudentIdExists($std_id)
    StudentUsernameExists($std_username)


*/

#SET THE VARIABLES TO TEST THE SEARCH
    #Admin
    $admin_username = "george_mathenge";
    $admin_email = "yo@frankie.com";
    $admin_staff_id = 20;
    
    #Student
    $std_id = 10;
    $std_username = "allan_jeremy";

?>

    <style type="text/css">
    /*For blockquotes being used here*/
        .positive-block
        {
            border-color:#00c853 !Important;
        }
        .negative-block
        {
            border-color:#c62828 !Important;
        }    
        .informative-block
        {
            border-color:#7b1fa2 !Important;
        }    
    </style>

        <h1 class="center blue-grey-text text-lighten-1">ADMIN FUNCTION TESTS</h1>
        <!-- CHECK ADMIN BY USERNAME -->
        <div class="row ">
            <h3 class="center blue-grey-text text-lighten-3">USERNAME RETRIEVAL TESTS</h3>
            <!-- Checking Teacher by Username -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Teacher by Username</h5>
                    <p class="center"><code>TeacherUsernameExists($admin_username)</code></p>
                    <div class="divider"></div>
                    <p><b>Username to check:</b><?php echo " ". $admin_username?> </p>
                    <?php 
                        if(DbInfo::TeacherUsernameExists($admin_username))
                        {
                            echo "<blockquote class='positive-block'> Found a teacher going by the username <code>$admin_username</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no teacher with the username <code>$admin_username</code></blockquote>";
                        }
                    ?>
                </div>
            </div>

            <!-- Checking Principal by Username -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Principal by Username</h5>
                    <p class="center"><code>PrincipalUsernameExists($admin_username)</code></p>
                    <div class="divider"></div>
                    <p><b>Username to check:</b><?php echo " ". $admin_username?> </p>
                    <?php 
                        if(DbInfo::PrincipalUsernameExists($admin_username))
                        {
                            echo "<blockquote class='positive-block'> Found a principal going by the username <code>$admin_username</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no principal with the username <code>$admin_username</code></blockquote>";
                        }
                    ?>
                </div>
            </div>
           
            <!-- Checking Superuser by Username -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Superuser by Username</h5>
                    <p class="center"><code>SuperuserUsernameExists($admin_username)</code></p>
                    <div class="divider"></div>
                    <p><b>Username to check:</b><?php echo " ". $admin_username?> </p>
                    <?php 
                        if(DbInfo::SuperuserUsernameExists($admin_username))
                        {
                            echo "<blockquote class='positive-block'> Found a superuser going by the username <code>$admin_username</code> Exists in superuser accounts</blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no superuser with the username <code>$admin_username</code></blockquote>";
                        }
                    ?>
                </div>
            </div>

            <div class="divider"></div>
        </div>
        
        <div class="divider blue-grey lighten-3"></div>
        
        <!-- CHECK ADMIN BY STAFF ID -->
        <div class="row ">
            <h3 class="center blue-grey-text text-lighten-3">STAFF ID RETRIEVAL TESTS</h3>

            <!-- Checking Teacher by Staff ID -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Teacher by Staff ID</h5>
                    <p class="center"><code>TeacherStaffIdExists($admin_staff_id)</code></p>
                    <div class="divider"></div>
                    <p><b>Staff ID to check:</b><?php echo " ". $admin_staff_id?> </p>
                    <?php 
                        if(DbInfo::TeacherStaffIdExists($admin_staff_id))
                        {
                            echo "<blockquote class='positive-block'> Found a teacher going by the staff id : <code>$admin_staff_id</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no teacher with the staff id : <code>$admin_staff_id</code></blockquote>";
                        }
                    ?>
                </div>
            </div>

            <!-- Checking Principal by Staff ID -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Principal by Staff ID</h5>
                    <p class="center"><code>PrincipalStaffIdExists($admin_staff_id)</code></p>
                    <div class="divider"></div>
                    <p><b>Staff ID to check:</b><?php echo " ". $admin_staff_id?> </p>
                    <?php 
                        if(DbInfo::PrincipalStaffIdExists($admin_staff_id))
                        {
                            echo "<blockquote class='positive-block'> Found a principal going by the staff id : <code>$admin_staff_id</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no principal with the staff id : <code>$admin_staff_id</code></blockquote>";
                        }
                    ?>
                </div>
            </div>
           
            <!-- Checking Superuser by Staff ID -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Superuser by Staff ID</h5>
                    <p class="center"><code>SuperuserStaffIdExists($admin_staff_id)</code></p>
                    <div class="divider"></div>
                    <p><b>Staff ID to check:</b><?php echo " ". $admin_staff_id?> </p>
                    <?php 
                        if(DbInfo::SuperuserStaffIdExists($admin_staff_id))
                        {
                            echo "<blockquote class='positive-block'> Found a superuser going by the staff id : <code>$admin_staff_id</code> Exists in superuser accounts</blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no superuser with the staff id : <code>$admin_staff_id</code></blockquote>";
                        }
                    ?>
                </div>
            </div>

        </div>
        
        <div class="divider blue-grey lighten-3"></div>
        
        <!-- CHECK ADMIN BY EMAIL ADDRESS -->
        <div class="row ">
            <h3 class="center blue-grey-text text-lighten-3">EMAIL ADDRESS RETRIEVAL TESTS</h3>

            <!-- Checking Teacher by Email -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Teacher by Email</h5>
                    <p class="center"><code>TeacherEmailExists($admin_email)</code></p>
                    <div class="divider"></div>
                    <p><b>Email to check:</b><?php echo " ". $admin_email?> </p>
                    <?php 
                        if(DbInfo::TeacherEmailExists($admin_email))
                        {
                            echo "<blockquote class='positive-block'> Found a teacher going by the email : <code>$admin_email</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no teacher with the email : <code>$admin_email</code></blockquote>";
                        }
                    ?>
                </div>
            </div>

            <!-- Checking Principal by Username -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Principal by Email</h5>
                    <p class="center"><code>PrincipalEmailExists($admin_email)</code></p>
                    <div class="divider"></div>
                    <p><b>Email to check:</b><?php echo " ". $admin_email?> </p>
                    <?php 
                        if(DbInfo::PrincipalEmailExists($admin_email))
                        {
                            echo "<blockquote class='positive-block'> Found a principal going by the email : <code>$admin_email</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no principal with the email : <code>$admin_email</code></blockquote>";
                        }
                    ?>
                </div>
            </div>
           
            <!-- Checking Superuser by Username -->
            <div class="col s12 m4">
                <div class="card-panel ">
                    <h5 class="center">Checking Superuser by Email</h5>
                    <p class="center"><code>SuperuserEmailExists($admin_email)</code></p>
                    <div class="divider"></div>
                    <p><b>Email to check:</b><?php echo " ". $admin_email?> </p>
                    <?php 
                        if(DbInfo::SuperuserEmailExists($admin_email))
                        {
                            echo "<blockquote class='positive-block'> Found a superuser going by the email : <code>$admin_email</code> Exists in superuser accounts</blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no superuser with the email : <code>$admin_email</code></blockquote>";
                        }
                    ?>
                </div>
            </div>

        </div>
        
        <div class="divider blue-grey lighten-3"></div>
        <h1 class="center blue-grey-text text-lighten-1">STUDENT FUNCTION TESTS</h1>
        
        <!-- CHECK STUDENT 
        StudentIdExists($std_id)
        StudentUsernameExists($std_username)
        -->
        <div class="row ">
            <!-- Checking Student by Email -->
            <div class="col s12 m6">
                <div class="card-panel ">
                    <h5 class="center">Checking Student by Student ID</h5>
                    <p class="center"><code>StudentIdExists($std_id)</code></p>
                    <div class="divider"></div>
                    <p><b>Student ID to check:</b><?php echo " ". $std_id?> </p>
                    <?php 
                        if(DbInfo::StudentIdExists($std_id))
                        {
                            echo "<blockquote class='positive-block'> Found a student going by the student id : <code>$std_id</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no student with the student id : <code>$std_id</code></blockquote>";
                        }
                    ?>
                </div>
            </div>

            <!-- Checking Principal by Username -->
            <div class="col s12 m6">
                <div class="card-panel ">
                    <h5 class="center">Checking Student by Username</h5>
                    <p class="center"><code>StudentUsernameExists($std_username)</code></p>
                    <div class="divider"></div>
                    <p><b>Username to check:</b><?php echo " ". $std_username?> </p>
                    <?php 
                        if(DbInfo::StudentUsernameExists($std_username))
                        {
                            echo "<blockquote class='positive-block'> Found a student going by the username : <code>$std_username</code></blockquote>";
                        }
                        else
                        {
                            echo "<blockquote class='negative-block'>There is no student with the username : <code>$std_username</code></blockquote>";
                        }
                    ?>
                </div>
            </div>
            
            <div class="divider blue-grey lighten-3"></div>
            
            <div class="row">
                <div class="col s12">
                <div class="card-panel  lime lighten-2">
                    <h4 class="center">NOTES</h4>
                    <blockquote class="informative-block">All the functions working within normal parameters. Feel free to copy paste them from here. Tested every single one of the functions stated using both correct and incorrect inputs</blockquote>
                </div>
                </div>
            </div>
        </div>

    </div>
    </main>
    <footer>
    </footer>

    <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
    <script type="text/javascript" src="js/materialize.js"></script>
    <script src="js/masonry.pkgd.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

    <script>
    $(document).ready(function() {
        $('select').material_select();

        //Ensure labels don't overlap text fields
        Materialize.updateTextFields();//doesn't work
    });
        
    function hideSideNav() {
        $(".mobile-button-collapse").sideNav('hide');
        
        //console.log('already open');
    }


    </script>
    </body>
</html>
