<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Esomo2</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link  rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        
    </head>

    <body>
        <header>
            <nav class="top-nav">
                <div class="container ">
                    <div class="nav-wrapper ">
                        <div class="row no-margin">
                            <div class="col s2">
                                <a href="index.php" class="">
                                    <i class="material-icons">arrow_back</i>
                                </a>
                            </div>
                            <div class="col s8">
                                <a class="page-title center-align">Test title</a>
                            </div>
                            <div class="col s2" id="fullScreenDiv">
                                <a class="right" id="fullScreenToggle" href="#!FullScreenTestPage"><i class="material-icons">fullscreen</i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <?php
            
            //Account type - from session variable storing the account type of the currently logged in user
            $accType = "student";
            
            ?>

        </header>
        <main>
            <?php
            ?>
            <div class="row grey darken-2 z-depth-1">
                <div class="container">
                    <div class="row no-margin">
                        <div class="col s4 center-align">
                            <p class="white-text">Question <span class="php-data">4</span> of 30</p>
                        </div>
                        <div class="col s4 center-align">
                            <p class="white-text"><span class="php-data">1</span> question skipped</p>
                        </div>
                        <div class="col s4 center-align">
                            <p class="white-text">Time left: <span class="php-data">1:04:32</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-container">
                <h4 class="col s12 grey-text thin text-darken-4 question-number">Question 4</h4>
                <br>
                <h5 class="question light black-text">
                    Tables are a nice way to organize a lot of data. We provide a few utility classes to help you style your table as easily as possible. In addition, to improve mobile experience, all tables on mobile-screen widths are centered automatically. Which color?
                </h5>
                <br>
                <div class="col s12">
                    <form action="#" class="row">
                        <p>
                            <input name="group1" type="radio" id="test1" />
                            <label for="test1">Red</label>
                        </p>
                        <p>
                            <input name="group1" type="radio" id="test2" />
                            <label for="test2">Yellow</label>
                        </p>
                        <p>
                            <input class="" name="group1" type="radio" id="test3"  />
                            <label for="test3">Green</label>
                        </p>
                        
                        <div class="col s6 input-field">
                            <a class="btn right" type="submit">Next</a>
                        </div>
                        <div class="col s6 input-field">
                            <a class="btn-flat btn-skip-question right" type="submit">skip</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <footer>
        </footer>
        
        <script type="text/javascript" src="js/jquery-2.0.0.js"></script>
        <script type="text/javascript" src="js/tests-functions.js"></script>
        
        <script type="text/javascript" src="js/materialize.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        
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