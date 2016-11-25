/*global $, jQuery, alert, console*/

var Dashboard = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('dashboard created');
        
        Lists_Templates = new Lists_Templates();
        Forms_Templates = new Forms_Templates();
        
        Events = new Events();
        Tests = new Tests();
        Result = new Result();
        
        //loadTestCard();
        
        cleanOutModals();
        openEsomoModal();
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
        
    }
    
    //-----------
    
    var loadComment = function () {
        //load comment list template
    }
    
    //-----------
    
    var markCommentSeen = function () {
        
    };
    
    //------------  STUDENTS
    
    var loadStudentList = function(type, stream_id, subject_id) {
        
        //ajax $.get
        var data = {
            studentusername: "Test 1 username",
            studentid: "001",
            studentrank: "25"
        }
        
        var list = {
            1: {
                username: data.studentusername,
                studentid: data.studentid,
                studentrank: data.studentrank
            },
            2: {
                username: data.studentusername,
                studentid: data.studentid,
                studentrank: data.studentrank
            },
            3: {
                username: data.studentusername,
                studentid: data.studentid,
                studentrank: data.studentrank
            },
            4: {
                username: data.studentusername,
                studentid: data.studentid,
                studentrank: data.studentrank
            },
            5: {
                username: data.studentusername,
                studentid: data.studentid,
                studentrank: data.studentrank
            }
        }
        if(type === 'form') {
            
            console.log('Type of student list');
            
        } else if(type === 'list') {
            
            console.log('Type of student list');
            
        } else {
            
            console.log('Type of student list');
            
        }
    }
    
    //------------  MODALS
    
    var openEsomoModal = function (obj) {
        console.log('esomo modal created');
        
        var type = 'form';//----either form or list
        
        var modal_id = obj.modal_id;
        
        var stream_id = obj.stream_id;
        
        var subject_id = obj.subject_id;
        
        var modal_header = obj.modal_header;
        
        var createClassStudentList = loadStudentList(type, stream_id, subject_id);
        
        var defaults = {
            modalId: modal_id,
            modalActionType: {
                actionClose: 'close-hivi',
                actionAdd: 'add-hivi'
            },
            templateHeader: modal_header,
            templateBody: CreateClassStudentList;
            
        };
        
        var esomoModal = Lists_Templates.esomoModalTemplate(defaults);
        
        $('main').append(esomoModal);
        
        $('#' + defaults)
    };
    
    //-------------
    
    this.__construct();
    
};