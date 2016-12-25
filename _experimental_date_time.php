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
<h2 class="center">DATE TIME</h2>
<?php
    require_once("handlers/session_handler.php");
    require_once("handlers/db_info.php");
    require_once("handlers/date_handler.php");
    $due_days = 1;
    $due_hours = 1;
    $due_minutes = 0;

    $due_info = EsomoDate::GetDueText(array("days"=>$due_days,"hours"=>$due_hours,"minutes"=>$due_minutes));
    echo " <h3>".$due_info["due_text"]."</h3>";
    echo "<h6> Due days = $due_days</h6>";
    echo "<h6> Due hours = $due_hours</h6>";
    echo "<h6> Due minutes = $due_minutes</h6>";
    
    echo "<br><h3>Date formatting</h3>";
    $test = EsomoDate::GetOptimalDateTime("2016-12-14 15:47:23");
    echo $test["date"]." ";
    echo $test["time"];

    echo "<br><h3>Date difference</h3>";

    $date_diff=EsomoDate::GetDateDiff("2016-12-14 15:47:23","2016-12-25 15:10:07");
    $date_info = EsomoDate::GetDateInfo($date_diff);
    echo "Year difference | ".$date_info["years"]."<br>";
    echo "Month difference | ".$date_info["months"]."<br>";
    echo "Day difference | ".$date_info["days"]."<br>";
    echo "Hours difference | ".$date_info["hours"]."<br>";
    echo "Minutes difference | ".$date_info["minutes"]."<br>";
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