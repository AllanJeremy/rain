<!DOCTYPE html>

<html lang="en" >
    <head>
        <title>Esomo2</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                                            $pageTitle = 'Received assignments';
                                            $searchBar = '';
                                            
                                            echo $pageTitle;
                                            
                                            break;
                                        case "teacher":
                                            $pageTitle = 'Create an assignment';
                                            $searchBar = '';
                                            
                                            echo $pageTitle;
                                            
                                            break;
                                        case "principal":
                                            $pageTitle = 'Stats overview';
                                            $searchBar = '';
                                            
                                            echo $pageTitle;
                                            
                                            break;
                                        case "superuser":
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
        </main>
        <footer>
        </footer>
        
        <?php
            else:#redirect user to the login page
                header('Location:'.$redirectPath);
            endif;#end the main if statement
        ?>
        
        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/materialize.js"></script>
        <script src="js/masonry.pkgd.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
        <script>
        $(document).ready(function() {
            $('select').material_select();
        });
            
            function hideSideNav() {
                $(".mobile-button-collapse").sideNav('hide');
                
                //console.log('already open');
            }


        </script>
    </body>
</html>