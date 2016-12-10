/*global $, jQuery, alert, console*/

var ClassroomEvents = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('classroom events created');
        
        //global inits
        cleanOutModals();
        
        //classroom inits
        createClassroom();
        editClassroomCard();
        submitNewClassroom();
        addStudentsInClassroom();
        submitEdittedClassroom();
        viewStudentsinClassroom();
        viewAssignmentsinClassroom();
    };
    
    //----------------------------
    //--------------------------------  CLASSROOM EVENTS
    //--------------------------------
    
    var viewStudentsinClassroom = function () {
        
        //ajax get
        //load modal template
        
        $('main').on('click', '#classroomCardList a#openStudentsClassList', function (e) {
            
            var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
        
            $.get("handlers/db_info.php", {"action":"GetAllStudentsInClass", "class_id":classroomId}, function (result) {
                
                console.log('fetching students in classroom id:' + classroomId);
                
            }).success(function (result) {
                
                console.log(result);
                
                if (!result.trim() || result === '0') {
                    // is empty or whitespace
                    console.log('empty. No students found');

                    var errorMessage = '<span>Sorry, there are no students found in this classroom.</span>';

                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(errorMessage, 5000, '', function () {
                        
                    });

                } else {
                    
                    result = result.replace(/0,/, '');
                    console.log(result);
                    result = cleanArray(result.split(','));
                    
                    console.log('Students found');
                    console.log(result);

                    var listVars = {
                        "id":"",
                        "name":"",
                    };
                    
                    var list = '';
                    var admNo = '';
                    var XHRs = [];
                    var ajaxObjectResult = '';
                    
                    $.each(result, function(i, v) {
                        
                        admNo = v;
                        
                        console.log(v);
                        
                        /*XHRs.push($.get("handlers/db_info.php", {"action": "StudentIdExists", "adm_no": admNo}, function (r) {
                                
                                //console.log(l);
                                //console.log(l.username);
                            }
                            
                        ));*/
                        
                        
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

                                list += Lists_Templates.studentTableList(listVars);

                                k++;
                                
                            } else {
                            
                                console.log('last one')
                                
                                listVars.id = x.adm_no;
                                listVars.name = x.full_name;

                                list += Lists_Templates.studentTableList(listVars);

                                //Continue with the rest of the functions
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
                    
                    
                    //console.log(list);
                    
                    
                    /*ajaxObjectResult = $.when(XHRs).then(function(x) {

                        for (x in XHRs) {

                            console.log('done');
                            console.log(x);

                            listVars.id = x.adm_no;
                            listVars.name = x.full_name;

                            list += Lists_Templates.studentTableList(listVars);

                            console.log(list);
                            //console.log(XHRs[s]);
                            console.log(x.responseText);



                            listVars.id = x.adm_no;
                            listVars.name = x.full_name;

                            list += Lists_Templates.studentTableList(listVars);

                            //return list;
                            
                            console.log(listVars);  

                        }
                        
                    });*/
                    
                    //console.log(list);
                    //console.log(ajaxObjectResult);
                    
                    list += '';
                    
                    
                    /*var listTemplate = {
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
*/
                }
                
                
            }, 'text');
            
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
                
                if (!result.trim() || result === '0') {
                    // is empty or whitespace
                    console.log('empty. No assignments found');

                    var errorMessage = '<span>Sorry, there are no assignments found in this classroom.</span>';

                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(errorMessage, 5000, '', function () {
                        
                    });

                } else {
                    
                    result = cleanArray(result.split(','));
                    
                    console.log('Assignments found');
                    console.log(result);

                    var listVars = {
                        "id":"",
                        "name":"",
                    };
                    
                    var list = '';
                    var classId = '';
                    var XHRs = [];
                    var ajaxObjectResult = '';
                    
                    $.each(result, function(i, v) {
                        
                        classId = v;
                        
                        console.log(v);
                        
                        /*XHRs.push($.get("handlers/db_info.php", {"action": "StudentIdExists", "adm_no": admNo}, function (r) {
                                
                                //console.log(l);
                                //console.log(l.username);
                            }
                            
                        ));*/
                        
                        
                        XHRs.push(
                            $.ajax({
                                url: "handlers/db_info.php",
                                type: 'GET',
                                dataType: 'json',
                                data: {"action": "GetTeacherAssInClass", "class_id": classId}
                            })
                        );
                          
                    });
                    
                    var responseLength = (XHRs.length - 1);
                    
                    $.each(XHRs, function(b, n) {

                        XHRs[b].done(function(x) {
                        
                            console.log(b);
                            console.log(responseLength);
                            
                            if ( b < (responseLength) ) {
                                
                                console.log('still less');
                                
                                listVars.id = x.adm_no;
                                listVars.name = x.full_name;

                                list += Lists_Templates.studentTableList(listVars);

                            } else {
                            
                                console.log('last one')
                                
                                listVars.id = x.adm_no;
                                listVars.name = x.full_name;

                                list += Lists_Templates.studentTableList(listVars);

                                //Continue with the rest of the functions
                                var listTemplate = {
                                    "listData" : list
                                };

                                var listData = Lists_Templates.studentTable(listTemplate);

                                //variables for the modal
                                var template = {
                                    modalId: 'ClassRoomAssignments',
                                    templateHeader: 'All assignments given to the classroom',
                                    templateBody: listData,
                                };

                                $('main').append(Lists_Templates.modalTemplate(template));

                                $('#' + template.modalId).openModal({dismissible:false});

                                console.log('modal assignments classroom list created.');

                            }
                            
                        });
                    
                    });
                    
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
                            
                            $('#classroomTab').append(Lists_Templates.classroomCardListContainer);
                            
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
                            modalActionTypeText: 'Update classroom',
                            extraActions: Lists_Templates.editExtraFooterActions({
                                "Delete" : true,
                                "Archive" : true,
                                "Reload" : false
                            })
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
                        
                        $('.dropdown-button').dropdown({
                            hover: false,
                            alignment: 'bottom',
                            constrain_width: false, // Does not change width of dropdown to that of the activator
                            gutter: 300,
                            belowOrigin: false
                        });
                        
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
                    var Result = Lists_Templates.classRoomCardData(formResults);
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
        
        var checkboxEl = 'input#addStudentsToClassroom, input#addMoreStudentsToClassroom';
        var checkedCheckboxEl = 'input#addStudentsToClassroom:checked, input#addMoreStudentsToClassroom:checked';
        var modal_id = 'NewClassStudentList';
                        
        var main = $('main');
        
        main.on('change', checkboxEl, function (e) {
        
            e.preventDefault();
            
            var hook = $('.student-list');
            
            console.log('V- ' + $(checkboxEl).val());
            console.log('V- ' + $(checkboxEl).attr('name'));
            console.log('V- ' + $(checkboxEl).attr('id'));
            
            var action = $(checkboxEl).val();
            
            console.log('length- ' + $(checkedCheckboxEl).length);
            
            if($(checkedCheckboxEl).length > 0) {//checked
                
                //remove existing esomo modal
                cleanOutModal('#esomoModal' + modal_id);
                
                var classiD = localStorage.getItem("cardId");
                
                console.log('adding list');
                
                var subject = $('select#newClassroomSubject').val();
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
    //--------------------------------  END OF CLASSROOM EVENTS AND FUNCTIONS
    //--------------------------------
  
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
                .split('row').join('')
                .split(' ').join('');
            
            console.log(hookType);
            
            if(hookType === 'student-list') {//classroom form
                if ($('.modal#editClassRoom .students').length > 0) {

                    var previousTotal = $('.modal#editClassRoom .students').attr('data-total-students');

                    console.log(selectedStringFormat);
                    if($('.modal#editClassRoom .students').attr('data-selected-students') === '0') {
                        
                    } else {
                    
                        selectedStringFormat += $('.modal#editClassRoom .students').attr('data-selected-students');
                        console.log(selectedStringFormat);
                        
                    }
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
    
    var searchIfClassNameExists = function (className) {
        
        console.log('checking if class name ' + className + ' exists');
        
        return 1;
        
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
        
        console.log('cleaning out modals');
        
        //$('a#createClassroom').attr('data-target', '');
        
        $('.modal ').remove();

    };
    
    //--------------------------------
    //--------------------------------  END OF MODAL EVENTS AND FUNCTIONS
    //--------------------------------
    
    this.__construct();
    
};