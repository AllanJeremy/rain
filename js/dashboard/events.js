/*global $, jQuery, alert, console*/

var Events = function () {
    'use strict';
    //--------------
    var userInfo, $this = this;


    $this.__construct = function (userInfo) {
        console.log('global events created');
        Modals_Events = new Modals_Events();
        
        //global inits
        Modals_Events.cleanOutModals();
        Modals_Events.closeModalsEvent();
    };

/*
    $this.__construct_Student = function (userInfo) {

    };
*/

    $this.__construct_Admin = function (userInfo, section) {

        switch(section) {
            case 'resources':
                ResourcesEvents = new ResourcesEvents();
                break;

            case 'schedules':
                ScheduleEvents = new ScheduleEvents(userInfo);
                CommentsEvents = new CommentsEvents(userInfo);
                break;

            case 'classrooms':
                ClassroomEvents = new ClassroomEvents();
                CommentsEvents = new CommentsEvents(userInfo);
                break;

            case 'create-assignment':
            case 'sent-assignments':
            case 'assignment-submissions':
                AssignmentEvents = new AssignmentEvents(userInfo);
                CommentsEvents = new CommentsEvents(userInfo);
                break;

            case 'create-test':
            case 'take-test':
            case 'view-test-results':
            case 'account':
                CommentsEvents = new CommentsEvents(userInfo);
                break;
        }

    };

    $this.__construct_Student = function (userInfo, section) {

        switch(section) {
            case 'resources':
                ResourcesEvents = new ResourcesEvents();
                break;

            case 'schedules':
                ScheduleEvents = new ScheduleEvents(userInfo);
                CommentsEvents = new CommentsEvents(userInfo);
                break;

            case 'classrooms':
                ClassroomEvents = new ClassroomEvents();
                CommentsEvents = new CommentsEvents(userInfo);
                break;

            case 'received-assignments':
            case 'sent-assignments':
                AssignmentEvents = new AssignmentEvents(userInfo);
                CommentsEvents = new CommentsEvents(userInfo);
                break;

            case 'take-test':
            case 'view-test-results':
                Tests = new Tests();
                CommentsEvents = new CommentsEvents(userInfo);

            case 'account':
                CommentsEvents = new CommentsEvents(userInfo);
                break;
        }

//        ResourcesEvents = new ClassroomEvents();
//        AssignmentEvents = new AssignmentEvents(userInfo);
//        ScheduleEvents = new ScheduleEvents(userInfo);
        console.log('student ready');

    };

    var getUserInfo = function () {

        var $req =  $.ajax({
            url: 'handlers/session_handler.php',
            data: {'action': 'GetLoggedUserInfo'},
            type: 'GET',
            processData: true
        }, 'json');

        return $req;

    },

        ajaxInit = function () {

        var section = window.location.search.split('=').pop();

            $.when(getUserInfo()).then(function (_1, _2, _3) {
    /*
                console.log(_1);
                console.log(_2);
                console.log(_3.responseText);
    */

                userInfo = jQuery.parseJSON(_1);

                if (userInfo.account_type !== 'student') {

                    console.log('Admin account. Construct admin events for the page.');
                    $this.__construct(userInfo, section);
                    $this.__construct_Admin(userInfo, section);
                } else {

                    console.log('Student account. Construct student events for the page.');
                    $this.__construct(userInfo, section);
                    $this.__construct_Student(userInfo, section);

                    return;
                }

            });

    };

    ajaxInit();
    
};
