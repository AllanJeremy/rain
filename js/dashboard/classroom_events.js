/*global $, jQuery, alert, console*/

var ClassroomEvents = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('classroom events created');
        
        //global inits
        Modals_Events.cleanOutModals();
        
        //classroom inits
        createClassroom();
        editClassroomCard();
        submitNewClassroom();
        addStudentsInClassroom();
        addMoreStudentsInClassroom();
        removeStudentsFromClassroom();
        submitEdittedClassroom();
        viewStudentsinClassroom();
        viewAssignmentsinClassroom();
        deleteClassroom();
    };
    
    //----------------------------
    //--------------------------------  CLASSROOM EVENTS
    //--------------------------------
    
    var deleteClassroom = function () {
        
        /*
        1. Close modal
        2. Visually remove the classroom card
        3. Call toast
        --  toast callback
        4. Delete classroom from database
        5. Remove classroom card from DOM
        6. Remove modal from DOM
        */
        
        $('main').on('click', '.modal#editClassRoom a#moreCardDelete', function (e) {
            e.preventDefault();
            
            var self = $(this),
                classid = localStorage.getItem("cardId"),
                toastMessage = '<p class="white-text" data-ref-class-id="' + classid + '">Preparing to delete classroom  <a href="#!" class="bold" id="toastUndoAction" >UNDO</a></p>',
                re = new RegExp("regex", "i");
            
            console.log('card id ' + classid + ' to be deleted.');
            
            //1 & 2
            $('.modal#editClassRoom').closeModal();
            //6
            Modals_Events.cleanOutModals();
            $('#classroomCardList .card-col[data-classroom-id=' + classid + ']').addClass('to-remove');
            
            //3
            var toastCall = Materialize.toast(toastMessage, 7200, '', function (s) {
                //4
                $.post("handlers/db_handler.php", {"action" : "DeleteClassroom", "classroomid" : classid}, function (result) {
                   
                    //5
                    $('#classroomCardList .card-col[data-classroom-id=' + classid + ']').remove();
                    
                    console.log(result);
                    
                    //6
                    //cleanOutModals();
                    
                }, 'text');
                
            });
             
        });
    };
    
    //--------------------------------
    
    var viewStudentsinClassroom = function () {
        
        //ajax get
        //load modal template
        
        $('main').on('click', '#classroomCardList a#openStudentsClassList', function (e) {
            
            var $this = $(this),
                classroomId = $(this).parents('.card-col').attr('data-classroom-id');

            if($this.hasClass('disabled')) {
                return;
            }

            $this.addClass('disabled');

            $.get("handlers/db_info.php", {"action": "GetAllStudentsInClass", "class_id" : classroomId}, function (result) {
                
                console.log('fetching students in classroom id:' + classroomId);
                
            }).success(function (result) {
                console.log(result);
                result = jQuery.parseJSON(result);

                if (jQuery.isEmptyObject(result)) {
                    // is empty or whitespace
                    console.log('empty. No students found');

                    var errorMessage = '<span>Sorry, there are no students found in this classroom.</span>';

                    $this.removeClass('disabled');
                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(errorMessage, 5000, 'red accent-3 white-text');

                } else {
                    result = cleanArray(result, 'true');
                    
                    console.log('Students found');
                    console.log(result);

                    var listVars = {
                        "id": "",
                        "name": ""
                    },
                        list = '',
                        admNo = '',
                        XHRs = [],
                        ajaxObjectResult = '',
                        responseLength;
                    
                    $.each(result, function (i, v) {
                        admNo = v;
                        console.log(v);

                        XHRs.push(
                            $.ajax({
                                url: "handlers/db_info.php",
                                type: 'GET',
                                dataType: 'json',
                                data: {"action": "StudentIdExists", "adm_no": admNo}
                            })
                        );
                          
                    });
                    
                    responseLength = (XHRs.length - 1);
                    
                    $.each(XHRs, function (b, n) {
                        console.log(b);
                        XHRs[b].done(function (x) {
                            console.log(b,responseLength);
                            
                            listVars.id = x.adm_no;
                            listVars.name = x.full_name;

                            list += Lists_Templates.studentTableList(listVars);
                            
                            if (b >= (responseLength)) {
                                console.log('last one');
                                
                                list += '';

                                //Continue with the rest of the functions
                                var listTemplate = {
                                    "listData" : list
                                },
                                    listData = Lists_Templates.studentTable(listTemplate);

                                //variables for the modal
                                var template = {
                                    modalId: 'ClassRoomStudents',
                                    templateHeader: 'Students in the classroom',
                                    templateBody: listData
                                };

                                $('main').append(Lists_Templates.modalTemplate(template));

                                $('#' + template.modalId).openModal({dismissible: false});
                                console.log('modal students classroom list created.');

                                $this.removeClass('disabled');
                            }
                            
                        });
                    
                    });
                }
            }, 'json');
            
        });
      
    };
    
    //----------------------------
    
    var viewAssignmentsinClassroom = function () {
        
        //ajax get
        //load modal template
        //open modal
        
        $('main').on('click', '#classroomCardList a#openAssignmentsClassList', function (e) {
            
            var $this = $(this),
                classroomId = $(this).parents('.card-col').attr('data-classroom-id');

            if($this.hasClass('disabled')) {
                return;
            }

            $this.addClass('disabled');
            
            $.get("handlers/db_info.php", {"action": "GetTeacherAssInClass", "class_id" : classroomId}, function (result) {
                
                console.log('fetching Assignments in classroom id:' + classroomId);
                
                if (jQuery.isEmptyObject(result)) {
                    // is empty or whitespace
                    console.log('empty. No assignments found');

                    var errorMessage = '<span>Sorry, there are no assignments found in this classroom.</span>';

                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(errorMessage, 5000, 'red accent-3 white-text', function () {
                    });

                } else {
                    
                    result = cleanArray(result, 'false');
                    
                    console.log('Assignments found');
                    console.log(result);

                    var listVars = {
                        "id": "",
                        "name": "",
                        "sent": "",
                        "datecreated": "",
                        "duedate": "",
                        "passgrade": ""
                    },
                        list = '',
                        ajaxObjectResult,
                        responseLength = result.length - 1;
                    
                    $.each(result, function (b, x) {
                        
                        console.log(x);
                        console.log(responseLength);
                        
                        listVars.id = x.ass_id;
                        listVars.name = x.ass_title;
                        listVars.datecreated = moment(x.date_sent).toString();
                        listVars.duedate = moment(x.due_date).fromNow();
                        listVars.passgrade = x.max_grade;
                        listVars.sent = x.sent;

                        console.log(listVars);
                        list += Lists_Templates.assignmentTableList(listVars);

                        if (b >= (responseLength)) {
                            console.log('last one');

                            list += '';

                            //Continue with the rest of the functions
                            var listTemplate = {
                                "listData" : list
                            },
                                listData = Lists_Templates.assignmentTable(listTemplate);

                            //variables for the modal
                            var template = {
                                modalId: 'ClassRoomAssignments',
                                templateHeader: 'All assignments given to the classroom',
                                templateBody: listData
                            };

                            $('main').append(Lists_Templates.modalTemplate(template));

                            $('#' + template.modalId).openModal({dismissible: false});

                            $this.removeClass('disabled');

                            console.log('modal assignments classroom list created.');
                        }

                    });
                }
            }, 'json');
        });
    };
    
    //----------------------------
    var getAllSubjects = function () {
        return $.ajax({
            url: "handlers/db_handler.php",
            type: 'GET',
            data: {"action" : "GetAllSubjects"}
        }, 'json');
    };

    //----------------------------
    var getAllStreams = function () {
        return $.ajax({
            url: "handlers/db_handler.php",
            type: 'GET',
            data: {"action" : "GetAllStreams"}
        }, 'json');
    };

    //----------------------------

    var subjectOptions = function (result) {
        var output = '',
            count = 0,
            optgroup = new Array(),
            totalOutput = '',
            obj;
        result = JSON.parse(result);

        for (var key in result) {
            if ( typeof optgroup[result[key].category] !== 'string' ) {
                optgroup[result[key].category] = Forms_Templates.formSelectTemplate(result[key]);

            } else {
                output = Forms_Templates.formSelectTemplate(result[key]);
                optgroup[result[key].category] += output;

            }
        }

        output += '';
        obj = {
            category : '',
            categorylist : ''
        };

        for (var i in optgroup) {

            obj.category = i;
            obj.categorylist = optgroup[i];

            totalOutput += Forms_Templates.formOptgroupTemplate(obj);

        }
        totalOutput +='';

        return totalOutput;

    };

    //----------------------------

    var streamOptions = function (result) {
        var totalOutput = '';
        result = JSON.parse(result);

        for(var u in result) {

            totalOutput += Forms_Templates.formSelectTemplate(result[u]);

        }
        return totalOutput;
    };

    //----------------------------
    
    var createClassroom = function () {
        
        //load form template
        //get subjects select list
        //load modal template
        //append form template to modal template
        //open modal
        $('a#createClassroom:not(.disabled)').click(function (e) {
            e.preventDefault();

            Modals_Events.cleanOutModals();//remove any modal if exists
            console.log('fetching form template');

            var totalOutput = '',
//                $this = $(this),
                SubjectOptions = '',
                SubjectHook = '',
                StreamOptions = '',
                StreamHook = '';

            if($(this).hasClass('disabled')) {
                return false;
            }

            $(this).addClass('disabled');
            console.log($(this));

            var formTemplateVars = {
                subjectoptions: SubjectOptions,
                streamoptions: StreamOptions

            };

            var formTemplate = Forms_Templates.createClassroomForm(formTemplateVars);

            //variables for the modal
            var template = {
                modalId: 'createNewClassRoom',
                templateHeader: 'Create a new Classroom',
                templateBody: formTemplate,
                modalActionType: 'type="submit"',
                modalActionTypeText: 'Create classroom'
            };

            //load the modal in the DOM
            $('main').append(Lists_Templates.modalTemplate(template));

            $(this).attr('data-target', template.modalId);

            $('#' + template.modalId).openModal({dismissible:false});

            console.log('modal create classroom form created.');

            $.when(getAllSubjects(), getAllStreams()).done(function (eSub, eStr) {
                console.log(eSub);
                SubjectOptions = subjectOptions(eSub[0]);
                StreamOptions = streamOptions(eStr[0]);

                if(formTemplateVars.streamoptions === '') {
                    //append the options
                    console.log('Appending select options');

                    $('.modal#' + template.modalId + ' form#createNewClassroomForm select#newClassroomStream').append(StreamOptions);
                    $('.modal#' + template.modalId + ' form#createNewClassroomForm select#newClassroomSubject').append(SubjectOptions);

                }

                Materialize.updateTextFields();//doesn't work
                $('select').material_select();
            });

            $(this).removeClass('disabled');
            console.log('mijiji');
//            return (false);
        });
    };
    
    //------------------------------
    
    var submitNewClassroom = function (str1, str2) {
        
        //get form variables
        //validate the variables
        //submit the variables
        //prepend the new classroom card to the list
        //initialize tooltip
        //initialize classroom events
        //close modal
        
        $('main').on('click', 'a#createNewClassroomCard', function (e) {
            e.preventDefault();
            
            console.log('submit event handler ready');
            
            var str1 = $('.modal#createNewClassRoom').attr('id'),
                str2 = $('#classroomCardList .card-col').first().attr('data-classroom-id'),//Get the class-id of the latest card then add +1
                classes = $('.modal#createNewClassRoom .card-color-list input[type="radio"]:checked').parent().children('label').attr('class'),
                newClassTitle = $('.modal#createNewClassRoom input#newClassroomName').val(),
                newClass_stream = $('.modal#createNewClassRoom select#newClassroomStream').val(),
                newClass_subject = $('.modal#createNewClassRoom select#newClassroomSubject').val(),
                newClass_streamname = $('.modal#createNewClassRoom select#newClassroomStream').find('option:selected:not(:disabled)').text(),
                newClass_subjectname = $('.modal#createNewClassRoom select#newClassroomSubject').find('option:selected:not(:disabled)').text(),
                $this = $(this),
                $thisEl = $this[0].innerHTML;

            if($this.hasClass('disabled')) {
                return false;
            }

            $this.addClass('disabled btn-loadiing')
                .text('creating...');

            console.log($this);

            console.log($('.modal#createNewClassRoom .students').attr('data-total-students'));
            console.log(classes);

            if (typeof classes === 'undefined') {
                //if color was not chosen
                classes = 'cyan darken-4';
            }
            
            if (typeof $('.modal#createNewClassRoom .students').attr('data-selected-students') === 'undefined') {
            
                var studentsToAdd = 0;
            } else {

                var studentsToAdd = $('.modal#createNewClassRoom .students').attr('data-selected-students');
            }

            if (typeof $('.modal#createNewClassRoom .students').attr('data-total-students') === 'undefined') {

                var totalStudents = 0;
            } else {

                var totalStudents = $('.modal#createNewClassRoom .students').attr('data-total-students');
            }
            
            console.log('class title: ' + newClassTitle);
            console.log('adding students : ' + studentsToAdd);
            console.log('total students to add : ' + totalStudents);
            console.log('subject id : ' + newClass_subject);
            console.log('stream name : ' + newClass_streamname);
            
            //validate first
            if (newClassTitle !== '' && newClass_stream !== '' && newClass_subject !== '') {
                    
                var formResults = {
                    action : 'CreateClassroom',
                    classroomid : ( parseInt(str2) + 1 ),
                    assignmentnumbers: '0',
                    classroomtitle : newClassTitle,
                    classroomstream : newClass_stream,
                    classroomstreamname : newClass_streamname,
                    classroomsubject : newClass_subject,
                    classroomsubjectname : newClass_subjectname,
                    totalstudents : totalStudents,
                    classes : classes,
                    studentids : studentsToAdd

                };
                    
                //ajax post
                var request = $.post("classes/classroom_class.php", formResults, function (result) {

                    console.log(result);

                    if (result === 'true') {

                        var classroomTab = $('#classroomTab #classroomCardList'),
                            Result = Lists_Templates.classRoomCard(formResults);

                        //masonryGridInit();
                        $this.removeClass('disabled btn-loadiing');
                        $this[0].inneHTML = $thisEl;

                        if ( $('#classroomTab .no-data-message').length > 0 ) {
                            
                            $('#classroomTab .no-data-message').remove();
                            
                            $('#classroomTab').append(Lists_Templates.classroomCardListContainer);
                            
                            //recall classroomTab after appending
                            classroomTab = $('#classroomTab #classroomCardList');

                            classroomTab.prepend(Result);
                        } else {
                            
                            classroomTab.prepend(Result);
                        }

                        $('.tooltipped').tooltip({delay: 50});
                        
                        var successMessage = '<span class="white-text name ">Class ' + formResults.classroomtitle + ' has been created!</span>';

                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast(successMessage, 3000, 'green accent-3', function () {
                            Modals_Events.cleanOutModals();
                        });
                    
                    $('#' + str1).closeModal();

                    } else {
                        console.log('waiting');
                    
                        $this.removeClass('disabled btn-loadiing');
                        $this[0].innerHTML = $thisEl;
                        var errorMessage = '<span class="white-text name ">Sorry, we found an error in creating the classroom<br>We will <a class="btn-inline" href="">reload</a> the page</span>';

                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast(successMessage, 3000, 'red accent-3', function () {
                            location.reload();
                        });
                    }
                    
                }, 'text');

                console.log(request.status);
            } else {
            
                console.log('empty form. Unable to create the class');

//                $('#' + str1).closeModal();
                $this.removeClass('disabled btn-loadiing');
                $this[0].innerHTML = $thisEl;
                
                var errorMessage = '<span class="white-text name ">Error in creating the classroom. Kindly see if you have filled all inputs.</span>';
                
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(errorMessage, 3000, 'red accent-3', function () {
//                    Modals_Events.cleanOutModals();
                });
            }

//            $('#' + str1).closeModal();
//            Modals_Events.cleanOutModals();

        });
    };
    
    //-------------------------------
    
    var editClassroomCard = function () {
        
        //get the selected classroom id         1
        //get classroom data                    2
        //highlight classroom card              3
        //load form with the classroom data     4
        //load modal                            5
        //append form to modal                  6
        //append modal to DOM                   7
        //open modal                            8
        
        $('main').on('click', 'a#editClassroom', function (e) {
            
            e.preventDefault();
            
            var self = $(this), resultData = '',
                totalOutput = '', SubjectOptions = '',
                SubjectHook = '', StreamOptions = '',
                StreamHook = '', activeInputId = '',
            /*1*/classroomId = self.parents('.card-col').attr('data-classroom-id');
            
            self.parents('.card-col').attr('data-classroom-id');
            
            console.log(classroomId);
            
            $.get("handlers/db_info.php", { "action" : "ClassroomExists", "class_id" : classroomId }, function (classroomData) {
                
                console.log(typeof classroomData);

                if (classroomData !== 'null') {

                    resultData = classroomData;
                    activeInputId = resultData.classes;
                    localStorage.setItem("cardColor", resultData.classes);
                    localStorage.setItem("selectedStudents", resultData.student_ids);
                    localStorage.setItem("cardId", classroomId);

                    /*3*/self.parents('.card').removeClass(resultData.classes)
                        .addClass('grey z-depth-4 to-edit');

                    Modals_Events.cleanOutModals();//remove any modal if exists

                    console.log(resultData);
                    console.log('fetching form template');

                    //get list of subjects
                    var formTemplateVars = {
                        subjectoptions: SubjectOptions,
                        streamoptions: StreamOptions

                    },
                        formTemplate = Forms_Templates.editClassroomForm(formTemplateVars);

                    //variables for the modal
                    var template = {
                        classes: resultData.classes,
                        modalId: 'editClassRoom',
                        templateHeader: 'Edit Classroom',
                        templateBody: formTemplate,
                        modalActionType: 'type="submit"',
                        modalActionTypeText: 'Update classroom',
                        extraActions: Lists_Templates.editExtraFooterActions({
                            "Delete" : true,
                            "Archive" : true,
                            "Reload" : false
                        })
                    };

                    //load the modal in the DOM
                    $('main').append(Lists_Templates.modalTemplate(template));

                    $(this).attr('data-target', template.modalId);

                    $('#' + template.modalId).openModal({dismissible:false});

                    $.when(getAllSubjects(), getAllStreams()).done(function (eSub, eStr) {
                        console.log(eSub);
                        console.log('creating a dropdowns.');
                        SubjectOptions = subjectOptions(eSub[0]);
                        StreamOptions = streamOptions(eStr[0]);

                        if(formTemplateVars.streamoptions === '' && $('.modal#' + template.modalId).length > 0) {
                            //append the options
                            console.log('Appending select options');

                            $('.modal#' + template.modalId + ' form#editClassroomForm select#editClassroomStream')
                                .append(StreamOptions)[0].value = resultData.stream_id;
                            $('.modal#' + template.modalId + ' form#editClassroomForm select#editClassroomSubject')
                                .append(SubjectOptions)[0].value = resultData.subject_id;

                        }

                        $('select').material_select();
                    });

                    activeInputId = activeInputId.split(' darken-4')[0].split('-').join('');
                    //load current class data to the form
                    console.log(activeInputId);
                    console.log(resultData.classes);
                    console.log($('main .modal#' + template.modalId + ' .card-color-list input#' + activeInputId));

                    $('main .modal#' + template.modalId + ' .card-color-list input#' + activeInputId).prop('checked', true);

                    $('main .modal#' + template.modalId + ' input#editClassroomName').val(resultData.class_name);

                    Materialize.updateTextFields();

                    $('.dropdown-button').dropdown({
                        hover: false,
                        alignment: 'bottom',
                        constrain_width: false, // Does not change width of dropdown to that of the activator
                        gutter: 300,
                        belowOrigin: false
                    });

                    console.log('modal edit classroom form created.');

                    if(resultData.student_ids) {

                        console.log('dd');

                        var previouslySelectedStudents = resultData.student_ids,
                            totalSelected = previouslySelectedStudents.split(',').length - 1;

                        $('#' + template.modalId + ' .student-list')
                            .append('<div class="col s12 rain-theme-primary previous students lighten-2 card-panel " data-total-students="'
                                    + totalSelected + '" data-selected-students="'
                                    + previouslySelectedStudents + '"><p class="white-text php-data">A total of '
                                    + totalSelected + ' student' + ( (totalSelected > 1) ? 's are' : ' is' ) + ' in the classroom. </p> <p><a id="removeStudentsFromClassroom" class="btn btn-small ' + ( (totalSelected < 1) ? 'disabled hide' : '' ) + '"> Remove students</a></p><br></div>');

                    }

                } else {
                    
                    var toastMessage = '<p class="white-text">Oops, technical error, esomo needs to reload. <a href="" class="btn">reload</a></p>';
                    
                    Materialize.toast(toastMessage, '6000', 'red accent-3');
                }
            }, 'json');
            
        });
        
        //return false;

    };

    //--------------------------------
    
    var submitEdittedClassroom = function () {
        
        //get the form data                     1
        //validate the form data                2
        //post the data to db (update)          3
        //Change the highlighted card data      4
        //Update card data in the DOM           5
        //close modal                           6
        //clean out modals from DOM             7
        
        $('main').on('click', 'form#editClassroomForm a#editClassroomCard', function (e) {
            e.preventDefault();
            
            /*1*/
            var self = $(this),
                selfEl = $(this)[0],
                str1 = $('.modal#editClassRoom').attr('id'),
                str2 = Materialize.guid(),//Generate class-id
                classes = $('.modal#editClassRoom .card-color-list input[type="radio"]:checked').parent().children('label').attr('class'),
                newClassTitle = $('.modal#editClassRoom input#editClassroomName').val(),
                newClass_stream = $('.modal#editClassRoom select#editClassroomStream').val(),
                newClass_subject = $('.modal#editClassRoom select#editClassroomSubject').val(),
                newClass_streamname = $('.modal#editClassRoom select#editClassroomStream').find('option:selected:not(:disabled)').text(),
                newClass_subjectname = $('.modal#editClassRoom select#editClassroomSubject').find('option:selected:not(:disabled)').text();


            if (typeof classes === 'undefined') {
                
                classes = localStorage.getItem("cardColor");
                
            }
            
            if (typeof $('.modal#editClassRoom .students').attr('data-selected-students') === 'undefined') {
            
                var studentsToAdd = localStorage.getItem("selectedStudents");

            } else {

                var studentsToAdd = $('.modal#editClassRoom .students').attr('data-selected-students');

            }

            if (typeof $('.modal#editClassRoom .students').attr('data-total-students') === 'undefined') {

                var totalStudents = studentsToAdd.split(',').length;

            } else {

                var totalStudents = $('.modal#editClassRoom .students').attr('data-total-students');

            }
            
            console.log('class title: ' + newClassTitle);
            console.log('adding students : ' + studentsToAdd);
            
             /*2*/
            //validate first
            if (newClassTitle !== '' && newClass_stream !== '' && newClass_subject !== '') {

                self.addClass('disabled btn-loading').text('updating...');

                var formResults = {
                    action : 'UpdateClassroomInfo',
                    classroomid : localStorage.getItem("cardId"),
                    assignmentnumbers: '0',
                    classroomtitle : newClassTitle,
                    classroomstream : newClass_stream,
                    classroomstreamname : newClass_streamname,
                    classroomsubject : newClass_subject,
                    classroomsubjectname : newClass_subjectname,
                    totalstudents : totalStudents,
                    classes : classes,
                    studentids : studentsToAdd

                };
              
        /*3*/
                //ajax post
                console.log(formResults);
                    
                var request = $.post( "handlers/db_handler.php", formResults, function (result) {

                console.log(result);

                if(result === 'true') {

                    var classroomTab = $('#classroomTab #classroomCardList');
        /*4*/
                    var Result = Lists_Templates.classRoomCardData(formResults);
        /*5*/
                    var hook = $('.card.to-edit').parent('.card-col[data-classroom-id=' + formResults.classroomid + ']');
                    
                    console.log('Updating card id ' + formResults.classroomid + '.');
                    
                    $('.card-col[data-classroom-id=' + formResults.classroomid + '] .card.to-edit').remove();
                    
                    hook.append(Result);
                    
                    $('.tooltipped').tooltip({delay: 50});
                    var successMessage = '<span class="white-text name ">Class ' + formResults.classroomtitle + ' has been update!</span>';

                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(successMessage, 5000, 'green accent-3');

        /*6*/
                    $('#' + str1).closeModal();
        /*7*/
                    Modals_Events.cleanOutModals();
                    
                } else {

                    console.log('waiting');

                    var errorMessage = '<span class="white-text name ">Sorry, we found an error in updating the classroom<br>We will <a class="btn-action" href="' + location.href + 'reload</a> the page</span>';

                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(errorMessage, 5000, 'red accent-3');
                }

            }, 'text');

            } else {
            
                console.log('empty form. Unable to create the class');

                $('#' + str1).closeModal();
                
                var errorMessage = '<span class="red-text name text-lighten-5">Error in updating the classroom. Kindly see if you have filled all inputs.</span>';
                
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(errorMessage, 5000);
                
            }
            
        });
    };
    
    //--------------------------------
    
    var addStudentsInClassroom = function () {
        
        var checkboxEl = 'input#addStudentsToClassroom',
            checkedCheckboxEl = 'input#addStudentsToClassroom:checked, input#addMoreStudentsToClassroom:checked',
            modal_id = 'NewClassStudentList',
            main = $('main');
        
        main.on('change', checkboxEl, function (e) {
        
            console.log('students adding function on');
        
            e.preventDefault();
            
            var hook = $('.student-list');
            
            console.log('V- ' + $(checkboxEl).val());
            console.log('V- ' + $(checkboxEl).attr('name'));
            console.log('V- ' + $(checkboxEl).attr('id'));
            
            var action = $(checkboxEl).val();
            
            console.log('length- ' + $(checkedCheckboxEl).length);
            
            if($(checkedCheckboxEl).length > 0) {//checked
                
                //remove existing esomo modal for student list
                Modals_Events.cleanOutModal('#esomoModal' + modal_id);
                
                Materialize.toast('Fetching students', 15000, 'white-text');

                var classiD = localStorage.getItem("cardId"),
                    subject = $('select#newClassroomSubject').val();
                
                console.log('adding list');

                //console.log('args-' + subject + stream);
                
                if( $(checkboxEl).val() === "GetAllStudentsNotInClass" ) {

                    $.get('handlers/db_info.php', {"action" : action, "class_id" : localStorage.getItem("cardId")}, function (result) {
                        console.log('get results:- ');

                        result = JSON.parse(result);
                        console.log(result);
                        console.log(typeof result);

                        if (typeof result === 'undefined') {

                        }

                        if (typeof result === 'object') {
                            //loop

                            var output = '';
                            var count = 0;
                            var autocompletedata = '{';//limit autocomplete dropdown to 20;

                            for (var key in result) {

                                output += Forms_Templates.formOptionsTemplate(result[key]);

                                if (count < 21 && result[key].name != 'undefined') {

                                    if (key > 0) {

                                        autocompletedata += ',';

                                    }

                                    autocompletedata += '"';
                                    autocompletedata += result[key].name;
                                    autocompletedata += '" : ';
                                    autocompletedata += 'null';

                                    count++;

                                }

                            }

                            output += '';

                            autocompletedata += '}';

                            console.log(autocompletedata);


                            autocompletedata = jQuery.parseJSON(autocompletedata);

                            console.log(autocompletedata);

                            $('input#searchStudentFormList').autocomplete({
                                data: autocompletedata
                            });

                            var formOptionsTemplate = {
                                "formData" : output
                            },
                                formList = Forms_Templates.makeStudentFormList(formOptionsTemplate);

                            //open the esomo modal Template
                            //append the list to esomo modal

                            var modal_header = 'Add students to the classroom',
                                modal_body = formList,
                                modal_action = 'Add',
                                action2 = 'morph-in',
                                studentListModal = Modals_Events.loadEsomoModal(modal_id, modal_header, modal_body, modal_action);

//                            console.log(formList);

                            $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                            $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-action').bind('click', function(e) {
                                e.preventDefault();
                                $(this).addClass('disabled btn-loading')
                                    .text('adding...');

                                addToForm(action2, hook, modal_id, 1); //when add students is clicked//

                                $(this).removeClass('disabled btn-loading');
                                $(this)[0].innerHTML = el;
                            }); //when add students is clicked//

                        }

                    })
                      .success( function (result) {
                        $('body').find('#toast-container .toast:nth-of-type(1)')
                            .addClass('panning')
                            .animate({
                                'margin-top' : '-40px',
                                'opacity' : 0
                            }, 820, function () {
                            console.log('REMOVE TOAST');
                            this.remove();
                        });

                        console.log('success');

                    }, 'json');
                
                } else if ( $(checkboxEl).val() === "GetAllStudents" ) {

                    $.get('handlers/db_info.php', { "action" : action }, function (result) {
                        console.log('get results:- ');

                        result = JSON.parse(result);
                        console.log(result);
                        console.log(typeof result);

                        if (typeof result === 'undefined') {

                        }

                        if (typeof result === 'object') {
                            //loop

                            var output = '';
                            var count = 0;
                            var autocompletedata = '{';//limit autocomplete dropdown to 20;

                            for (var key in result) {

                                output += Forms_Templates.formOptionsTemplate(result[key]);

                                if (count < 21 && result[key].name != 'undefined') {

                                    if (key > 0) {

                                        autocompletedata += ',';

                                    }

                                    autocompletedata += '"';
                                    autocompletedata += result[key].name;
                                    autocompletedata += '" : ';
                                    autocompletedata += 'null';

                                    count++;

                                }

                            }

                            output += '';

                            autocompletedata += '}';

                            console.log(autocompletedata);


                            autocompletedata = jQuery.parseJSON(autocompletedata);

                            console.log(autocompletedata);

                            $('input#searchStudentFormList').autocomplete({
                                data: autocompletedata
                            });

                            var formOptionsTemplate = {
                                "formData" : output
                            };

                            var formList = Forms_Templates.makeStudentFormList(formOptionsTemplate);

                            //open the esomo modal Template
                            //append the list to esomo modal

                            var modal_header = 'Add students to the classroom';

                            var modal_body = formList;

                            var modal_action = 'Add';
                            
                            var studentListModal = Modals_Events.loadEsomoModal(modal_id, modal_header, modal_body, modal_action);

                            $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                            console.log(formList);

                            var action2 = 'morph-in';

                            $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-action').bind('click', function(e) {
                                e.preventDefault();
                                $(this).addClass('disabled btn-loading')
                                    .text('adding...');

                                addToForm(action2, hook, modal_id, 1); //when add students is clicked//

                                $(this).removeClass('disabled btn-loading');
                                $(this)[0].innerHTML = el;
                            }); //when add students is clicked//


                        }

                    })
                      .success( function (result) {
                        $('body').find('#toast-container .toast:nth-of-type(1)')
                            .addClass('panning')
                            .animate({
                                'margin-top' : '-40px',
                                'opacity' : 0
                            }, 820, function () {
                            console.log('REMOVE TOAST');
                            this.remove();
                        });

                        console.log('success dsfdf');
                        console.log('success');

                    }, 'json');
                }
  
            } else if ($(checkedCheckboxEl).length < 1) {
            
                Modals_Events.cleanOutModal('#esomoModal' + modal_id);
                
                console.log('removing list');
            
                hook.fadeOut(300, function () {
                    
                    $(this).html(' ');
                    
                    $(this).show();
                    
                });
                
            }
            
        });
        
    };

    //--------------------------------
    
    var addMoreStudentsInClassroom = function () {
        
        
        var el = 'a#addMoreStudentsToClassroom';
        var modal_id = 'MoreClassStudentList';
                        
        var main = $('main');
        
        main.on('click', el, function (e) {
        
            console.log('More students adding on');
        
            e.preventDefault();
            
            var hook = $('.student-list'),
                elEL = $(el)[0].innerHTML,
                action = $(el).attr('data-action'),
                classiD = localStorage.getItem("cardId"),
                subject = $('select#newClassroomSubject').val();
            
            console.log('V- ' + $(el).attr('data-action'));
            console.log('V- ' + $(el).attr('id'));
            
            $(el).addClass('btn-loading disabled')
                .text('fetching...');
            
            //remove existing esomo modal
            Modals_Events.cleanOutModal('#esomoModal' + modal_id);

            Materialize.toast('Fetching more students', 15000, 'white-text');

            console.log('adding list');

            if( action === "GetAllStudentsNotInClass" ) {

                $.get('handlers/db_info.php', {"action" : action, "class_id" : localStorage.getItem("cardId")}, function (result) {
                    console.log('get results:- ');

                    result = JSON.parse(result);
                    console.log(result);
                    console.log(typeof result);

                    if (typeof result === 'undefined') {

                    }

                    if (typeof result === 'object') {
                        //loop

                        var output = '',
                            count = 0,
                            autocompletedata = '{';//limit autocomplete dropdown to 20;

                        for (var key in result) {

                            output += Forms_Templates.formOptionsTemplate(result[key]);

                            if (count < 21 && result[key].name != 'undefined') {

                                if (key > 0) {

                                    autocompletedata += ',';

                                }

                                autocompletedata += '"';
                                autocompletedata += result[key].name;
                                autocompletedata += '" : ';
                                autocompletedata += 'null';

                                count++;

                            }
                        }

                        output += '';

                        autocompletedata += '}';

                        autocompletedata = jQuery.parseJSON(autocompletedata);

                        //console.log(autocompletedata);

                        $('input#searchStudentFormList').autocomplete({
                            data: autocompletedata
                        });

                        var formOptionsTemplate = {
                            "formData" : output
                        },
                            formList = Forms_Templates.makeStudentFormList(formOptionsTemplate);

                        //open the esomo modal Template
                        //append the list to esomo modal

                        var modal_header = 'Add students to the classroom',
                            modal_body = formList,
                            modal_action = 'Add',
                            action2 = 'morph-in',
                            studentListModal = Modals_Events.loadEsomoModal(modal_id, modal_header, modal_body, modal_action);

                        $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                        console.log(formList);

                        $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-action').bind('click', function(e) {
                            e.preventDefault();
                            $(this).addClass('disabled btn-loading')
                                .text('adding...');

                            addToForm(action2, hook, modal_id, 1); //when add students is clicked//

                            $(this).removeClass('disabled btn-loading');
                            $(this)[0].innerHTML = el;
                        }); //when add students is clicked//


                    }

                })
                  .success( function (result) {

                    $(el).removeClass('btn-loading disabled');
                    $(el)[0].innerHTML = elEL;

                    $('body').find('#toast-container .toast:nth-of-type(1)')
                        .addClass('panning')
                        .animate({
                        'margin-top' : '-40px',
                        'opacity' : 0
                    }, 820, function () {
                        console.log('REMOVE TOAST');
                        this.remove();

                    });

                    console.log('success');

                }, 'json');

            } else if ( action === "GetAllStudents" ) {

                $.get('handlers/db_info.php', { "action" : action }, function (result) {
                    console.log('get results:- ');

                    result = JSON.parse(result);
                    console.log(result);
                    console.log(typeof result);

                    if (typeof result === 'undefined') {

                    }

                    if (typeof result === 'object') {
                        //loop

                        var output = '',
                            count = 0,
                            autocompletedata = '{';//limit autocomplete dropdown to 20;

                        for (var key in result) {

                            output += Forms_Templates.formOptionsTemplate(result[key]);

                            if (count < 21 && result[key].name != 'undefined') {

                                if (key > 0) {

                                    autocompletedata += ',';

                                }

                                autocompletedata += '"';
                                autocompletedata += result[key].name;
                                autocompletedata += '" : ';
                                autocompletedata += 'null';

                                count++;

                            }

                        }

                        output += '';

                        autocompletedata += '}';

                        console.log(autocompletedata);

                        autocompletedata = jQuery.parseJSON(autocompletedata);

                        console.log(autocompletedata);

                        $('input#searchStudentFormList').autocomplete({
                            data: autocompletedata
                        });

                        var formOptionsTemplate = {
                            "formData" : output
                        };
                        
                        var formList = Forms_Templates.makeStudentFormList(formOptionsTemplate);

                        //open the esomo modal Template
                        //append the list to esomo modal

                        var modal_header = 'Add students to the classroom',
                            modal_body = formList,
                            modal_action = 'Add',
                            action2 = 'morph-in',
                            studentListModal = Modals_Events.loadEsomoModal(modal_id, modal_header, modal_body, modal_action);

                        $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                        console.log(formList);


                        $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-action').bind('click', function(e) {
                            e.preventDefault();
                            $(this).addClass('disabled btn-loading')
                                .text('adding...');

                            addToForm(action2, hook, modal_id, 1); //when add students is clicked//

                            $(this).removeClass('disabled btn-loading');
                            $(this)[0].innerHTML = el;
                        }); //when add students is clicked//
                    }

                })
                  .success( function (result) {

                    $(el).removeClass('btn-loading disabled');
                    $(el)[0].innerHTML = elEL;

                    $('body').find('#toast-container .toast:nth-of-type(1)')
                        .addClass('panning')
                        .animate({
                            'margin-top' : '-40px',
                            'opacity' : 0
                        }, 820, function () {
                        console.log('REMOVE TOAST');
                        this.remove();
                    });

                    console.log('success');

                }, 'json');
            }
            
        });
        
    };
       
    //--------------------------------
    
    var removeStudentsFromClassroom = function () {
        
        
        var el = 'a#removeStudentsFromClassroom';
        var modal_id = 'currentclassStudentList';
                        
        var main = $('main');
        
        main.on('click', el, function (e) {
        
            console.log('removing students function on');
        
            e.preventDefault();
            
            var hook = $('.student-list');
            
            console.log('V- ' + $(el).attr('data-action'));
            console.log('V- ' + $(el).attr('id'));
            
            var action = $(el).attr('data-action');
            
            //remove existing esomo modal
            Modals_Events.cleanOutModal('#esomoModal' + modal_id);

            var classiD = localStorage.getItem("cardId");

            console.log('making list');

            var currentChosenStudents = hook.children('.students').attr('data-selected-students');
            console.log(currentChosenStudents);
            
            currentChosenStudents = cleanArray(currentChosenStudents.split(','), 'false');
            console.log(currentChosenStudents);
            
            
            var listVars = {
                "id":"",
                "name":"",
            };

            var formList = '';
            var admNo = '';
            var XHRs = [];
            var ajaxObjectResult = '';

            $.each(currentChosenStudents, function(i, v) {

                admNo = v;

                console.log(v);

                XHRs.push(
                    $.ajax({
                        url: "handlers/db_info.php",
                        type: 'GET',
                        dataType: 'json',
                        data: {"action": "StudentIdExists", "adm_no": admNo}
                    })
                );

            });

            var responseLength = (XHRs.length - 1);
            var k = 0;

            $.each(XHRs, function(b, n) {

                XHRs[b].done(function(x) {

                    console.log(b);
                    console.log(responseLength);

                    if ( k < (responseLength) ) {

                        console.log('still less');

                        listVars.id = x.adm_no;
                        listVars.name = x.full_name;

                        formList += Forms_Templates.formOptionsTemplate(listVars);

                        k++;

                    } else {

                        console.log('last one')

                        listVars.id = x.adm_no;
                        listVars.name = x.full_name;

                        formList += Forms_Templates.formOptionsTemplate(listVars);

                        //Continue with the rest of the functions
                        var formOptionsTemplate = {
                            "formData" : formList
                        };

                        var formListData = Forms_Templates.makeStudentFormList(formOptionsTemplate);
                        
                        var modal_header = 'Remove students from the classroom';

                        var modal_body = formListData;
                        
                        var modal_action = 'Remove';

                        var studentListModal = Modals_Events.loadEsomoModal(modal_id, modal_header, modal_body, modal_action);

                        $('.modal#esomoModal' + modal_id).openModal({dismissible:false});

                        console.log('modal students classroom list created.');

                        var action2 = 'morph-in';

                        $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-action').bind('click', function(e) {
                            e.preventDefault();
                            $(this).addClass('disabled btn-loading')
                                .text('adding...');

                            addToForm(action2, hook, modal_id, 0); //when add or remove students is clicked//
                            
                            $(this).removeClass('disabled btn-loading');
                            $(this)[0].innerHTML = el;
                        }); //when add or remove students is clicked//

                    }

                });

            });
    
        });
        
    };
       
    //--------------------------------
    //--------------------------------  END OF CLASSROOM EVENTS AND FUNCTIONS
    //--------------------------------
  
    //--------------------------------
    
    var addToForm = function (action2, hook, modal_id, int) {

        console.log('Function Inited');
        
        console.log(modal_id);

        console.log('adding to form now');

        if (action2 != 'undefined') {

            //getting the list

        }

        
        var totalSelected = $('#esomoModal' + modal_id + ' .list').find('input[type="checkbox"]:checked').length;

        var selectedArrayResult = $('#esomoModal' + modal_id + ' .list').find('input[type="checkbox"]:checked').map(function(){
            return $(this).attr('id');
        }).get(); // <----

        var selectedStringFormat = selectedArrayResult.toString();
        
        selectedStringFormat += ',';//for database' sake, let the string end with a commar*
        
        
        if (typeof totalSelected === 'number' && totalSelected > 0) {

            console.log(hook.attr('class'));
            
            var hookType = hook.attr('class');
            
            hookType = hookType.split('col').join('')
                .split('s12').join('')
                .split('input-field').join('')
                .split('row').join('')
                .split(' ').join('');
            
            console.log(hookType);
            
            if(hookType === 'student-list') {//classroom form
                if ($('.modal#editClassRoom').find('.students').length > 0) {

                    var previousTotal = $('.modal#editClassRoom .students').attr('data-total-students');

                    console.log(selectedStringFormat);
                    
                    switch (int) {
                    
                        case 1:
                            console.log('Add, Don\'t tamper with the results');
                            
                            if ($('.modal#editClassRoom .students').attr('data-selected-students') === '0') {

                            } else {

                                //Add the students already in classroom with the current chosen students to be added.
                                selectedStringFormat += $('.modal#editClassRoom .students').attr('data-selected-students');
                                console.log(selectedStringFormat);

                            }

                            selectedStringFormat = cleanArray(selectedStringFormat.split(','), 'false');
                            console.log(selectedStringFormat);
                            selectedStringFormat = jQuery.unique( selectedStringFormat );
                            console.log(selectedStringFormat);

                            selectedStringFormat = selectedStringFormat.toString();
                            console.log(selectedStringFormat);
                            selectedStringFormat += ',';
                            console.log(selectedStringFormat);
                            console.log(selectedStringFormat.split(',').length);

                            $('.modal#editClassRoom .student-list').find('.students').remove();
                            
                            hook.append('<div class="col s12 rain-theme-primary students lighten-2 card-panel " data-total-students="'
                                        + (selectedStringFormat.split(',').length - 1) + '" data-selected-students="' + selectedStringFormat + 
                                        '"><p class="white-text php-data">' + previousTotal + ' student' + ( (previousTotal > 1) ? 's are' : ' is' ) + ' already in the classroom<br>' + 
                                        ((selectedStringFormat.split(',').length - 1) - parseInt(previousTotal)) + 
                                        ' more student' + ( (((selectedStringFormat.split(',').length - 1) - parseInt(previousTotal)) > 1) ? 's' : '' ) + ' will be added to the classroom on submit. </p><p><a id="removeStudentsFromClassroom" class="btn"> Remove students</a></p><br></div>');
                            break;
                            
                        case 0:
                            console.log('Remove selected array from the previously selected students in classroom.');

                            if ($('.modal#editClassRoom .students').attr('data-selected-students') === '0') {

                            } else {
                                
                                var previousSelected = $('.modal#editClassRoom .students').attr('data-selected-students');
                                
                                previousSelected = cleanArray(previousSelected.split(','), 'false');
                                
                                //remove all elements contained in the other array.
                                selectedStringFormat = previousSelected.filter( function( el ) {
                                    return !selectedArrayResult.includes( el );
                                } );

                            }

                            console.log(selectedStringFormat);
                            selectedStringFormat = jQuery.unique( selectedStringFormat );
                            console.log(selectedStringFormat);
                            
                            selectedStringFormat = selectedStringFormat.toString();

                            if(selectedStringFormat === '') {
                                
                                selectedStringFormat += '0';
                                
                            } else {
                                
                                selectedStringFormat += ',';
                            
                            }
                            
                            console.log(selectedStringFormat);
                            console.log(selectedStringFormat);
                            console.log(selectedStringFormat.split(',').length);

                            $('.modal#editClassRoom .students').remove();

                            hook.append('<div class="col s12 rain-theme-primary students lighten-2 card-panel " data-total-students="'
                                        + (selectedStringFormat.split(',').length - 1) + '" data-selected-students="' + selectedStringFormat + 
                                        '"><p class="white-text php-data">' 
                                        + (parseInt(previousTotal) - (selectedStringFormat.split(',').length - 1)) + 
                                        ' student' + ( ((parseInt(previousTotal) - (selectedStringFormat.split(',').length - 1)) > 1) ? 's' : '' ) + ' will be removed from the classroom on submit. </p><p><a id="removeStudentsFromClassroom" class="btn"> Remove more students</a></p><br></div>');
                            
                            break;
                            
                        default:
                            console.log('Error message in addToForm function.');
                            console.log('AddToForm action not chosen');
                            
                            break;
                    }


                } else {
                    
                    hook.append('<div class="col s12 rain-theme-primary students lighten-2 card-panel " data-total-students="'
                                + totalSelected + '" data-selected-students="' + selectedStringFormat + 
                                '"><p class="white-text php-data">A total of ' + totalSelected + 
                                ' students to be added in the classroom. </p><p><a id="removeStudentsFromClassroom" class="btn"> Remove students</a></p><br></div>');
                    
                }
                
            } else if(hookType === 'classroom-list') {//student form

                if ($('.modal#editAssignment .classrooms').length > 0) {

                    var previousTotal = $('.modal#editAssignment .classrooms').attr('data-total-classrooms');

                    console.log(selectedStringFormat);
                    selectedStringFormat += $('.modal#editAssignment .classrooms').attr('data-selected-classrooms');
                    console.log(selectedStringFormat);
                    selectedStringFormat = cleanArray(selectedStringFormat.split(','), 'false');
                    console.log(selectedStringFormat);
                    selectedStringFormat = jQuery.unique( selectedStringFormat );
                    console.log(selectedStringFormat);
                    selectedStringFormat = selectedStringFormat.toString();
                    console.log(selectedStringFormat);
                    selectedStringFormat += ',';
                    console.log(selectedStringFormat);
                    console.log(selectedStringFormat.split(',').length);


                    $('.modal#editAssignment .classrooms').remove();

                    hook.append('<div class="col s12 rain-theme-primary classrooms lighten-2 card-panel " data-total-classrooms="'
                                + (selectedStringFormat.split(',').length - 1) + '" data-selected-classrooms="' 
                                + selectedStringFormat + '"><p class="white-text php-data">' 
                                + previousTotal + ' classrooms have the assignment<br>' 
                                + ((selectedStringFormat.split(',').length - 1) - parseInt(previousTotal)) + ' more classrooms will receive this assignment on submit.<p><p><input id="canComment" class="filled-in" type="checkbox"><label for="canComment">Allow students to comment</label></p><br></div>');

                } else {

                    hook.append('<div class="col s12 rain-theme-primary classrooms lighten-2 card-panel " data-total-classrooms="'
                                + totalSelected + '" data-selected-classrooms="' 
                                + selectedStringFormat + '"><p class="white-text php-data">A total of ' 
                                + totalSelected + ' classrooms to receive the assignment.</p><p><input id="canComment" class="filled-in" type="checkbox"><label for="canComment">Allow students to comment</label></p><br></div>');

                }

            }
            
            console.log(totalSelected);

            Modals_Events.cleanOutModal('#esomoModal' + modal_id);

            return true;

        } else {

            console.log(totalSelected);

            Modals_Events.cleanOutModal('#esomoModal' + modal_id);

            return null;

        }
        
    };
    
    //--------------------------------
    
    var cleanArray = function (actual, str) {

        switch (str) {
            
            case 'true': //Clean zero array elements.
            
                console.log(typeof actual);
                
                var newArray = new Array();
        
                for (var i = 0; i < actual.length; i++) {

                    if (actual[i]) {

                        newArray.push(actual[i]);

                    }

                
                }

                var toRemove = [ "0", " " ];
                
                console.log(typeof toRemove);
                
                console.log(toRemove);
                console.log(newArray);
                
                newArray = newArray.filter( function( el ) {
                    return !toRemove.includes( el );
                } );
                
                return newArray;
                
                break;
            
            case 'false':
                
                var newArray = new Array();
        
                for (var i = 0; i < actual.length; i++) {

                    if (actual[i]) {

                        newArray.push(actual[i]);

                    }

                }
                
                console.log(newArray);
                
                return newArray;
                
                break;
            
            default:
                
                str = 'false';
        
                var newArray = new Array();
        
                for (var i = 0; i < actual.length; i++) {

                    if (actual[i]) {

                        newArray.push(actual[i]);

                    }

                }

                return newArray;
            
                break;
                
        }
        
        
    };
    
    //--------------------------------
    
    var searchIfClassNameExists = function (className) {
        
        console.log('checking if class name ' + className + ' exists');
        
        return 1;
        
    };

    this.__construct();
    
};
