/*global $, jQuery, alert, console*/

var Events = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('events created');
        
        cleanOutModals();
        createClassroom();
        editClassroomCard();
        submitNewClassroom();
        addStudentsInClassroom();
        closeModalsEvent();
        
    };
    
    //----------------------------
    
    var closeModalsEvent = function () {
        
        $('main').on('click', '.modal a#modalFooterCloseAction.modal-close', function(e) {
            
            console.log('removing modal from DOM');
        
            $(this).parents('.modal').remove();
            
        });
        
        //$('a#createClassroom').attr('data-target', '');
            
        //$('.modal').remove();

    };
    
    //----------------------------
    
    var cleanOutModal = function (str) {
        
        console.log('cleaning out modal' + str);
        
        $('.modal' + str).remove();

        console.log($('main').find('.modal' + str).length);
        
    };
    
    //----------------------------
    
    var cleanOutModals = function () {
        
        console.log('cleaning out modals');
        
        $('a#createClassroom').attr('data-target', '');
        
        $('.modal ').remove();

    };
    
    //----------------------------
    
    var createClassroom = function () {
        //load form template
        
        $('a#createClassroom').click(function (e) {
            e.preventDefault();
            
            cleanOutModals();//remove any modal if exists
            
            var classroomid = $('.new-class').attr('data-classroom-id');
            
            classroomid++;
            
            console.log('new classroom id:- ' + classroomid);
            
            $('.new-class').removeClass('new-class');
            
            console.log('fetching form template');
            
            //get list of subjects
            var formTemplateVars = {
                optgroupname: 'Sciences',
                optgroupname2: 'Languages',
                subjectoption1: 'Mathematics',
                subjectoption2: 'js Physics',
                subjectoption3: 'js Physi',
                subjectoption4: 'js Phcs'
            };
            
            var formTemplate = Forms_Templates.createClassroomForm(formTemplateVars);

            //variables for the modal
            var template = {
                modalId: 'createNewClassRoom',
                templateHeader: 'Create a new ClassRoom',
                templateBody: formTemplate,
                modalActionType: 'type="submit" onclick="submitNewClassroom()"',
                modalActionTypeText: 'Create classroom'
            };
            
            //console.log(Lists_Templates.modalTemplate(template));
            $('main').append(Lists_Templates.modalTemplate(template));
            
            $('select').material_select();
            
            
            $(this).attr('data-target', template.modalId);
            
            $('#' + template.modalId).openModal({dismissible : false});
            
            console.log('modal create classroom form created.');
            
            //$('.modal#' + template.modalId + ' a#createNewClassroomCard').bind('click', function (e) {
             //   e.preventDefault();
                
                //ajax post
            //    console.log('999999:-' + classroomid);
             //   
                //
          //  });
            
        });
        
        //put the form in a modal
        
    };
    
    //------------------------------
    
    var submitNewClassroom = function (str1, str2) {
        
        $('main').on('click', 'a#createNewClassroomCard', function (e) {
            e.preventDefault();
            
            console.log('submit event handler ready');
            
            str1 = $('.modal#createNewClassRoom').attr('id');
            str2 = 32;
            
            var newClassTitle = $('.modal#createNewClassRoom input#newClassroomName').val();
            var newClass_stream = $('.modal#createNewClassRoom select#newClassroomStream').val();
            var newClass_subject = $('.modal#createNewClassRoom select#newClassroomSubject').val();
            var studentsToAdd = $('.modal#createNewClassRoom .students').attr('data-selected-students');
            var totalStudents = $('.modal#createNewClassRoom .students').attr('data-total-students');
            
            console.log('class title: ' + newClassTitle);
            console.log('adding students : ' + studentsToAdd);
            
            //validate first
            if (newClassTitle !== '' && newClass_stream !== '' && newClass_subject !== '')
                {
                    
                var formResults = {
                    action : 'CreateClassroom',
                    classroomid : str2,
                    totalstudents: totalStudents,
                    studentids: studentsToAdd,
                    assignmentnumbers: '17 ',
                    classroomtitle : newClassTitle,
                    classroomstream : newClass_stream,
                    classroomsubject : newClass_subject

                };
                
                //ajax post
                var request = $.post( "classes/classroom_class.php", formResults, function (result) {

                    console.log(result);

                    if(result === 'true') {

                        var classroomTab = $('#classroomTab #classroomCardList');

                        var Result = Lists_Templates.classRoomCard(formResults);

                        classroomTab.prepend(Result);

                        //masonryGridInit();
                    } else {
                        
                        console.log('waiting');
                    
                    }
                    
                }, 'text');

            } else {
            
                console.log('empty form. Unable to create the class');

                $('#' + str1).closeModal();
                
                
            
                var errorMessage = '<span class="red-text name text-lighten-5">Error in creating the classroom. Kindly see if you have filled all inputs.</span>';
                
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(errorMessage, 5000, '', function() {
                    cleanOutModals();
                });
                
            }
            
            //var validationResult = validateInputs('createNewClassroomForm');

            //if (validationResult) {
                //this.createClassroomCard();

                $('#' + str1).closeModal();
                cleanOutModals();

            //} else {

              //  console.log('empty somewhere')
            //}

        });

    };
    
    //-------------------------------
    
    var editClassroomCard = function () {
        
        var self = $('a#editClassroom');
        
        //self.click(function (e) {
        $('main').on('click', 'a#editClassroom', function (e) {
            e.preventDefault();
            
            var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
            var cardColorClasses = $(this).parents('.card').attr('class').split('card');
            
            $(this).parents('.card').removeClass(cardColorClasses[1]);
            
            console.log(cardColorClasses[1]);
            
            $(this).parents('.card').addClass('grey z-depth-4 to-edit');
            
            var formTemplateVars = {
                optgroupname : 'Languages',
                optgroupname2 : 'English',
                subjectoption1 : 'Matmatics',
                subjectoption2 : 'thematics',
                subjectoption3 : 'Mamatics',
                subjectoption4 : 'Mathetics',
            };
            
            //append a modal then open it
            var formTemplate = Forms_Templates.editClassroomForm(formTemplateVars);

            //variables for the modal
            var template = {
                modalId: 'editClassRoom',
                templateHeader: 'Update Classroom',
                templateBody: formTemplate,
                modalActionType: 'type="submit" onclick="submitClassroomEdit()"',
                modalActionTypeText: 'Update classroom'
            };
            
            //console.log(Lists_Templates.modalTemplate(template));
            $('main').append(Lists_Templates.modalTemplate(template));
            
            $('select').material_select();
            
            
            $(this).attr('data-target', template.modalId);
            
            $('#' + template.modalId).openModal({dismissible : false});
            
            
            
            console.log('calling edit classroom functions');
        });
        
        //return false;
    };
    
    //--------------------------------
    
    var submitEdittedClassroom = function () {
        
        $('main').on('submit', 'form#editClassroomForm a#editClassroomCard', function () {
            
        
            str1 = $('.modal#editClassroomForm').attr('id');
            str2 = 32;
            
            var newClassTitle = $('.modal#createNewClassRoom input#newClassroomName').val();
            var newClass_stream = $('.modal#createNewClassRoom select#newClassroomStream').val();
            var newClass_subject = $('.modal#createNewClassRoom select#newClassroomSubject').val();
            var studentsToAdd = $('.modal#createNewClassRoom .students').attr('data-selected-students');
            var totalStudents = $('.modal#createNewClassRoom .students').attr('data-total-students');
            
            console.log('class title: ' + newClassTitle);
            console.log('adding students : ' + studentsToAdd);
            
            //validate first
            if (newClassTitle !== '' && newClass_stream !== '' && newClass_subject !== '')
                {
                    
                var formResults = {
                    action : 'CreateClassroom',
                    classroomid : str2,
                    totalstudents: totalStudents,
                    studentids: studentsToAdd,
                    assignmentnumbers: '17 ',
                    classroomtitle : newClassTitle,
                    classroomstream : newClass_stream,
                    classroomsubject : newClass_subject

                };
                
                //ajax post
                var request = $.post( "classes/classroom_class.php", formResults, function (result) {

                    console.log(result);

                    if(result === 'true') {

                        var classroomTab = $('#classroomTab #classroomCardList');

                        var Result = Lists_Templates.classRoomCard(formResults);

                        classroomTab.prepend(Result);

                        //masonryGridInit();
                    } else {
                        
                        console.log('waiting');
                    
                    }
                    
                }, 'text');

            } else {
            
                console.log('empty form. Unable to create the class');

                $('#' + str1).closeModal();
                
                
            
                var errorMessage = '<span class="red-text name text-lighten-5">Error in creating the classroom. Kindly see if you have filled all inputs.</span>';
                
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(errorMessage, 5000, '', function() {
                    cleanOutModals();
                });
                
            }
                
            
        });
    };
    
    //--------------------------------
    
    var addStudentsInClassroom = function () {
        console.log('students will be added');
        
        var checkboxEl = 'input#addStudentsToClassroom';
        var checkedCheckboxEl = 'input#addStudentsToClassroom:checked';
        var modal_id = 'NewClassStudentList';
                        
        var main = $('main');
        
        main.on('change', checkboxEl, function (e) {
        
            e.preventDefault();
            
            var hook = $('.student-list');
            
            console.log('V- ' + $(checkboxEl).val());
            
            console.log('length- ' + $(checkedCheckboxEl).length);
            
            if($(checkedCheckboxEl).length > 0) {//checked
                
                //remove existing esomo modal
                cleanOutModal('#esomoModal' + modal_id);
                
                console.log('adding list');
                
                var subject = $('select#newClassroomSubject').val();
                
                //console.log('args-' + subject + stream);
                
                $.get('handlers/db_info.php', {"action" : "GetAllStudents"}, function (result) {
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

                        /*
                        autocompletedata = jQuery.parseJSON(autocompletedata);

                        $('input.autocomplete').autocomplete({
                            data: autocompletedata
                        });

                        console.log(autocompletedata);
                        */
                        
                        var optionslist = output;
                        
                        var formList = Forms_Templates.makeStudentFormList(optionslist);
                        
                        //open the esomo modal Template
                        //append the list to esomo modal
                        
                        var modal_header = 'Add students to the classroom';
                        
                        var modal_body = formList;
            
                        var studentListModal = loadEsomoModal(modal_id, modal_header, modal_body);
                        
                        $('.modal#esomoModal' + modal_id).openModal({dismissible : false});
                        
                        console.log(formList);
                        
                        //Init functions needed for the esomo actions
                        updateEsomoModalProgress(modal_id);
                        
                        var action = 'morph-in';
                        
                        $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-close').bind('click', function(e) {
                            
                         
                            addToForm(action, hook, modal_id); //when add students is clicked//
                        
                        }); //when add students is clicked//
                        
                        
                    }

                })
                  .success( function (result) {
                    
                    console.log('success');
                    
                }, 'json');
                
            } else if ($(checkedCheckboxEl).length < 1) {
            
                cleanOutModal('#esomoModal' + modal_id);
                
                console.log('removing list');
            
                hook.fadeOut(300, function () {
                    
                    $(this).html(' ');
                    
                    $(this).show();
                    
                });
                
            }
            
        });
        
    };
    
    //--------------------------------
    
    var addToForm = function (action, hook, modal_id) {

        console.log('Function Inited');
        
        console.log(modal_id);

        console.log('adding to form now');

        if (action != 'undefined') {

            //getting the list

        }

        var totalSelected = $('#esomoModal' + modal_id + ' .list').find('input[type="checkbox"]:checked').length;

        var selectedArrayResult = $('#esomoModal' + modal_id + ' .list').find('input[type="checkbox"]:checked').map(function(){
            return $(this).attr('id');
        }).get(); // <----

        var selectedStringFormat = selectedArrayResult.toString();
        
        selectedStringFormat += ',';//for database' sake, let the string end with a commar*
        
        if (typeof totalSelected === 'number' && totalSelected > 0) {

            hook.append('<div class="col s12 brookhurst-theme-primary students lighten-2 card-panel " data-total-students="' + totalSelected + '" data-selected-students="' + selectedStringFormat + '"><p class="white-text php-data">A total of ' + totalSelected + ' students to be added in the classroom.<p></div>');

            console.log(totalSelected);

            cleanOutModal('#esomoModal' + modal_id);

            return true;

        } else {

            console.log(totalSelected);

            cleanOutModal('#esomoModal' + modal_id);

            return null;

        }
        
    };
    
    //--------------------------------
    
    var masonryGridInit = function (str) {
        var masonryContainer = 'classroomCardList';
        
        var $container = $('#' + masonryContainer);
                
                $container.masonry({
                    columnWidth: '.card-col',
                    itemSelector: '.col'
                });
    };
    
    //--------------------------------
    
    var loadEsomoModal = function (modal_id, modal_header, modal_body) {
        
        var args = {
            modalId: modal_id,
            modalActionType: {
                actionClose: 'close-hivi',
                actionAdd: 'add-hivi'
            },
            templateHeader: modal_header,
            templateBody: modal_body
            
        };
        
        var esomoModal = Lists_Templates.esomoModalTemplate(args);
        
        $('main').append(esomoModal);
        
    };
    
    //--------------------------------
    
    var updateEsomoModalProgress = function (modal_id) {
        
        console.log('progress bar event listener fired on modal id: ' + modal_id);
        
        
                
        $('main').on('change', 'input[type="checkbox"]', function (o) {
            
            var totalCount = $('#' + modal_id).find('input[type="checkbox"]:checked').length;
            
            console.log('progress bar event listener on ' + totalCount + ' checkboxes.');
            
        });
    };
    
    //--------------------------------
    
    this.__construct();
    
};