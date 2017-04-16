<!DOCTYPE html>

<html lang="en" >
    <head>
        <?php require_once("handlers/header_handler.php");?>

        <title><?php echo MyHeaderHandler::GetPageTitle();?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="stylesheet" type="text/css" href="stylesheets/compiled-materialize.css"/>
        <link rel="stylesheet" type="text/css" href="stylesheets/pace-theme-flash.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body class="grey lighten-5">
        <header data-assignment-id="">
            <div class="brookhurst-theme-primary lighten-1">
                <div class="container ">
                    <br>
                    <div class="row no-bottom-margin">
                        <h5 class="col s10 php-data white-text">Title: X,Y Compression formulea</h5>
                        <p class="col s12 php-data white-text margin-vert-8">From Tr. Jennifer</p>
                        <h5 class="col s12 php-data white-text">Description</h5>
                        <p class="col s11 php-data white-text">Try take the coordinate of the following images, then construct a formulae of curing cancer using HIV/AIDS and vice versa<br>
                        Come pick a few condoms for the assignment at 4:40pm</p>
                    </div>
                    <br>
                </div>
                <div class="assignment-action-header marg-16">
                    <a class="btn btn-large js-assignment-submit" href="">Submit</a>
                    <p class="right-align js-assignment-due action-text-color">Due tommorow</p>
                </div>
            </div>
            <div class="brookhurst-theme-primary lighten-2 row pin-nav-top">

                <div class="col s12">
                    <a class="white-text btn-flat btn marg-6">back</a>
                    <ul class="tabs inline tabs-transparent">
                        <li class="tab col s6"><a class="active" href="#myAssignment">My assignment</a></li>
                        <li class="tab col s6"><a href="#resources">resources</a></li>
                    </ul>

                    <a class="white-text btn-flat btn right marg-6">download</a>
                    <a class="white-text btn-flat btn right marg-6">upload</a>
                </div>
            </div>

        </header>
        <main>
            <div id="myAssignment" class="row container">
                <div class="m12 s12 col l8 assignment-tinymce">
                    <div class="brookhurst-theme-primary lighten-3 row= tinymce-toolbar inline-toolbar" id="mytoolbar">
                    </div>
                    <div name="body" id="body" class="z-depth-1-half pad-16 inline-editor row tinymce-document">
                    </div>
                </div>
                <div class="m12 col l3 offset-l1 hide-on-med-and-down">
                    <div class="row resources-bar marg-16">
                        <p class="grey-text">Resources</p>
                        <div class="divider"></div>
                        <div class="resource-bar">
                            <ul>
                                <li class="margin-vert-8">
                                    <a href="" class="black-text">This type of resources</a>
                                    <p class="no-margin grey-text text-lighten-1 secondary-title">PDF.</p>
                                    <p class="no-margin grey-text text-lighten-1 secondary-title">23kb</p>
                                </li>
                                <li class="divider"></li>
                                <li class="margin-vert-8">
                                    <a href="" class="black-text">This type of resources</a>
                                    <p class="no-margin grey-text text-lighten-1 secondary-title">PDF.</p>
                                    <p class="no-margin grey-text text-lighten-1 secondary-title">23kb</p>
                                </li>
                                <li class="divider"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div id="resources" class="row container">
                <div class="col s12">
                    <div class="resources-bar"></div>
                    <ul>
                        <li class="margin-vert-8">
                            <a href="" class="black-text">This type of resources</a>
                            <p class="no-margin grey-text text-lighten-1 secondary-title">PDF.</p>
                            <p class="no-margin grey-text text-lighten-1 secondary-title">23kb</p>
                        </li>
                        <li class="divider"></li>
                        <li class="margin-vert-8">
                            <a href="" class="black-text">This type of resources</a>
                            <p class="no-margin grey-text text-lighten-1 secondary-title">PDF.</p>
                            <p class="no-margin grey-text text-lighten-1 secondary-title">23kb</p>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </div>
            </div>
        </main>

        <script src="js/jquery-2.0.0.js"></script>
        <script src="js/materialize.js"></script>
        <script src="js/dashboard/events.js"></script>

        <!--DON'T MESS WITH-->
        <script src="tinymce/jquery.tinymce.min.js"></script>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinymce.init({
                selector: '#body',
                theme: 'modern',
                inline: true,
                skin: 'lightgray',
                //event_root: '#root',
                fixed_toolbar_container: '#mytoolbar',
                width: 300,
                height: 300,
                auto_focus: true,
                subfolder:"",
                toolbar1: 'forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
                toolbar2: 'undo redo | link unlink anchor image | fullscreen',
                forced_root_block: 0,
                relative_urls: false,
                plugins: [
                    "advlist autolink autosave textpattern link image lists charmap preview hr anchor pagebreak",
                    "table contextmenu directionality paste textcolor"
                ],
                image_advtab: false,
                toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect forecolor backcolor | link unlink anchor | image media"
            });
            var $target = $('.pin-nav-top'),
                $target2 = $('.inline-toolbar'),
                $target3 = $('.resources-bar');

            $target.pushpin({
                top: $target.offset().top,
                bottom: $('#myAssignment').outerHeight(),
                offset: 0
            });
            $target2.pushpin({
                top: $target.offset().top + $target.outerHeight(),
                bottom: $('#myAssignment').outerHeight(),
                offset: $target.outerHeight()
            });
            $target3.pushpin({
                top: $target.offset().top + $target.outerHeight(),
                bottom: $('#myAssignment').outerHeight(),
                offset: $target.outerHeight() + 160
            });
        </script>
        <!--DON'T EVEN THINK ABOUT IT -->
    </body>
    <footer>
    </footer>
</html>
