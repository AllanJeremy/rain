/*global $, jQuery, alert, console*/

var AssignmentEvents = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('assignment events created');
        
        //global inits
        cleanOutModals();
        
        //assignment inits
        addClassroomToAssignment();
        submitNewAssignment();
    };
    
    //------------------------------
    //--------------------------------  ASSIGNMENT EVENTS AND FUNCTIONS
    //--------------------------------
    
    var addClassroomToAssignment = function () {
        
        var checkboxEl = 'input#addClassroomToAssignment, input#addMoreClassroomToAssignment';
        var checkedCheckboxEl = 'input#addClassroomToAssignment:checked, input#addMoreClassroomToAssignment:checked';
        var modal_id = 'NewAssClassroomList';
                        
        var main = $('main');
        
        main.on('change', checkboxEl, function (e) {
        
            console.log('classrooms adding function on');
        
            e.preventDefault();
            
            var hook = $('.classroom-list');
            
            console.log('V- ' + $(checkboxEl).val());
            console.log('V- ' + $(checkboxEl).attr('name'));
            console.log('V- ' + $(checkboxEl).attr('id'));
            
            var action = $(checkboxEl).val();
            
            console.log('length- ' + $(checkedCheckboxEl).length);
            
            if($(checkedCheckboxEl).length > 0) {//checked
                
                //remove existing esomo modal
                cleanOutModal('#esomoModal' + modal_id);
                
                console.log('adding list');
                
                if( $(checkboxEl).val() === "GetSpecificTeacherClassrooms" ) {
                    


                    $.get('handlers/db_info.php', {"action" : action}, function (result) {
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

                                result[key].totalstudents = result[key].selectedStudents.split(',').length - 1;
                                
                                output += Lists_Templates.classroomTableList(result[key]);

                                
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


                            var list = {
                                
                                "listData" : output
                            
                            };

                            var List = Lists_Templates.classroomTable(list);

                            //open the esomo modal Template
                            //append the list to esomo modal

                            var modal_header = 'Send assignment to classrooms';

                            var modal_body = List;

                            var classroomListModal = loadEsomoModal(modal_id, modal_header, modal_body);
                            
                            $('input#searchStudentFormList').autocomplete({
                                data: autocompletedata
                            });

                            $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                            console.log(List);

                            var action2 = 'morph-in';

                            $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-close').bind('click', function(e) {

                                addToForm(action2, hook, modal_id); //when add students is clicked//

                            }); //when add students is clicked//


                        }

                    })
                      .success( function (result) {

                        console.log('success');

                    }, 'json');
                
                } else if ( $(checkboxEl).val() === "GetAllStudents" ) {
                    
                    


                    $.get('handlers/db_info.php', { "action" : action/*, "class_id" : localStorage.getItem("cardId")*/ }, function (result) {
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


                            var optionslist = output;

                            var formList = Forms_Templates.makeStudentFormList(optionslist);

                            //open the esomo modal Template
                            //append the list to esomo modal

                            var modal_header = 'Add students to the classroom';

                            var modal_body = formList;

                            var studentListModal = loadEsomoModal(modal_id, modal_header, modal_body);
                            
                            $('input#searchStudentFormList').autocomplete({
                                data: autocompletedata
                            });

                            $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                            console.log(formList);

                            //Init functions needed for the esomo actions
                            updateEsomoModalProgress(modal_id);

                            var action2 = 'morph-in';

                            $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-close').bind('click', function(e) {

                                addToForm(action2, hook, modal_id); //when add students is clicked//

                            }); //when add students is clicked//


                        }

                    })
                      .success( function (result) {

                        console.log('success');

                    }, 'json');
                }
  
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
    
    var submitNewAssignment = function (str1, str2) {
        
        //get form variables
        //validate the variables
        //submit the variables
        //prepend the new classroom card to the list
        //initialize tooltip
        //initialize classroom events
        //close modal
        
        $('main').on('click', '.main-tab#createAssignmentsTab a#createNewAssignment', function (e) {
            e.preventDefault();
            
            
            console.log('new assignment submit event handler ready');
            
            if ($('#assignmentCardList .card-col').first().attr('data-classroom-id')) {
                var str2 = $('#assignmentCardList .card-col').first().attr('data-classroom-id');
            } else {
                var str2 = (0-1);
            }
            
            var newAssignmentTitle = $('#createAssignmentsTab form#createAssignmentForm input#newAssignmentName').val();
            var newAssignmentDescription = $('#createAssignmentsTab form#createAssignmentForm textarea#assignmentInstructions').val();
            var newAssignmentCanComment = $('#createAssignmentsTab form#createAssignmentForm  .classroom-list .classrooms input[type="checkbox"]:checked#canComment').val();
            var newAssignmentDueDate = $('#createAssignmentsTab form#createAssignmentForm input#assDueDate').val();
            var newAssignmentResources = $('#createAssignmentsTab form#createAssignmentForm .file-field input[type="file"]').val();

            
            console.log(newAssignmentTitle);
            console.log(newAssignmentDescription);
            console.log(newAssignmentCanComment);
            console.log(newAssignmentDueDate);
            console.log(newAssignmentResources);
            
            if (newAssignmentCanComment === 'on') {
                
                newAssignmentCanComment = 1;
                
            } else {
                
                newAssignmentCanComment = 0;
                
            }
            
            console.log($('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').attr('data-selected-classrooms'));
         
            if (typeof $('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').attr('data-selected-classrooms') === 'undefined') {
            
                var newAssignmentClassIds = 0;

            } else {

                var newAssignmentClassIds = $('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').attr('data-selected-classrooms');

            }

            if (typeof $('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').attr('data-total-classrooms') === 'undefined') {

                var totalClassrooms = 0;

            } else {

                var totalClassrooms = $('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').attr('data-total-classrooms');

            }
            
            console.log('ass title: ' + newAssignmentTitle);
            console.log('adding classrooms : ' + newAssignmentClassIds);
            console.log('total classrooms to add : ' + totalClassrooms);
            console.log('cancomment : ' + newAssignmentCanComment);
            console.log('ass description : ' + newAssignmentDescription);
            console.log('resources : ' + newAssignmentResources);
            
            //validate first
            if (newAssignmentTitle !== '' && newAssignmentDescription !== '' && newAssignmentDueDate !== '') {
                    
                var formResults = {
                    action : 'CreateAssignment',
                    totalClassrooms: totalClassrooms,
                    assignmenttitle : newAssignmentTitle,
                    assignmentdescription : newAssignmentDescription,
                    classids : newAssignmentClassIds,
                    duedate : newAssignmentDueDate,
                    attachments : newAssignmentResources,
                    maxgrade : '',
                    cancomment : newAssignmentCanComment,

                };
                    
                //ajax post
                var request = $.post("classes/teacher.php", formResults, function (result) {

                    console.log(result);

                    if (result === 'true') {

                        var assignmentTab = $('#assignmentTab #assignmentCardList');
                        
                        var Result = Lists_Templates.teacherAssignmentCard(formResults);

                        if ( $('.no-data-message').length > 0 ) {
                            
                            $('.no-data-message').remove();
                            
                            $('#assignmentTab').append(Lists_Templates.assignmentCardListContainer);
                            
                            //recall classroomTab after appending
                            assignmentTab = $('#assignmentTab #assignmentCardList');

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
                
                var errorMessage = '<span class="red-text name text-lighten-5">Error in creating the assignment. Kindly see if you have filled all inputs.</span>';
                
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

    //--------------------------------
    
    var addToForm = function (action2, hook, modal_id) {

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
                .split(' ').join('');
            
            console.log(hookType);
            
            if(hookType === 'student-list') {//classroom form
                if ($('.modal#editClassRoom .students').length > 0) {

                    var previousTotal = $('.modal#editClassRoom .students').attr('data-total-students');

                    console.log(selectedStringFormat);
                    selectedStringFormat += $('.modal#editClassRoom .students').attr('data-selected-students');
                    console.log(selectedStringFormat);
                    selectedStringFormat = cleanArray(selectedStringFormat.split(','));
                    console.log(selectedStringFormat);
                    selectedStringFormat = jQuery.unique( selectedStringFormat );
                    console.log(selectedStringFormat);
                    selectedStringFormat = selectedStringFormat.toString();
                    console.log(selectedStringFormat);
                    selectedStringFormat += ',';
                    console.log(selectedStringFormat);
                    console.log(selectedStringFormat.split(',').length);


                    $('.modal#editClassRoom .students').remove();

                    hook.append('<div class="col s12 brookhurst-theme-primary students lighten-2 card-panel " data-total-students="' + (selectedStringFormat.split(',').length - 1) + '" data-selected-students="' + selectedStringFormat + '"><p class="white-text php-data">' + previousTotal + ' students are already in the classroom<br>' + ((selectedStringFormat.split(',').length - 1) - parseInt(previousTotal)) + ' more students will be added to the classroom on submit.<p></div>');

                } else {
                    
                    hook.append('<div class="col s12 brookhurst-theme-primary students lighten-2 card-panel " data-total-students="' + totalSelected + '" data-selected-students="' + selectedStringFormat + '"><p class="white-text php-data">A total of ' + totalSelected + ' students to be added in the classroom.<p></div>');
                    
                }
                
            } else if(hookType === 'classroom-list') {//student form

                if ($('.modal#editAssignment .classrooms').length > 0) {

                    var previousTotal = $('.modal#editAssignment .classrooms').attr('data-total-classrooms');

                    console.log(selectedStringFormat);
                    selectedStringFormat += $('.modal#editAssignment .classrooms').attr('data-selected-classrooms');
                    console.log(selectedStringFormat);
                    selectedStringFormat = cleanArray(selectedStringFormat.split(','));
                    console.log(selectedStringFormat);
                    selectedStringFormat = jQuery.unique( selectedStringFormat );
                    console.log(selectedStringFormat);
                    selectedStringFormat = selectedStringFormat.toString();
                    console.log(selectedStringFormat);
                    selectedStringFormat += ',';
                    console.log(selectedStringFormat);
                    console.log(selectedStringFormat.split(',').length);


                    $('.modal#editAssignment .classrooms').remove();

                    hook.append('<div class="col s12 brookhurst-theme-primary classrooms lighten-2 card-panel " data-total-classrooms="' + (selectedStringFormat.split(',').length - 1) + '" data-selected-classrooms="' + selectedStringFormat + '"><p class="white-text php-data">' + previousTotal + ' classrooms have the assignment<br>' + ((selectedStringFormat.split(',').length - 1) - parseInt(previousTotal)) + ' more classrooms will receive this assignment on submit.<p><p><input id="canComment" class="filled-in" type="checkbox"><label for="canComment">Allow students to comment</label></p><br></div>');

                } else {

                    hook.append('<div class="col s12 brookhurst-theme-primary classrooms lighten-2 card-panel " data-total-classrooms="' + totalSelected + '" data-selected-classrooms="' + selectedStringFormat + '"><p class="white-text php-data">A total of ' + totalSelected + ' classrooms to receive the assignment.</p><p><input id="canComment" class="filled-in" type="checkbox"><label for="canComment">Allow students to comment</label></p><br></div>');

                }

            }
            
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
    
    var cleanArray = function (actual) {
        
        var newArray = new Array();
        
        for (var i = 0; i < actual.length; i++) {
            
            if (actual[i]) {
                
                newArray.push(actual[i]);
                
            }
            
        }
        
        return newArray;
        
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
    //--------------------------------  MODAL EVENTS AND FUNCTIONS
    //--------------------------------
    
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
        
        console.log('cleaning out assignments dialogs');
        
        //$('a#createClassroom').attr('data-target', '');
        
        $('.modal ').remove();

    };
    
    //--------------------------------
    //--------------------------------  END OF MODAL EVENTS AND FUNCTIONS
    //--------------------------------
    
    this.__construct();
    
};