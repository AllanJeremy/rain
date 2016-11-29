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
<?php
    require_once("handlers/session_handler.php");
    require_once("handlers/db_info.php");
    $classrooms = DbInfo::GetSpecificTeacherClassrooms($_SESSION["admin_acc_id"]);
    $reversed_classrooms = DbInfo::ReverseResult($classrooms);#an array that has the reversed values of the array, newest is the first

    $class_id=0;
    $class_name="";
    $student_ids="";
    foreach($reversed_classrooms as $classroom):    
        $class_id = $classroom['class_id'];
        $class_name = $classroom['class_name'];
        $student_ids = $classroom['student_ids'];
        
?>
    <h4>Class name : <?php echo $class_name ?></h4>
    <h5><b>Class ID : </b><?php echo $class_id ?></h5>

    <?php if($student_ids!=0): 
        $student_ids = DbInfo::GetArrayFromList($student_ids);
        echo "Number of student accounts found : ". count($student_ids) . "<br>";
        foreach($student_ids as $std_id):
        $student = DbInfo::StudentIdExists($std_id);
    ?>
    <p><b>Student ID :</b><?php echo $std_id ?><br><b>Student Name:</b>
    <?php
        echo $student["full_name"];
    ?>
    </p>
    <?php
     endforeach;
     else :
     ?>
    <p> No students in this classroom</p>
    <?php endif;?>
    <br><hr>

<?php
    endforeach;
    
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