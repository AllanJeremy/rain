/*global $, jQuery, alert, console*/

var Dashboard = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('dashboard created');
        
        Lists_Templates = new Lists_Templates();
        Forms_Templates = new Forms_Templates();
        
        
        cleanOutModals();
        
        Events = new Events();
        Tests = new Tests();
        Result = new Result();
        
        //loadTestCard();
        
        
        //openEsomoModal("23", "Test head", {"modalBody":"test"});
        
    };
    
    //-----------
    
    var cleanOutModals = function () {
        
        console.log('cleaning out modals');
        
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