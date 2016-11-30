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
        submitEdittedClassroom();
        
    };
    
    //----------------------------
    //--------------------------------  CLASSROOM EVENTS
    //--------------------------------
    
    var viewStudentsinClassroom = function () {
        
        //ajax get
        //load modal template
        
        $('main').on('click', '#classroomCardList a#openStudentsClassList', function (e) {
            
            var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
        
            console.log('fetching students in classroom id:' + classroomId);
            
        });
        
        

    };
    
    //----------------------------
    
    var viewAssignmentsinClassroom = function () {
        
        //ajax get
        //load modal template
        
        $('main').on('click', '#classroomCardList .card-col a#openAssignmentsClassList', function (e) {
            
            var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
        
            console.log('fetching Assignments in classroom id:' + classroomId);
            
        });
      
    };
    
    //----------------------------
    
    var createClassroom = function () {
        
        //load form template
        //get subjects select list
        //load modal template
        //append form template to modal template
        //open modal
        
        $('a#createClassroom').click(function (e) {
            e.preventDefault();
            
            cleanOutModals();//remove any modal if exists
            
            console.log('fetching form template');
            
            var totalOutput = '';
            
            $.get("handlers/db_info.php", {"action" : "GetAllSubjects"}, function (result) {
                
                console.log('creating a subjects dropdown.');
                
                //loop
                //arranging a select template accoding to subject category
                
                var output = '';
                var count = 0;

                var optgroup = new Array();
                
                for (var key in result) {

                    if ( typeof optgroup[result[key].category] !== 'string' ) {
                        
                        optgroup[result[key].category] = Forms_Templates.formSelectTemplate(result[key]);
                        
                    } else {
                        
                        output = Forms_Templates.formSelectTemplate(result[key]);

                        optgroup[result[key].category] += output;
                        
                    }

                }

                output += '';

                var totalOutput = '';
                
                var obj = {
                    category : '',
                    categorylist : ''
                };
                
                for (var i in optgroup) {
                    console.log(i);
                    
                    obj.category = i;
                    obj.categorylist = optgroup[i];
                    
                    totalOutput += Forms_Templates.formOptgroupTemplate(obj);
                    
                }


                totalOutput +='';

                $.get("handlers/db_info.php", {"action" : "GetAllStreams"}, function (result2) {

                    var totalOutput2 = '';
                    
                    for(var u in result2) {
                        
                        totalOutput2 += Forms_Templates.formSelectTemplate(result2[u]);
                        
                    }
                    //get list of subjects
                    var formTemplateVars = {
                        subjectoptions: totalOutput,
                        streamoptions: totalOutput2

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

                    $('select').material_select();

                    $(this).attr('data-target', template.modalId);

                    $('#' + template.modalId).openModal({dismissible:false});

                    console.log('modal create classroom form created.');

                    /*
                    $('.modal#esomoModal' + template.modalId + ' input#newClassroomName').bind('blur', function (e) {

                        var ifExistsResult = searchIfClassNameExists($(this).val());

                        console.log('blur');

                        if (ifExistsResult === 1) {
                            //exists
                            //append warning to that input
                            var warningText = '<p class="orange-text text-accent-1 col s9">Classroom ' + $(this).val() + 'exists, continue?</p><a class="btn btn-flat>Okay</a>"';

                            $('.modal#esomoModal' + template.modalId + ' input#newClassroomName').parent().append(warningText);

                        }

                    });
                    */

                }, 'json');

            }, 'json');
            
        });
        
    };
    
    //------------------------------
    
    var submitNewClassroom = function (str1, str2) {
        
        //get form variables
        //validate the variables
        //submit the variables
        //prepend the new classroom card to the list
        //initialize tooltip
        //close modal
        
        $('main').on('click', 'a#createNewClassroomCard', function (e) {
            e.preventDefault();
            
            console.log('submit event handler ready');
            
            var str1 = $('.modal#createNewClassRoom').attr('id');
            
            var str2 = Materialize.guid();//Generate class-id
            
            var classes = $('.modal#createNewClassRoom .card-color-list input[type="radio"]:checked').parent().children('label').attr('class');
            var newClassTitle = $('.modal#createNewClassRoom input#newClassroomName').val();
            var newClass_stream = $('.modal#createNewClassRoom select#newClassroomStream').val();
            var newClass_subject = $('.modal#createNewClassRoom select#newClassroomSubject').val();
            var newClass_streamname = $('.modal#createNewClassRoom select#newClassroomStream').find('option:selected:not(:disabled)').text();
            var newClass_subjectname = $('.modal#createNewClassRoom select#newClassroomSubject').find('option:selected:not(:disabled)').text();

            console.log($('.modal#createNewClassRoom .students').attr('data-total-students'));
            console.log(classes);

            if (typeof classes === 'undefined') {
                
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
                    classroomid : str2,
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

                        var classroomTab = $('#classroomTab #classroomCardList');

                        var Result = Lists_Templates.classRoomCard(formResults);

                        if ( $('.no-data-message').length > 0 ) {
                            
                            $('.no-data-message').remove();
                            
                            $('#classroomTab').append(Lists_Templates.cardListContainer);
                            
                            //recall classroomTab after appending
                            classroomTab = $('#classroomTab #classroomCardList');

                            /*

                            var $main = $("main"),  
                            
                            ajaxLoad = function(html) {
                                
                                document.title = html
                                    .match(/<div>(.*?)<\/div>/)[0]
                                    .trim()
                                    .decodeHTML();

                                init();
                            },

                            loadPage = function(href) {
                                $main.load(href + " main>*", ajaxLoad);
                            };
                            
                            var href = '#classroomCardList';
                            
                            loadPage(href);
                            
                            */

                            classroomTab.prepend(Result);
                            
                        } else {
                            
                            classroomTab.prepend(Result);
                            
                        }
                        

                        $('.tooltipped').tooltip({delay: 50});
                        
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
                Materialize.toast(errorMessage, 5000, '', function () {
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
            
            /*1*/var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
            
            console.log(classroomId);
            
            /*2*/var cardColorClasses = $(this).parents('.card').attr('class').split('card');
            
            /*2*/var classroomData = $.get("");
            
            /*3*/$(this).parents('.card').removeClass(cardColorClasses[1]);
            
            console.log(cardColorClasses[1]);
            
            /*3*/$(this).parents('.card').addClass('grey z-depth-4 to-edit');
            
            var formTemplateVars = {
                optgroupname : 'Languages',
                optgroupname2 : 'English',
                subjectoption1 : 'Matmatics',
                subjectoption2 : 'thematics',
                subjectoption3 : 'Mamatics',
                subjectoption4 : 'Mathetics'
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
        
        //get the form data                     1
        //validate the form data                2
        //post the data to db (update)          3
        //Change the highlighted card data      4
        //close modal                           5
        //clean out modals from DOM             6
        //remove the highlighted card style     7
        
        $('main').on('click', 'form#editClassroomForm a#editClassroomCard', function (e) {
            e.preventDefault();
            
            /*1*/
            var str1 = $('.modal#editClassroom').attr('id');
            var str2 = 2;
            
            var currentStudentNumbers = 10;
            var edittedClassTitle = $('.modal#editClassroom input#newClassroomName').val();
            var edittedClass_stream = $('.modal#editClassroom select#newClassroomStream').val();
            var edittedClass_subject = $('.modal#editClassroom select#newClassroomSubject').val();
            var studentsToAdd = $('.modal#editClassroom .students').attr('data-selected-students');
            var totalStudents = $('.modal#editClassroom .students').attr('data-total-students') + currentStudentNumbers;
            
            console.log('class title: ' + edittedClassTitle);
            console.log('adding students : ' + studentsToAdd);
            
            /*2*/
            //validate first
            if (newClassTitle !== '' && newClass_stream !== '' && newClass_subject !== '')
                {
                    
                var formResults = {
                    action : 'UpdateClassroomInfo',
                    subjectid : str2,
                    totalstudents: totalStudents,
                    studentids: studentsToAdd,
                    assignmentnumbers: '17 ',
                    classroomtitle : edittedClassTitle,
                    classroomstream : edittedClass_stream,
                    classroomsubject : edittedClass_subject

                };
                
            /*3*/
                //ajax post
                var request = $.post( "classes/classroom_class.php", formResults, function (result) {

                    console.log(result);

                    if(result === 'true') {

                        var classroomTab = $('#classroomTab #classroomCardList');

            /*4*/
                        
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
                        
                        
                        autocompletedata = jQuery.parseJSON(autocompletedata);

                        console.log(autocompletedata);
                        
                        $('input#searchStudentFormList').autocomplete({
                            data: autocompletedata
                        });

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
    //--------------------------------  END OF CLASSROOM EVENTS AND FUNCTIONS
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
    
    var searchIfClassNameExists = function (className) {
        
        console.log('checking if class name ' + className + ' exists');
        
        return 1;
        
    };
    
    //--------------------------------
    //--------------------------------  MODAL EVENTS AND FUNCTIONS
    //--------------------------------
    
    var updateEsomoModalProgress = function (modal_id) {
        
        console.log('progress bar event listener fired on modal id: ' + modal_id);
        
        $('main').on('change', 'input[type="checkbox"]', function (o) {
            
            var totalCount = $('#' + modal_id).find('input[type="checkbox"]:checked').length;
            
            console.log('progress bar event listener on ' + totalCount + ' checkboxes.');
            
        });
    };
    
    //--------------------------------
    
    var closeModalsEvent = function () {
        
        $('main').on('click', '.modal a#modalFooterCloseAction.modal-close', function (e) {
            
            console.log('removing modal from DOM');
        
            $(this).parents('.modal').remove();
            
        });
        
        //$('a#createClassroom').attr('data-target', '');
            
        //$('.modal').remove();

    };
    
    //----------------------------      FUNCTIONS
    
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
    
    var cleanOutModal = function (str) {
        
        console.log('cleaning out modal' + str);
        
        $('.modal' + str).remove();

        console.log($('main').find('.modal' + str).length);
        
    };
    
    //----------------------------
    
    var cleanOutModals = function () {
        
        console.log('cleaning out modals');
        
        //$('a#createClassroom').attr('data-target', '');
        
        $('.modal ').remove();

    };
    
    //--------------------------------
    //--------------------------------  END OF MODAL EVENTS AND FUNCTIONS
    //--------------------------------
    
    this.__construct();
    
};