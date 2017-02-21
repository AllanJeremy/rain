/*global $, jQuery, alert, console*/

var Dashboard = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('dashboard created');
        
        setActiveSection();

        Lists_Templates = new Lists_Templates();
        Forms_Templates = new Forms_Templates();
        
        
        cleanOutModals();
        
        Result = new Result();
        Events = new Events();
        Tests = new Tests();
        
        //loadTestCard();
        
    };
    
    //-----------
    
    var setActiveSection = function () {

        console.log('setting active section');

        console.log(location.hash);

        $('main .main-tab').addClass('hide');

        $.get('handlers/session_handler.php', {"action": 'GetLoggedUserInfo'}, function(user) {
            console.log(user);

            localStorage.setItem("currentUserType", user.account_type);

            //make the current #? bar active on page load

            var currTag = location.hash,//current hash on init
                userType = localStorage.getItem("currentUserType"),
                pageTitle,
                activates = $('.side-nav a:not(.collapsible-header)' + currTag).attr('data-activates');//get the 'data-activates' attr of the current hash

            console.log(userType);

            if(!currTag || currTag === '#!name' || currTag === '#!') {//if currTag is undefined or has '#!name' or '#!' value, make it #classroom

                $('main .main-tab').addClass('hide');

                switch (userType) {
                    case 'student' :
                        currTag = '#recievedAssignments';

                        activates = $('.side-nav a:not(.collapsible-header)' + currTag).attr('data-activates');//get the 'data-activates' attr of the current hash
                        break;
                    case 'teacher' :

                        currTag = '#classroom';
                        activates = $('.side-nav a:not(.collapsible-header)' + currTag).attr('data-activates');//get the 'data-activates' attr of the current hash
                        break;
                    case 'principal' :
                        currTag = '#statsOverview';
                        activates = $('.side-nav a:not(.collapsible-header)' + currTag).attr('data-activates');//get the 'data-activates' attr of the current hash
                        break;
                    case 'superuser' :
                        currTag = '#dashboard';
                        activates = $('.side-nav a:not(.collapsible-header)' + currTag).attr('data-activates');//get the 'data-activates' attr of the current hash
                        break;

                }

                location.hash = currTag;

                //hide all tabs in <main> then remove 'hide' class on the active bar
                $('main .main-tab').addClass('hide');
                $('main .main-tab').removeClass('active-bar');

                $('main #' + activates).addClass('active-bar');
                $('main #' + activates).addClass('new-class');

                $('main .active-bar').removeClass('hide');

                $('.side-nav a:not(.collapsible-header)').parent().removeClass('active');//clean out any active class

                $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').addClass('active');// make active the tag that's similar to the location hash
                $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').parent().parent('.collapsible-body').parent().addClass('active');// make active the collapsible header of the tag that's similar to the location hash
                $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').parent().parent().parent().parent('.collapsible').find('.collapsible-header').addClass('active');// make active the collapsible header of the tag that's similar to the location hash
                $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').parent().parent('.collapsible-body').css('display','block');// Open the collapsible body

                pageTitle = $('.side-nav a:not(.collapsible-header)' + currTag).text();

                $('a#pageTitle').text(pageTitle);

            } else if(!$('main #' + activates).hasClass('active-bar')) {

                $('main .main-tab').removeClass('active-bar');
                $('main #' + activates).addClass('active-bar');
                $('main #' + activates).removeClass('hide');
                $('main #' + activates).addClass('new-class');
                $('main .main-tab:not(.active-bar)').addClass('hide');

                pageTitle = $('.side-nav a:not(.collapsible-header)' + currTag).text();

                $('a#pageTitle').text(pageTitle);
            }

            if(!$('.side-nav a:not(.collapsible-header)' + currTag).parent('li').hasClass('active')) {
                console.log('haina. SET.');

                $('.side-nav').find('li.active').find('.collapsible-body').hide();

                $('.side-nav').find('li.active').removeClass('active');
                $('.side-nav').find('a.collapsible-header').removeClass('active');

                $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').addClass('active');
                $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').addClass('active');


                if($('.side-nav a:not(.collapsible-header)' + currTag).parent('li').parent().parent().hasClass('collapsible-body')) {

                    console.log('IS part of accordion');

                    $('.side-nav a:not(.collapsible-header)' + currTag).parent().parent().parent().parent().addClass('active');
                    $('.side-nav a:not(.collapsible-header)' + currTag).parent().parent().parent().parent().find('a.collapsible-header').addClass('active');

                }

                $('.side-nav').find('li.active').find('.collapsible-body').show();

            } else {
                console.log($('.side-nav a:not(.collapsible-header)' + currTag).parent('li'));
            }

            $(document).on('click', '.side-nav a', function (e) {

                //console.log('tag id is -> ' + $(this).attr('id'));

                var tag = $(this).attr('id');

                if(tag) {

                    location.hash = tag;//add the id to the url as hash '#'

                }

            });


            //on click none-collapsible-headers side-nav panel actions
            $('.side-nav a.collapsible-header').click(function () {
                //$('.side-nav a:not(.collapsible-header)').parent().removeClass('active');
                console.log('Materialize css collapsible on.');
            });

                    /********** ON CLICK EVENT **********/

            //on click none-collapsible-headers side-nav panel actions
            $('.side-nav a:not(.collapsible-header)').click(function (e) {

                //console.log($(this).attr('data-activates'));

                var activates = $(this).attr('data-activates');

                //if it's not the name in the side nav that is clicked, switch tabs
                if ($(this).attr('id') != 'name' && $(this).attr('id') != undefined) {

                    e.preventDefault();

                    $('.side-nav a:not(.collapsible-header)').parent().removeClass('active');
                    if ($(this).parent().parent().parent().hasClass('collapsible-body') === true) {
                        //console.log('is a collapsible list');

                    } else {
                        //console.log('not a collapsible list');

                        $('.side-nav a.collapsible-header').parent().removeClass('active');
                    }
                    $(this).parent().addClass('active');

                    $('main .active-bar').addClass('hide');

                    $('main .main-tab').removeClass('active-bar');

                    $('main #' + activates).addClass('active-bar');

                    $('main .active-bar').removeClass('hide');

                    console.log('bar-clicked');
                    console.log('setting header text');

                    var pageTitle = $(this).text();

                    $('a#pageTitle').text(pageTitle);

                } else {

                    console.log('name clicked');
                    console.log('undefined---doing nothing');

                }

            });


        }, 'json');

        /**************
        TABS SWITCH FUNCTIONALITIES END
        ************/

    };

    //-----------

    var cleanOutModals = function () {
        
        console.log('cleaning out dashboard dialogs');
        
        $('a#createClassroom').attr('data-target', '');
            
        $('.modal').remove();
         
    };
    
    //-----------   CLASSROOMS
    
    var editClassroomCard = function () {
        
    };
    
    //---------
    
    var loadClassroomCard = function (obj) {
        
        //load classroom card template
        var classroomTab = $('#classroomTab #classroomCardList');
        
        var result = Lists_Templates.classRoomCard(obj);
        
        classroomTab.prepend(result);
    };
    
    //-----------
    
    var archiveClassroomCard = function () {
        //archive a classroom card, with its info
        
    };
    
    //-----------   ASSIGNMENTS
    
    var loadSentAssingmentCard = function () {
        //load sent assignment card template
    };
    
    //-----------
    
    var submitAssingmentMarks = function () {
        //load loadAssingmentMarks
    };
    
    //-----------
    
    var loadAssingmentMarks = function () {
        //load assignment marks card template
    };
    
    //-----------
    
    var loadSentAssingmentComment = function () {
        // load assignment comment template
    };
    
    //-----------
    
    var submitAssingment = function () {
        //call loadSubmittedAssingment
        // load new submitted assignment notification [for the teachers]
    };
    
    //-----------
    
    var loadSubmittedAssingment = function () {
        //load submitted assignment card template
    };
    
    //-----------   SCHEDULES
    
    var loadScheduleCard = function () {
        //load schedule template
    };
    
    //-----------
    
    var markSchedule = function () {
        //call loadMarkedSchedule
    };
    
    //-----------
    
    var loadMarkedSchedule = function () {
        //load schedule template
        
    };
    
    //-----------   TESTS
    
    var loadTestCard = function () {
        var testTemplateVars = {
            testid: '3',
            testtitle: 'js title trial',
            testsubject: 'js subject',
            testtotalquestions: '32',
            testtime: '2hrs 40min',
            testdifficulty: 'Average',
            testpassmark: '89%',
            testtotalstudentstaken: '18',
            testlink: 'test.php/#342'
        };
        console.log(
            Lists_Templates.testCard(testTemplateVars)
        );
    };
  
    //-----------   COMMENTS
    
    var submitComment = function () {
        //call loadComment()
        
    };
    
    //-----------
    
    var loadComment = function () {
        //load comment list template
    };
    
    //-----------
    
    var markCommentSeen = function () {
        
    };
    
    //------------  STUDENTS
    
    var loadStudentList = function (type, stream_id, subject_id) {

        var action = 'getAllStudents';
        var i = "233";
        
        //action = json_Parse(action);
        
        //ajax $.get
        $.get('handlers/db_info.php', {"action": "getAllStudents"}, function (result) {
            console.log('get results:- ');
            console.log(result);
            var u = 0
            if (type === 'form') {

                console.log('Making Form Type of student list total->');
                
                if (typeof result === 'object') {
                    console.log('object. looping through');
                    
                    var output = '';
                    
                    for (var key in result) {
                        
                        output += Forms_Templates.makeStudentFormList(result);
                        
                        output += '';
                        
                        console.log(result[key]);
                    }
                    
                    return output;
                    
                }

                //Forms_Templates.makeStudentFormList(optionslist);

            } else if (type === 'list') {

                console.log('Making List Type of student list');

                
                var output = '';
                
                for (var key in result) {

                    output += Lists_Templates.makeStudentList(result);

                    output += '';

                    console.log(result[key]);
                }
                
                return output;
                
            } else {

                console.log('Type of student list Not set');

            }

        }, 'json');

    };
    
    //------------  MODALS
    
    var openEsomoModal = function (modal_id, modal_header, modal_body) {
        
        console.log('esomo modal created');
        
        
        var defaults = {
            modalId: modal_id,
            modalActionType: {
                actionClose: 'close-hivi',
                actionAdd: 'add-hivi'
            },
            templateHeader: modal_header,
            templateBody: modal_body
            
        };
        
        var esomoModal = Lists_Templates.esomoModalTemplate(defaults);
        
        $('main').append(esomoModal);
        
        $('#esomoModal' + defaults.modalId).openModal();
        
    };
    
    //-------------
    
    this.__construct();
    
};
