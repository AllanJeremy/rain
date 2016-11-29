/*global $, jQuery, alert, console*/

var Lists_Templates = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('Lists templates created');
        
    };
    
    //--------------------------------------
    
    this.classRoomCard = function (obj) {
    
        var templateOutput = '';
        
        templateOutput += '<div class="col card-col new-class" data-classroom-id="' + obj.classroomid + '"><div class="card cyan darken-4">';
        templateOutput += '<div class="card-content white-text">';
        templateOutput += '<span class="card-title">' + obj.classroomtitle + '</span>';
        templateOutput += '<p>Number of students: ';
        templateOutput += '<span class="php-data">' + obj.totalstudents;
        templateOutput += ' <a onclick="openStudentClassList(' + obj.classroomid + ')" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="I am tooltip" href="#" >';
        templateOutput += '<i class="material-icons">info</i>';
        templateOutput += '</a>';
        templateOutput += '</span>';
        templateOutput += '</p>';
        templateOutput += '<p>Assignments sent: ';
        templateOutput += '<span class="php-data">' + obj.assignmentnumbers;
        templateOutput += ' <a onclick="openAssignmentClassList(' + obj.classroomid + ')" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="I am tooltip" href="#" >';
        templateOutput += '<i class="material-icons">info</i>';
        templateOutput += '</a>';
        templateOutput += '</span>';
        templateOutput += '</p>';
        templateOutput += '<p>Subject: <span class="php-data">' + obj.classroomsubject + '</span></p>';
        templateOutput += '<p>Stream:  <span class="php-data">' + obj.classroomstream + '</span></p>';
        templateOutput += '</div>';
        templateOutput += '<div class="card-action">';
        templateOutput += '<a href="#" data-target="modal1" class="modal-trigger" id="editClassroom">Edit</a>';
        templateOutput += '<a href="#"  class="right">View</a>';
        templateOutput += '</div>';
        templateOutput += '</div></div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.assignmentCard = function (obj) {
        
        var templateOutput = '';
        templateOutput += '<div class="col card-col" data-assignment-id="' + obj.assignmentid + '"><div class="card white">';
        //if there is a warning or a notification sent, print it out
        switch (obj.assignmentwarning) {

        case '0':
            //do nothing
            break;
        case '1':
            //warning
            templateOutput += '<div class="assignment-warning red lighten-1 z-depth-2"><p class="white-text">' + obj.assignmentwarningtext + '</p></div>';
            break;
        case '2':
            //info
            //e.g like the assignment was closed/cancelled
            templateOutput += '<div class="assignment-info grey darken-3 z-depth-2"><p class="grey-text text-lighten-3">' + obj.assignmentwarningtext + '</p></div>';
            break;
        default:
            //do nothing
            templateOutput += '';
            break;
        }
        
        templateOutput += '<div class="card-content">';
        templateOutput += '<span class="card-title">' + obj.assignmenttitle + '</span>';
        templateOutput += '<ul class="collapsible " data-collapsible="accordion"><li><div class="collapsible-header">Instructions<i class="material-icons right">arrow_drop_down</i></div><div class="collapsible-body">';
        templateOutput += '<p>' + obj.assignmentinstructions + '</p>';
        templateOutput += '</div></li></ul>';
        templateOutput += '<p>From: <span class="php-data">' + obj.assignmentauthor + '</span></p>';
        templateOutput += '<p>Subject: <span class="php-data">' + obj.assignmentsubject + '</span></p>';
        templateOutput += '<p>Date sent:  <span class="php-data">' + obj.datesent + '</span></p>';
        templateOutput += '<p>Due date:  <span class="php-data">' + obj.duedate + '</span></p>';
        templateOutput += '<p>Resources:  <span class="php-data">';
        //loop through the resources links
        
        templateOutput +=  obj.duedate + '</span></p>';
        templateOutput += '</div>';
        templateOutput += '<div class="card-action right-align">';
        templateOutput += '<a href="#" class="" onclick="submitAssignment(' + obj.classroomid + ')">Submit</a>';
        templateOutput += '</div>';
        templateOutput += '</div></div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.markedAssignmentCard = function (obj) {
        obj.assignmentwarning = '1';
        var templateOutput = '';
        templateOutput += '<div class="col card-col" data-assignment-id="' + obj.assignmentid + '"><div class="card white">';
        //if there is a warning or a notification sent, print it out
        switch (obj.assignmentwarning) {

        case '0':
            //do nothing
            break;
        case '1':
            //info
            //e.g like the assignment was closed/cancelled
            templateOutput += '<div class="assignment-info right-align"><a href="#" class="deep-orange-text text-accent-3" onclick="getAssignmentComment(' + obj.assignmentid + ')"><i class="material-icons">message</i> ' + obj.assignmentUnreadCommentsNumber + '</a></div>';
            break;
        default:
            //do nothing
            templateOutput += '';
            break;
        }
        
        templateOutput += '<div class="card-content">';
        templateOutput += '<span class="card-title">' + obj.assignmenttitle + '</span>';
        templateOutput += '<ul class="collapsible " data-collapsible="accordion"><li><div class="collapsible-header">Instructions<i class="material-icons right">arrow_drop_down</i></div><div class="collapsible-body">';
        templateOutput += '<p>' + obj.assignmentinstructions + '</p>';
        templateOutput += '</div></li></ul>';
        templateOutput += '<p>From: <span class="php-data">' + obj.assignmentauthor + '</span></p>';
        templateOutput += '<p>Subject: <span class="php-data">' + obj.assignmentsubject + '</span></p>';
        templateOutput += '<p>Date sent:  <span class="php-data">' + obj.datesent + '</span></p>';
        templateOutput += '<p>Due date:  <span class="php-data">' + obj.duedate + '</span></p>';
        templateOutput += '<p>Resources:  <span class="php-data">';
        //loop through the resources links
        
        templateOutput +=  obj.duedate + '</span></p>';
        templateOutput += '</div>';
        templateOutput += '<div class="card-action center-align brookhurst-theme-primary assignment-results">';
        templateOutput += '<p class="white-text">Grade given: <span class="php-data">' + obj.assignmentgradegiven + '</span></p>';
        templateOutput += '</div>';
        templateOutput += '</div></div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.testCard = function (obj) {
        obj.testwarning = '1';
        var templateOutput = '';
        templateOutput += '<div class="col card-col" data-test-id="' + obj.testid + '"><div class="card blue-grey darken-1">';
        //if there is a notification sent, print it out
        /*
        switch (obj.testwarning) {

        case '0':
            //do nothing
            break;
        case '1':
            //info
            //e.g like the assignment was closed/cancelled
            templateOutput += '<div class="test-info right-align"></div>';
            break;
        default:
            //do nothing
            templateOutput += '';
            break;
        }
        */
        templateOutput += '<div class="card-content white-text">';
        templateOutput += '<span class="card-title">' + obj.testtitle + '</span>';
        templateOutput += '<p>Subject: <span class="php-data">' + obj.testsubject + '</span></p>';
        templateOutput += '<p>Questions:  <span class="php-data">' + obj.testtotalquestions + '</span></p>';
        templateOutput += '<p>Time:  <span class="php-data">' + obj.testtime + '</span></p>';
        templateOutput += '<p>Difficulty:  <span class="php-data">' + obj.testdifficulty + '</span></p>';
        templateOutput += '<p>Pass mark:  <span class="php-data">' + obj.testpassmark + '</span></p>';
        templateOutput += '<p class="students-taken php-data"><i>' + obj.testtotalstudentstaken + ' students in your class have taken this test</i></p></span></p>';
        templateOutput += '</div>';
        templateOutput += '<div class="card-action center-align brookhurst-theme-primary assignment-results">';
        templateOutput += '<a href="' + obj.testlink + '">Take test</a>';
        templateOutput += '</div>';
        templateOutput += '</div></div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.modalTemplate = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<div id="' + obj.modalId + '" class="modal">';
        templateOutput += '<div class="modal-content">';
        templateOutput += '<h4>' + obj.templateHeader + '</h4>';
        templateOutput += obj.templateBody;
        templateOutput += '</div>';
        templateOutput += '<div class="modal-footer">';
        templateOutput += '<a href="#!" id="modalFooterCloseAction" class=" modal-action modal-close waves-effect waves-green btn-flat">close</a>';
        templateOutput += '</div>';
        templateOutput += '</div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.esomoModalTemplate = function (obj) {
        
        obj.progressWidth = '78%';
        
        var formattedProgressWidth = $.trim(obj.progressWidth).split("%").join("");
        
        var warningClass = 'red';
        var okayClass = 'green';
        var infoClass = 'amber';
        var noneClass = 'grey';
        
        if(formattedProgressWidth == 0) {
        
            var colorClass = noneClass;
            var textColorClass = noneClass + '-text text-lighten-1';
        
        } else if(formattedProgressWidth >= 1 && formattedProgressWidth <= 31) {
            
            var colorClass = okayClass + ' lighten-2';
            var textColorClass = okayClass + '-text text-darken-2';
        
        } else if(formattedProgressWidth >= 31 && formattedProgressWidth <= 80) {
            
            var colorClass = okayClass + ' lighten-2';
            var textColorClass = okayClass + '-text text-darken-2';
        
        } else if(formattedProgressWidth >= 81 && formattedProgressWidth <= 92) {
            
            var colorClass = infoClass;
            var textColorClass = infoClass + '-text';
        
        } else if(formattedProgressWidth >= 93 && formattedProgressWidth <= 100) {
            
            var colorClass = warningClass;
            var textColorClass = warningClass + '-text';
        
        } else {
            
            console.log('formattedProgressWidth: ');
            console.log(formattedProgressWidth);
            
        }
        
        var templateOutput = '';
        
        templateOutput += '<div id="esomoModal' + obj.modalId + '" class="esomo-modal modal modal-fixed-footer">';
        templateOutput += '<div class="modal-content">';
        templateOutput += '<h4>' + obj.templateHeader + '</h4>';
        templateOutput += obj.templateBody;
        templateOutput += '</div>';
        templateOutput += '<div class="modal-footer row">';
        templateOutput += '<div class="col s12 m6"><div class="progress modal-progress"><div class="determinate ' + colorClass + '" style="width:' + obj.progressWidth + ';"><span class=" ' + textColorClass + ' ">' + obj.progressWidth + '</span></div></div></div>';
        templateOutput += '<div class="col m3 s6"><a href="#!" id="modalFooterCloseAction" class="right modal-action modal-close waves-effect waves-red red-text btn-flat">close</a></div>';
        templateOutput += '<div class="col m3 s6"><a href="#!" id="modalFooterActionAdd" class="right modal-action modal-close waves-effect waves-green btn">Add students</a></div>';
        templateOutput += '</div>';
        templateOutput += '</div>';
        templateOutput += '</div>';
        
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.updateModalProgressTemplate = function (str) {
        
        var obj = {"updateWidth": str};
        
        var esomoModalTemplate = this.esomoModalTemplate(obj);
        
        //console.log(esomoModalTemplate);
            
        return esomoModalTemplate;
            
        
    }
    
    //--------------------------
    
    this.makeStudentList = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<li>' + obj.name + '</li>';
        
    };
    
    //-------------------------
    
    this.__construct();
    
};