/*global $, jQuery, alert, console*/

var Events = function () {
    'use strict';
    //--------------
    var userInfo, $this = this;


    $this.__construct = function (userInfo) {
        console.log('global events created');
        Lists_Templates = new Lists_Templates();

        CommentsEvents = new CommentsEvents(userInfo);
        AssignmentEvents = new AssignmentEvents(userInfo);
        ResourcesEvents = new ResourcesEvents(userInfo);
    };

    $this.__construct_Student = function (userInfo) {

        StudentAssignmentEvents = new StudentAssignmentEvents(userInfo);
        
    };

    $this.__construct_Admin = function (userInfo) {

        Modals_Events = new Modals_Events();
        Forms_Templates = new Forms_Templates();
        ClassroomEvents = new ClassroomEvents();
        ScheduleEvents = new ScheduleEvents(userInfo);

        //global inits
        Modals_Events.cleanOutModals();
        Modals_Events.closeModalsEvent();
    };

    var getUserInfo = function () {

        var $req =  $.ajax({
            url: 'handlers/session_handler.php',
            data: {'action':'GetLoggedUserInfo'},
            type: 'GET',
            processData: true
        }, 'json');

        return $req;

    };

    var ajaxInit = function () {

        $.when(getUserInfo()).then(function (_1,_2,_3) {
/*
            console.log(_1);
            console.log(_2);
            console.log(_3.responseText);
*/

            userInfo = jQuery.parseJSON(_1);

            if(userInfo.account_type !== 'student') {

                console.log('Admin account. Construct admin events for the page.');
                $this.__construct(userInfo);
                $this.__construct_Admin(userInfo);
            } else {

                console.log('Student account. Construct admin events for the page.');
                $this.__construct(userInfo);
//                $this.__construct_Student(userInfo);

                return;
            }

        });

    };

var domInit = function () {

        // the idea is that maybe some data-atribute could tell if the current
        // user is a student or admin
    };

    ajaxInit();
    // domInit();
};
