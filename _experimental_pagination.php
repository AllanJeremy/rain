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
    <br>
    <br>
<table class="bordered responsive-table" id="pendingScheduleTable">
    <thead class="">
        <tr>
            <th data-field="name">Schedule title</th>
            <th data-field="description">schedule description</th>
            <th data-field="due" class="right-align">Due date</th>
            <th data-field="action" class="hide">Action</th>
        </tr>
    </thead>

    <tbody>
        <?php

        $teacher_acc_id = $_SESSION['admin_acc_id'];

        $teacherSchedules = DBInfo::GetSpecificTeacherSchedules($teacher_acc_id);
//                                var_dump($teacherSchedules=>num_rows);

        $i = 0;

        foreach($teacherSchedules as $pendingschedules) {

            if($pendingschedules['attended_schedule'] === 0) {

                $pendingSchedulesData[$i] = $pendingschedules;
            }

            $i++;
        }

        $listdata = $pendingSchedulesData;
        $paginationtype = 'table';
        $numberperrows = 10;
        $active = true;

        paginate($listdata, $paginationtype, $numberperrows, $active);

        function paginate($listdata,$paginationtype, $numberperrows, $active) {

            //var_dump($listdata);
            $returnData['data'] = '';

            if(count($listdata) > 10) {

                $numberOfTbody = ceil((count($listdata) / $numberperrows));

                //echo $numberOfTbody;

                $returnData['tbodynumber'] = $numberOfTbody;
                $tbodyNumber = 0;

                while (list($key, $val) = each($listdata)) {

                    if($paginationtype == 'table') {

                        if (($key - 1) % 10 == 0 || $key == 0) {

                            if(($key - 1) != 0) {

                                if ($tbodyNumber > 0 ) {

                                    echo '<tbody class="hide" data-tbody-number="'.$tbodyNumber.'">';

                                } else {

                                    echo '<tbody data-tbody-number="'.$tbodyNumber.'">';

                                }

                            }

                        }

                        echo '<tr data-schedule-id="'.$val['schedule_id'].'">';
                        echo '<td>'.$val['schedule_title'].'</td>';
                        echo '<td>'.$val['schedule_description'].'</td>';
                        echo '<td class="right-align" >'.$val['due_date'].'</td>';
                        echo '<td class="right-align schedule-action" width="120">';
                        echo '<a class="btn-icon" id="attendedSchedule" href="#!"><i class="material-icons">done</i></a>';
                        echo '<a class="btn-icon" id="openSchedule" href="#!"><i class="material-icons">expand_more</i></a>';
                        echo '</td>';
                        echo '</tr>';

                        if ($key % 10 == 0 && $key != 0) {

                            $tbodyNumber++;

                            echo '</tbody>';

                        }

                    }
                }
            }

        }

        ?>
    </tbody>
</table>

<ul class="pagination center">
    <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
    <li class="active"><a href="#!">1</a></li>
    <li class="waves-effect"><a href="#!">2</a></li>
    <li class="waves-effect"><a href="#!">3</a></li>
    <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
</ul>
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
