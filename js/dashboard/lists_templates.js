/*global $, jQuery, alert, console*/

var Lists_Templates = function () {
    'use strict';
    //--------------
    
    this.construct = function () {
        console.log('Lists templates created');
        
    };
    
    //--------------------------------------
    
    this.classRoomCard = function (obj) {
    
        var templateOutput = '';
        
        templateOutput += '<div class="col card-col new-class" data-classroom-id="' + obj.classroomid + '">';
        templateOutput += this.classRoomCardData(obj);
        templateOutput += '</div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.classRoomCardData = function (obj) {
    
        var templateOutput = '';
        
        templateOutput += '<div class="card ' + obj.classes + '">';
        templateOutput += '<div class="card-content white-text">';
        templateOutput += '<span class="card-title">' + obj.classroomtitle + '</span>';
        templateOutput += '<p>Number of students: ';
        templateOutput += '<span class="php-data">' + obj.totalstudents;
        templateOutput += ' <a id="openStudentsClassList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of students in this classroom" href="#!" >';
        templateOutput += '<i class="material-icons">info</i>';
        templateOutput += '</a>';
        templateOutput += '</span>';
        templateOutput += '</p>';
        templateOutput += '<p>Assignments sent: ';
        templateOutput += '<span class="php-data">' + obj.assignmentnumbers;
        templateOutput += ' <a id="openAssignmentsClassList" class="orange-text text-accent-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Number of assignments sent to this classroom" href="#!" >';
        templateOutput += '<i class="material-icons">info</i>';
        templateOutput += '</a>';
        templateOutput += '</span>';
        templateOutput += '</p>';
        templateOutput += '<p>Subject: <span class="php-data">' + obj.classroomsubjectname + '</span></p>';
        templateOutput += '<p>Stream:  <span class="php-data">' + obj.classroomstreamname + '</span></p>';
        templateOutput += '</div>';
        templateOutput += '<div class="card-action">';
        templateOutput += '<a href="#" data-target="modal1" class="modal-trigger" id="editClassroom">Edit</a>';
        templateOutput += '<a href="#"  class="right">View</a>';
        templateOutput += '</div>';
        
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
        templateOutput += '<a href="#" class="">Submit</a>';
        templateOutput += '</div>';
        templateOutput += '</div></div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.teacherAssignmentCard = function (obj) {
        
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
        templateOutput += '<div class="card-action">';
        templateOutput += '<p class="">Submitted assignments: <a href="#!" >' + obj.totalsubmitted + '</a></p>';
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
        
        templateOutput += '<div id="' + obj.modalId + '" class="modal modal-fixed-footer">';
        templateOutput += '<div class="modal-content">';
        templateOutput += '<h4>' + obj.templateHeader + '</h4>';
        templateOutput += obj.templateBody;
        templateOutput += '</div>';
        templateOutput += '<div class="modal-footer">';
        
        if (typeof obj.extraActions === 'undefined') {
          
            templateOutput += '';
            
        } else {
        
            templateOutput += obj.extraActions;
            
        }
        
        templateOutput += '<a href="#!" id="modalFooterCloseAction" class=" modal-action modal-close waves-effect waves-green btn-flat">close</a>';
        templateOutput += '</div>';
        templateOutput += '</div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.listModalTemplate = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<div id="' + obj.modalId + '" class="modal modal-fixed-footer">';
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
    
    this.classroomCardListContainer = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<div class="row"id="classroomCardList">';
        templateOutput += '</div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.assignmentCardListContainer = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<div class="row"id="assignmentCardList">';
        templateOutput += '</div>';
        
        return templateOutput;
    };
    
    //--------------------------------------
    
    this.esomoModalTemplate = function (obj) {
        
        obj.progressWidth = '78%';
        
        var formattedProgressWidth = $.trim(obj.progressWidth).split("%").join(""),
            warningClass = 'red',
            okayClass = 'green',
            infoClass = 'amber',
            noneClass = 'grey',
            colorClass = noneClass,
            textColorClass = noneClass + '-text',
            templateOutput = '';
        
        if (formattedProgressWidth === 0) {
        
            colorClass = noneClass;
            textColorClass = noneClass + '-text text-lighten-1';
        
        } else if (formattedProgressWidth >= 1 && formattedProgressWidth <= 31) {
            
            colorClass = okayClass + ' lighten-2';
            textColorClass = okayClass + '-text text-darken-2';
        
        } else if (formattedProgressWidth >= 31 && formattedProgressWidth <= 80) {
            
            colorClass = okayClass + ' lighten-2';
            textColorClass = okayClass + '-text text-darken-2';
        
        } else if (formattedProgressWidth >= 81 && formattedProgressWidth <= 92) {
            
            colorClass = infoClass;
            textColorClass = infoClass + '-text';
        
        } else if (formattedProgressWidth >= 93 && formattedProgressWidth <= 100) {
            
            colorClass = warningClass;
            textColorClass = warningClass + '-text';
        
        } else {
            
            console.log('formattedProgressWidth: ');
            console.log(formattedProgressWidth);
            
        }
        
        
        templateOutput += '<div id="esomoModal' + obj.modalId + '" class="esomo-modal modal modal-fixed-footer">';
        templateOutput += '<div class="modal-content">';
        templateOutput += '<h4>' + obj.templateHeader + '</h4>';
        templateOutput += obj.templateBody;
        templateOutput += '</div>';
        templateOutput += '<div class="modal-footer row">';
        templateOutput += '<div class="col s12 m6"><div class="progress modal-progress"><div class="determinate ' + colorClass + '" style="width:' + obj.progressWidth + ';"><span class=" ' + textColorClass + ' ">' + obj.progressWidth + '</span></div></div></div>';
        templateOutput += '<div class="col m3 s6"><a href="#!" id="modalFooterCloseAction" class="right modal-action modal-close waves-effect waves-red red-text btn-flat">close</a></div>';
        templateOutput += '<div class="col m3 s6"><a href="#!" id="modalFooterActionAdd" class="right modal-action modal-close waves-effect waves-green btn">' + obj.modal_action + '</a></div>';
        templateOutput += '</div>';
        templateOutput += '</div>';
        templateOutput += '</div>';
        
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.updateModalProgressTemplate = function (str) {
        
        var obj = {"updateWidth": str},
        
            esomoModalTemplate = this.esomoModalTemplate(obj);
        
        //console.log(esomoModalTemplate);
            
        return esomoModalTemplate;
            
        
    };
    
    //--------------------------
    
    this.studentList = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<li>' + obj.name + '</li>';
            
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.studentTable = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<table>';
        templateOutput += '<thead><tr>';
        //templateOutput += '<th data-field="action"></th>';
        templateOutput += '<th data-field="price">Admission no.</th>';
        templateOutput += '<th data-field="name">Full name</th>';
        templateOutput += '</tr></thead>';
        templateOutput += '<tbody>';
        templateOutput += obj.listData;
        templateOutput += '</tbody>';
        templateOutput += '</table>';
            
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.studentTableList = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<tr>';
        templateOutput += '<td>' + obj.id + '</td>';
        templateOutput += '<td>' + obj.name + '</td>';
        templateOutput += '</tr>';
            
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.assignmentTable = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<table class="responsive-table">';
        templateOutput += '<thead><tr>';
        //templateOutput += '<th data-field="action"></th>';
        templateOutput += '<th data-field="name">Assignment name</th>';
        templateOutput += '<th data-field="price">Subject</th>';
        templateOutput += '<th data-field="price">Date sent</th>';
        templateOutput += '<th data-field="price">Attachments</th>';
        templateOutput += '<th data-field="price">Pass grade</th>';
        templateOutput += '</tr></thead>';
        templateOutput += '<tbody>';
        templateOutput += obj.listData;
        templateOutput += '</tbody>';
        templateOutput += '</table>';
            
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.assignmentTableList = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<tr>';
        templateOutput += '<td>' + obj.name + '</td>';
        templateOutput += '<td>' + obj.subject + '</td>';
        templateOutput += '<td>' + obj.datesent + '</td>';
        templateOutput += '<td>' + obj.totalattachments + '</td>';
        templateOutput += '<td>' + obj.passgrade + '</td>';
        templateOutput += '</tr>';
            
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.classroomTable = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<table class="responsive-table">';
        templateOutput += '<thead><tr>';
        templateOutput += '<th ></th>';
        templateOutput += '<th data-field="name">Classroom name</th>';
        templateOutput += '<th data-field="subject">Subject</th>';
        templateOutput += '<th data-field="stream">Stream</th>';
        templateOutput += '<th data-field="students">No. of Students</th>';
        templateOutput += '</tr></thead>';
        templateOutput += '<tbody class="list">';
        templateOutput += obj.listData;
        templateOutput += '</tbody>';
        templateOutput += '</table>';
            
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.classroomTableList = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<tr>';
        templateOutput += '<td><p class="no-margin"><input type="checkbox" value="' + obj.id + '" class="filled-in" id="' + obj.id + '"><label for="' + obj.id + '"></label></p></td>';
        templateOutput += '<td>' + obj.name + '</td>';
        templateOutput += '<td>' + obj.subject + '</td>';
        templateOutput += '<td>' + obj.stream + '</td>';
        templateOutput += '<td>' + obj.totalstudents + '</td>';
        templateOutput += '</tr>';
            
        return templateOutput;
        
    };
    
    //--------------------------------------
    
    this.scheduleList = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<tr class="new-class" data-schedule-id="' + obj.scheduleid + '">';
        templateOutput += this.scheduleListData(obj);
        templateOutput += '</tr>';
        
        return templateOutput;
        
    };
    
    //--------------------------------------
    
    this.scheduleListData = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<td>' + obj.schedulename + '</td>';
        templateOutput += '<td>' + obj.scheduledescription + '</td>';
        templateOutput += '<td class="right-align">' + obj.scheduledatetime + '</td>';
        templateOutput += '<td class="right-align schedule-action" width="120">';
        templateOutput += '<a class="btn-icon" id="attendedSchedule" href="#!"><i class="material-icons">done</i></a>';
        templateOutput += '<a class="btn-icon' + ((obj.scheduletype === 'done') ? 'hide': '') + '" id="openSchedule">';
        templateOutput += '<i class="material-icons">expand_more</i></a>';
        templateOutput += '</td>';
        
        return templateOutput;
        
    };
    
    //--------------------------------------
    
    this.scheduleTable = function (obj) {
        
        obj.includethead = 'hide';
        
        var templateOutput = '';
        
        templateOutput += '<table class="bordered-light responsive-table" id="pendingScheduleTable">';
        templateOutput += '<thead class="' + obj.includethead + '">';
        templateOutput += '<tr>';
        templateOutput += '<th data-field="id" class="center-align">' + obj.tableidcolumnname + '</th>';
        templateOutput += '<th data-field="id" class="center-align">' + obj.tableschedulenamecolumnname + '</th>';
        templateOutput += '<th data-field="id" class="center-align">' + obj.tablescheduledescriptioncolumnname + '</th>';
        templateOutput += '<th data-field="id" class="center-align">' + obj.tablescheduletimecolumnname + '</th>';
        templateOutput += '<th data-field="id" class="center-align">' + obj.tablescheduleextraactionscolumn + '</th>';
        templateOutput += '</tr>';
        templateOutput += '</thead>';
        templateOutput += '<tbody>';
        templateOutput += obj.tableLists;
        templateOutput += '</tbody>';
        templateOutput += '</table>';
        
        return templateOutput;
        
    };
    
    //--------------------------------------
    
    this.paginationTemplate = function (obj, j) {
        
        obj.position = 'center';
        
        var templateOutput = '',
            c;
        
        templateOutput += '<ul class="pagination ' + obj.position + '">';
        
        for (c = 1; c <= obj.pages; c++) {
            
            if (j === c && j === 1) {
                //if the first page is active
                templateOutput += '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
                templateOutput += '<li class="active"><a href="#!">' + obj.c + '</a></li>';
            
            } else if (c === 1 && j !== 1) {
                //if the first page is not active
                templateOutput += '<li ><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
                templateOutput += '<li class="waves-effect"><a href="#!">' + obj.c + '</a></li>';
                
            } else if (j === c) {
                //The current active page
                templateOutput += '<li class="active"><a href="#!">' + obj.c + '</a></li>';
                
            } else if (j === obj.pages && c === j) {
                //if the last page is active
                templateOutput += '<li class="active"><a href="#!">' + obj.c + '</a></li>';
                templateOutput += '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
            
            } else if (c === obj.pages && j !== obj.pages) {
                //if the last page is not active
                templateOutput += '<li class="waves-effect"><a href="#!">' + obj.c + '</a></li>';
                templateOutput += '<li ><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
                
            } else {
                //Page not active
                templateOutput += '<li class="waves-effect"><a href="#!">' + obj.c + '</a></li>';
            }
            
        }
        
        templateOutput += '</ul>';
        templateOutput += '<br>';
        
        return templateOutput;
        
    };
    
    //--------------------------------------
    
    this.objective = function (obj) {
    
        var templateOutput = '';
        
        templateOutput += '<li>';
        templateOutput += obj.text;
        templateOutput += ( (obj.isSubtopic === false) ? '' : '<span class="tiny-info">ST</span>');
        templateOutput += ( (obj.removable === false) ? '' : '<span class="right "><a class="mini-link btn-icon no-padding" href="#!">remove</a></span>');
        templateOutput += '</li>';

        return templateOutput;
        
    };
    
    //--------------------------------------
    
    this.editExtraFooterActions = function (obj) {
        
        var templateOutput = '',
            classes = '';
        
        $.each(obj, function (i, el) {
            
            
            switch (el) {

            case true:

                if (i === 'Delete' || i === 'delete') {

                    classes = 'red-text';

                } else {

                    classes = 'grey-text';

                }

                templateOutput += '<a style=" padding-left: 12px; padding-right: 12px; " class="' + classes + ' text-lighten-1 modal-action left btn btn-flat transparent" href="#!" id="moreCard' + i + '"><i class="material-icons">' + i.toLowerCase() + '</i></a>';

                break;

            case false:

                templateOutput += '';

                break;

            default:

                return false;

            }
            
        });
        
        return templateOutput;
    };
    
    //-------------------------
    
    this.construct();
    
};
