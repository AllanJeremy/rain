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
        viewStudentsinClassroom();
        viewAssignmentsinClassroom();
        
    };
    
    //----------------------------
    //--------------------------------  CLASSROOM EVENTS
    //--------------------------------
    
    var studentExists = function (admNo, action) {

        console.log(admNo);
        console.log(action);
        
        return $.ajax({
                url: "handlers/db_info.php",
                data: {
                    "action": action,
                    "adm_no": admNo
                },
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    return data;
                }
                });
    };
    
    var viewStudentsinClassroom = function () {
        
        //ajax get
        //load modal template
        
        $('main').on('click', '#classroomCardList a#openStudentsClassList', function (e) {
            
            var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
        
            $.get("handlers/db_info.php", {"action":"GetAllStudentsInClass", "class_id":classroomId}, function (result) {
                
                console.log('fetching students in classroom id:' + classroomId);
                
                console.log(result);
                
                if (!result.trim()) {
                    // is empty or whitespace
                    console.log('empty. No students found');

                    var errorMessage = '<span>Sorry, there are no students found in this classroom.</span>';

                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(errorMessage, 5000, '', function () {
                        
                    });

                } else {
                    
                    result = cleanArray(result.split(','));
                    
                    console.log('Students found');
                    console.log(result);

                    var listVars = {
                        "id":"",
                        "name":"",
                    };
                    
                    var list = '';
                    var admNo = '';
                    var h = Array();
                    
                    $.each(result, function(i, v) {
                        
                        admNo = v;
                        
                        
                        h[i] = studentExists(admNo, "StudentIdExists").responseJSON;
                            /*.done(function (r) {
                                console.log(r);
                                console.log('success');
                            
                                return r;
                                listVars.id = r.adm_no;
                                listVars.name = r.full_name;

                                console.log(listVars);

                                list += Lists_Templates.studentTableList(listVars);

                                //console.log(list);
                            
                                return list;
                            
                            })
                            .fail(function (r) {

                                console.log('error');
                            });
*/
                        
                    });
                    
                    list += '';
                    //h = JSON.parse(h);
                    console.log(h);
                    console.log(typeof h);
                    console.log(h[0].adm_no);
                    console.log(list);
                    var listTemplate = {
                        "listData" : list
                    };
                    
                    var listData = Lists_Templates.studentTable(listTemplate);
                    
                    //variables for the modal
                    var template = {
                        modalId: 'ClassRoomStudents',
                        templateHeader: 'Students in the classroom',
                        templateBody: listData,
                    };
                    
                    $('main').append(Lists_Templates.modalTemplate(template));

                    $('#' + template.modalId).openModal({dismissible:false});

                    console.log('modal students classroom list created.');

                }
                
            });
            
        });
      
    };
    
    //----------------------------
    
    var viewAssignmentsinClassroom = function () {
        
        //ajax get
        //load modal template
        //open modal
        
        $('main').on('click', '#classroomCardList a#openAssignmentsClassList', function (e) {
            
            var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
            
            $.get("handlers/db_info.php", {"action":"GetTeacherAssInClass", "class_id":classroomId}, function (result) {
                
                console.log('fetching Assignments in classroom id:' + classroomId);
                
                if (!result.trim()) {
                    // is empty or whitespace
                    console.log('empty. No assignments found');

                    var errorMessage = '<span>Sorry, there are no assignments found in this classroom.</span>';

                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(errorMessage, 5000, '', function () {
                        
                    });

                } else {

                    result = cleanArray(result.split(','));
                    
                    console.log('Students found');

                    var listTemplateVars = {
                        "assignment_id":""
                    }
                    
                    var listTemplate = Lists_Templates.createClassroomForm(listTemplateVars);

                    //variables for the modal
                    var template = {
                        modalId: 'ClassRoomAssignments',
                        templateHeader: 'Assignments in the classroom',
                        templateBody: formTemplate,
                        modalActionType: '',
                        modalActionTypeText: 'Okay'
                    };
                    
                    
                    
                }
                
            });
            
            
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
        //initialize classroom events
        //close modal
        
        $('main').on('click', 'a#createNewClassroomCard', function (e) {
            e.preventDefault();
            
            console.log('submit event handler ready');
            
            var str1 = $('.modal#createNewClassRoom').attr('id');
            
            var str2 = $('#classroomCardList .card-col').first().attr('data-classroom-id');//Get the class-id of the latest card then add +1
            
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
            
            var self = $(this);
            
            /*1*/var classroomId = self.parents('.card-col').attr('data-classroom-id');
            
            self.parents('.card-col').attr('data-classroom-id');
            
            console.log(classroomId);
            
            $.get("handlers/db_info.php", { "action" : "ClassroomExists", "class_id" : classroomId }, function (classroomData) {
                
                console.log(classroomData);

                var resultData = '';
                
                for (var s in classroomData) {
                    console.log(s);
                    
                    console.log(classroomData[s].classes);
                    
                    resultData = classroomData[s];
                    
                }
                
                localStorage.setItem("cardColor", resultData.classes);
                localStorage.setItem("selectedStudents", resultData.selectedStudents);
                localStorage.setItem("cardId", classroomId);
            
                /*3*/self.parents('.card').removeClass(resultData.classes);
                
                /*3*/self.parents('.card').addClass('grey z-depth-4 to-edit');
                
                cleanOutModals();//remove any modal if exists

                console.log(resultData);

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

                        //console.log(i);

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

                        var formTemplate = Forms_Templates.editClassroomForm(formTemplateVars);

                        //variables for the modal
                        var template = {
                            classes: resultData.classes,
                            modalId: 'editClassRoom',
                            templateHeader: 'Edit Classroom',
                            templateBody: formTemplate,
                            modalActionType: 'type="submit"',
                            modalActionTypeText: 'Update classroom'
                        };

                        //load the modal in the DOM
                        $('main').append(Lists_Templates.modalTemplate(template));

                        $('select').material_select();

                        $(this).attr('data-target', template.modalId);

                        $('#' + template.modalId).openModal({dismissible:false});

                        //load current class data to the form
                        console.log(resultData.classes);
                        
                        $('main .modal#' + template.modalId + ' .card-color-list label.' + resultData.classes).parent().children('input[type="radio"]').prop('checked', true);
                        
                        $('main .modal#' + template.modalId + ' input#editClassroomName').val(resultData.classname);
                        
                        $('main .modal#' + template.modalId + ' select#editClassroomStream').val(resultData.selectedStream);
                        
                        $('main .modal#' + template.modalId + ' select#editClassroomSubject').val(resultData.selectedSubject);
                        
                        Materialize.updateTextFields();
                        
                        console.log('modal edit classroom form created.');

        
                        if(resultData.selectedStudents) {
                        
                            
                            var previouslySelectedStudents = resultData.selectedStudents;
                            
                            var totalSelected = previouslySelectedStudents.split(',').length - 1;
                            
                            $('#' + template.modalId + ' .student-list').append('<div class="col s12 brookhurst-theme-primary previous students lighten-2 card-panel " data-total-students="' + totalSelected + '" data-selected-students="' + previouslySelectedStudents + '"><p class="white-text php-data">A total of ' + totalSelected + ' students are in the classroom.<p></div>');
                        
                        }
                        
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
            var self = $(this);
            
            var str1 = $('.modal#editClassRoom').attr('id');
            
            var str2 = Materialize.guid();//Generate class-id
            
            var classes = $('.modal#editClassRoom .card-color-list input[type="radio"]:checked').parent().children('label').attr('class');
            var newClassTitle = $('.modal#editClassRoom input#editClassroomName').val();
            var newClass_stream = $('.modal#editClassRoom select#editClassroomStream').val();
            var newClass_subject = $('.modal#editClassRoom select#editClassroomSubject').val();
            var newClass_streamname = $('.modal#editClassRoom select#editClassroomStream').find('option:selected:not(:disabled)').text();
            var newClass_subjectname = $('.modal#editClassRoom select#editClassroomSubject').find('option:selected:not(:disabled)').text();

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
            if (newClassTitle !== '' && newClass_stream !== '' && newClass_subject !== '')
                {
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
                    var Result = Lists_Templates.classRoomCardDataUpdate(formResults);
        /*5*/
                    var hook = $('.card.to-edit').parent('.card-col');
                    
                    $('.card.to-edit').remove();
                    
                    hook.append(Result);
                    
                    //classroomTab.prepend(Result);
        /*6*/
                    $('#' + str1).closeModal();
        /*7*/
                    cleanOutModals();
                    
                } else {

                    console.log('waiting');

                }

            }, 'text');

            } else {
            
                console.log('empty form. Unable to create the class');

                $('#' + str1).closeModal();
                
                var errorMessage = '<span class="red-text name text-lighten-5">Error in updating the classroom. Kindly see if you have filled all inputs.</span>';
                
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
        
        $('main').on('click', '.modal a#modalFooterCloseAction.modal-close', function () {
            
            console.log('removing modal from DOM');
        
            $(this).parents('.modal').remove();
    
            var cardColor = localStorage.getItem("cardColor");
            
            $('.to-edit').removeClass('grey z-depth-4');
            $('.to-edit').addClass(cardColor)
            $('.card').removeClass('to-edit');
            
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