<!DOCTYPE html>

<html lang="en" >
    <head>
        <?php
        require_once("handlers/header_handler.php");
        require_once("handlers/db_info.php");
        require_once("handlers/date_handler.php"); #Date handler. Handles all date operations
        ?>
        <title><?php echo MyHeaderHandler::GetPageTitle();?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link rel="stylesheet" type="text/css" href="stylesheets/pace-theme-flash.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body class="grey lighten-5">
        <?php
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $ass_id = $_GET['id'];
            $assignment = DbInfo::AssignmentExists($ass_id);
            if (!$assignment) {
                //if false
                //show all assignments so that the students can rechoose the assignment
                include_once('snippets/student_assignments.php');
            } else {
                $assignment['teacher_name'] = DbInfo::GetTeacherById($assignment['teacher_id']);
                include_once('snippets/student_do_assignment.php');

                var_dump($assignment);
            }
        } else {
            //show all assignments so that the students can rechoose the assignment
            include_once('snippets/student_assignments.php');
        }
        ?>
    </body>
</html>
