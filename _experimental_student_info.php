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
<body>
<main>
<div class="container">

<!--Student Classroom tests-->
<h1 class="center">CLASSROOM TESTS</h1>
<?php
    require_once("handlers/db_info.php");
    require_once("handlers/error_handler.php");

    $student_acc_id = 20;
    echo "<p>Function : <code class='center-text'>DbInfo::GetAllStudentClassrooms($student_acc_id)</code></p>";
    if($std_classrooms = DbInfo::GetAllStudentClassrooms($student_acc_id))
    {
        ErrorHandler::PrintSuccess("Found student with id ".$student_acc_id." in classroom(s)");
        echo "<h4>Classrooms the student belongs to</h4><ul>";
        foreach($std_classrooms as $std_classroom)
        {
           echo "<li>".$std_classroom["class_name"]."</li>";
        }
        echo "</ul><br><hr><br>";
    }
    else
    {
        ErrorHandler::PrintError("Could not find student with id ".$student_acc_id." in classrooms");
    }

?>

<!--Student Assignent tests-->
<h1 class="center">ASSIGNMENT TESTS</h1>
<?php
    require_once("handlers/db_info.php");
    require_once("handlers/error_handler.php");

    $student_acc_id = 20;
    echo "<p>Function : <code class='center-text'>DbInfo::GetAllStudentAssignments($student_acc_id)</code></p>";
    if($std_assignments = DbInfo::GetAllStudentAssignments($student_acc_id))
    {
        ErrorHandler::PrintSuccess("Found student with id ".$student_acc_id." in classroom(s)");
        echo "<h4>Assignments received by this student</h4><ul>";
        
        foreach($std_assignments as $std_assignment)
        {
           echo "<br><li>".$std_assignment["ass_title"]." in the class_id : ".$std_assignment["class_id"]."</li>";
        }
        echo "</ul><br><hr><br>";
    }
    else
    {
        ErrorHandler::PrintError("Could not find assignments for the student with student_id ".$student_acc_id);
    }

?>
</div>
</main>
<body>

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