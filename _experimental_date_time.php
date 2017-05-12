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

    $yesterday = EsomoDate::GetYesterday();
    $seven_days_ago = EsomoDate::Get7DaysAgo();
    $this_month = EsomoDate::GetThisMonth();
    $last_month = EsomoDate::GetLastMonth();
    $last30days = EsomoDate::Get30DaysAgo();
    

    echo "<p>Yesterday date : ".$yesterday."</p>";
    echo "<p>7 days ago date : ".$seven_days_ago."</p>";
    echo "<p>This month started : ".$this_month['start']." and has reached ".$this_month['end']."</p>";
    echo "<p>Last month date started : ".$last_month['start']." and ended on ".$last_month['end']."</p>";
    echo "<p>30 days ago date : ".$last30days."</p>";
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