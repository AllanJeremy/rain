<?php 
    require_once(realpath(dirname(__FILE__) . "/handlers/db_info.php"));
    
    $result_id = &$_GET['id'];
    $result = DbInfo::GetTestResult($result_id);
    
    $result = $result ? $result->fetch_array() : FALSE;
    $test = $result ?  DbInfo::TestExists($result['test_id']) : NULL;
    $user_info = MySessionHandler::GetLoggedUserInfo();#store the logged in user info anytime 
    
    //If it is the teacher who created the test
    $is_test_owner = ($test['teacher_id'] == $user_info['user_id']) && ($user_info['account_type'] == 'teacher');
    $is_student_taker = TRUE;//TODO: Change this to db value
    $taker = DbInfo::GetStudentByAccId($result['taker_id']);
    $taker_name = $taker ? $taker['first_name'].' '.$taker['last_name'] : 'Unknown';

    $back_link = './?section=take-test';
?>
<!DOCTYPE html>

<html lang="en" >
    <head>
        <?php require_once("handlers/header_handler.php");?>

        <title><?php echo 'Test results for '.$taker_name;?></title>

        <!--Site metadata-->
        <?php MyHeaderHandler::GetMetaData(); ?>
        
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link  rel="stylesheet" type="text/css" href="stylesheets/pace-theme-flash.css"/>
    </head>
    <body>
<?php
    //If it is the test owner or 
    if($test && ($is_test_owner || $is_student_taker)):
        $test_title = &$test['test_title'];
        $grade =  &$result['grade'];
        $max_grade =  &$test['max_grade'];
        $pass_mark =  &$test['passing_grade'];
        $verdict = ($grade >= $pass_mark) ? 'PASS' : 'FAIL';
        
        //Print the results here
?>

    <div class="container center">
        <h5><?php echo strtoupper($test_title).' RESULTS';?></h5>
        <br>
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <table class="table bordered striped">
                    <tr>
                        <th class="center" colspan="2">TEST RESULTS FOR <?php echo strtoupper($taker_name);?></th>
                    </tr>
                    <tr>
                        <th>Test Name</th>
                        <td><?php echo $test_title;?></td>
                    </tr>
                    <tr>
                        <th>Grade</th>
                        <td><?php echo $grade.'/'.$max_grade;?></td>
                    </tr>
                    <tr>
                        <th>Pass mark</th>
                        <td><?php echo $pass_mark;?></td>
                    </tr>
                    <tr>
                        <th>Percentage</th>
                        <td><?php echo round(($grade/$max_grade * 100),2).'%'; ?></td>
                    </tr>
                    <tr>
                        <th>Verdict</th>
                        <td><?php echo $verdict;?> </td>
                    </tr>
                    <!-- <tr>
                        <th>Position</th>
                        <td>1/20</td>
                    </tr> -->
                </table>
                <br>
                <p>
                    <a href="<?php echo $back_link;?>" class="btn-flat left-align">BACK TO TESTS</a>
                    <a href="#!" class="btn">PRINT RESULTS</a>
                </p>
            </div>
        </div>

    </div>

<?php else: ?>
<div class="container center">
    <div class="card-panel ">
        <p class="center">No test results found</p>
        <a href="<?php echo $back_link;?>" class="btn">BACK TO TESTS</a>
    </div>
</div>

<?php endif; ?>
    </body>
</html>