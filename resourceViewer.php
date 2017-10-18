<!DOCTYPE html>

<html lang="en">
    <head>
        <?php
            require_once(realpath(dirname(__FILE__) ."/handlers/header_handler.php"));
            require_once(realpath(dirname(__FILE__) . "/classes/test.php"));
            require_once(realpath(dirname(__FILE__) . "/handlers/date_handler.php"));
        ?>
        <title><?php echo MyHeaderHandler::SITE_TITLE;?> | Tests</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        
    </head>

    <body>
        <?php
            //If statement that determines whether content can be viewed
            if (!(MySessionHandler::AdminIsLoggedIn() || MySessionHandler::StudentIsLoggedIn()))
            {
                echo "<p>You need to be logged in to access this content</p>";
                echo "</body>";
                exit();
            }

            //Account type - from session variable storing the account type of the currently logged in user
            $snippet_folder = "snippets/";#folder that contains snippets
            $rid = htmlspecialchars(@$_GET["rid"]);
            $resource = DbInfo::ResourceExists(htmlspecialchars($rid));
            
            if(!isset($_GET["rid"]) || (!$resource))
            {
                require_once("handlers/error_handler.php");

                #redirect to 404 later
                echo "<br><div class='container'>";
                ErrorHandler::MsgBoxInfo("The resource you were looking for could not be found");
                echo "</div>";
                exit();
            }

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

            //Store the logged in user's information
            $user_info = MySessionHandler::GetLoggedUserInfo();
            
?>
        <header>
            <nav class="top-nav">
                <div class="container ">
                    <div class="nav-wrapper ">
                        <div class="row no-margin">
                            <div class="col s2">
                                <a class="tooltipped" data-position="right" data-delay="50" data-tooltip="Back to RAIN E-Learning" href=".?section=resources">
                                    <i class="material-icons">arrow_back</i>
                                </a>
                            </div>
                            <div class="col s8">
                                <a class="page-title center-align"><?php echo $resource["resource_name"];?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!--Jquery-->
            <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        </header>

        <main>
            <iframe src="<?php echo $resource['file_link'];?>" width="100%" style="height:90vh;"
            ></iframe>
        </main>
        <?php
            /*Include the footer at the bottom of the page*/
            include_once("./snippets/site_footer.php");
        ?>
        <script type="text/javascript" src="js/materialize.js"></script>

        <!--Fullscreen functionality-->
        <script>
        $(document).ready(function() {
            
            var fullscreenButton = $('a#fullScreenToggle');
                
            fullscreenButton.click(function (e) {
                e.preventDefault();
                toggleFullScreen();
            });
        });

        </script>
    </body>
</html>
