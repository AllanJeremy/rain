<!DOCTYPE html>

<html lang="en" >
    <head>
        <?php require_once("handlers/header_handler.php");?>

        <title><?php echo MyHeaderHandler::GetPageTitle();?></title>

        <!--Site metadata-->
        <?php MyHeaderHandler::GetMetaData(); ?>
        
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/materialize.js"></script>
        <script type="text/javascript" src="js/masonry.pkgd.min.js"></script>
        <script type="text/javascript" src="js/dashboard/result.js"></script>
        <script type="text/javascript" src="js/dashboard/lists_templates.js"></script>
        <script type="text/javascript" src="js/dashboard/forms_templates.js"></script>
        <script type="text/javascript" src="js/dashboard/events.js"></script>
        <script type="text/javascript" src="js/dashboard/tests.js"></script>
        <script type="text/javascript" src="js/dashboard.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
    </head>

    <body class="side-nav-page">

        <?php 
            require_once("handlers/session_handler.php");
            
            #If user is not logged in, they will be redirected to this file
            $redirectPath = "login.php";

            //If statement that determines whether content can be viewed
            if (MySessionHandler::AdminIsLoggedIn() || MySessionHandler::StudentIsLoggedIn()):
               
                //Account type - from session variable storing the account type of the currently logged in user
                $snippet_folder = "snippets/";#folder that contains snippets

                $accType="";
                //Determine what type of account is logged in and set accType to the appropriate value
                if(MySessionHandler::AdminIsLoggedIn())
                {
                    $accType = $_SESSION["admin_account_type"];#corresponds with file name prefix as well as the database name of the account type


                }
                else if(MySessionHandler::StudentIsLoggedIn())
                {
                    $accType = "student";#corresponds with file name prefix
                }

                //Check to see if the logout action has been triggered

                
                if(isset($_GET["action"]))
                {
                    $actionVariable = htmlspecialchars($_GET["action"]);#sanitized action variable to get the GET action variable
                    switch($actionVariable)
                    {
                        case "student_logout":
                            MySessionHandler::StudentLogout();#logout
                            unset($_GET["action"]);#unset the action GET variable if it hasn't been automagically unset
                        break;

                        case "admin_logout":#admin logs out
                            MySessionHandler::AdminLogout();
                            unset($_GET["action"]);#unset the action GET variable if it hasn't been automagically unset
                        break;

                        default:#invalid entry, anything we hadn't planned for
                            unset($_GET["action"]);#unset the action GET variable if it hasn't been automagically unset

                    }
                }
        ?>

        <header>
            <nav class="top-nav">
                <div class="container ">
                    <div class="nav-wrapper ">
                        <div class="row no-margin">
                            <div class="col s2">
                                <a href="#" data-activates="slide-out" class="mobile-button-collapse full hide-on-large-only">
                                    <i class="material-icons">reorder</i>
                                </a>
                            </div>
                            <div class="col s8">
                                <a class="page-title center-align" id="pageTitle">
                                    
                                    
                                    <?php
                                    
                                    
                                    //Setting the active page title according to the account type
                                    //Hiding the search icon according to the account type
                                    switch ($accType) {
                                            
                                        case "student":
                                            require_once("classes/student.php");
                                            
                                            $student = new Student();#object to access student functions

                                            $pageTitle = 'Received assignments';
                                            $searchBar = '';
                                            
                                            echo $pageTitle;
                                            
                                            break;
                                        case "teacher":
                                            require_once("classes/teacher.php");

                                            $teacher = new Teacher();#object to access teacher functions

                                            $pageTitle = 'Classrooms';
                                            $searchBar = '';
                                            
                                            echo $pageTitle;
                                            
                                            break;
                                        case "principal":
                                            require_once("classes/principal.php");
                                            
                                            $principal = new Principal();#object to access principal functions

                                            $pageTitle = 'Stats overview';
                                            $searchBar = '';
                                            
                                            echo $pageTitle;
                                            
                                            break;
                                        case "superuser":
                                            require_once("classes/superuser.php");
                                            require_once("classes/principal.php");
                                            require_once("classes/teacher.php");
                                            require_once("classes/student.php");

                                            $teacher = new Teacher();
                                            $teacher->CreateTeacher();
                                            
                                            $principal = new Principal();
                                            $principal->CreatePrincipal();#use this if the create corresponding account option is NOT checked - DEFAULT
                                            
                                            //$principal->CreatePrincipalTeacherAccount();#use this if the create corresponding account option is checked

                                            $superuser = new Superuser();
                                            $superuser->CreateSuperuser();

                                            $student = new Student();
                                            $student->CreateStudentAccount();

                                            $pageTitle = 'Dashboard';
                                            $searchBar = 'hide';
                                            
                                            echo $pageTitle;
                                            
                                            break;
                                        default:
                                            $pageTitle = 'Dashboard';
                                            $searchBar = '';
                                            
                                            echo $pageTitle;
                                    }
                                    
                                    ?>
                                </a>
                            </div>
                            <div class="col s2 <?php echo $searchBar; ?>">
                                <a class="right-align" href="#!searchBar">
                                    <i class="material-icons">search</i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <?php
                # show the side navigation for respective account types
                include_once($snippet_folder . $accType."_navigation.php");
            ?>

        </header>
        <main>
            <br>
            <?php
                #show respective tabs for the respective account type
                include_once($snippet_folder . $accType.'_tabs.php');
            ?>
                <!-- Modal Structure -->
                <!-- Will be transferred to esomo2-templates.js -->
                <div id="modal1" class="modal">
                    <div class="modal-content">
                    <h4>Modal Header</h4>
                    <p>A bunch of text</p>
                    </div>
                    <div class="modal-footer">
                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
                    </div>
                </div>
        </main>
        <footer>
        </footer>
        
        <?php
            else:#redirect user to the login page
                header('Location:'.$redirectPath);
            endif;#end the main if statement
        ?>
        
        
        <script>
        $(document).ready(function() {
            $('select').material_select();

            //Ensure labels don't overlap text fields
            Materialize.updateTextFields();//doesn't work
        });
            
        function hideSideNav() {
            $(".mobile-button-collapse").sideNav('hide');
            $('.tooltipped').tooltip({delay: 50});
            $('.modal-trigger').leanModal({dismissible : false});//a workaround the lean-overlay click event
            //console.log('already open');
        }

        
        </script>
    </body>
</html>