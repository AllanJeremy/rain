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
                                <a class="page-title center-align">RECEIVED ASSIGNMENTS</a>
                            </div>
                            <div class="col s2">
                                <a class="right-align" href="#!searchBar">
                                    <i class="material-icons">search</i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <?php
            
            //Account type - from session variable storing the account type of the currently logged in user
            $accType = "student";
            
            include_once($accType."_navigation.php");
            ?>

        </header>
        <main>
            <br>
            <?php
            include_once($accType.'_tabs.php');
            ?>
        </main>
        <footer>
        </footer>
        
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="js/materialize.js"></script>
        <script src="js/masonry.pkgd.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
        <script>
        $(document).ready(function() {
            /*
            $(".mobile-button-collapse").click(function() {
                $('.side-nav').animate({
                    left: '0'
                }, '400');
            });
            */
        });
            
            function hideSideNav() {
                $(".mobile-button-collapse").sideNav('hide');
                /*
                $('.side-nav').animate({
                    left: '-300'
                }, '400');
                $('header').animate({
                    paddingLeft: '0'
                }, '400');
                $('main').animate({
                    paddingLeft: '0'
                }, '400');
                $('footer').animate({
                    paddingLeft: '0'
                }, '400');
                 */
                console.log('already open');
            }


        </script>
    </body>
</html>