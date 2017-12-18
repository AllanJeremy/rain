/*global $, jQuery, alert, console*/

var AssignmentEvents = function (userInfo) {
    'use strict';
    //--------------
    var $this = this;
    
    this.__construct_Student = function (userInfo) {
        console.log('Student Assignments events created');

        //Assignments inits
        addAssignment();
        submitAssignment(userInfo);
        myAssignmentSave();

    };

    this.__construct_Admin = function (userInfo) {
        console.log('Admin Assignments events created');

        //Assignments inits
        assDueCheck();
        addClassroomToAssignment();
        submitNewAssignment();
        returnAssSubmission();
        assSubmissionCardEvents();
    };
    
    //-------------------------------
    //--------------------------------  ASSIGNMENT EVENTS AND FUNCTIONS
    //--------------------------------
    
    var addClassroomToAssignment = function () {
        
        var checkboxEl = 'input#addClassroomToAssignment, input#addMoreClassroomToAssignment',
            checkedCheckboxEl = 'input#addClassroomToAssignment:checked, input#addMoreClassroomToAssignment:checked',
            modal_id = 'NewAssClassroomList',

            main = $('main');
        
        main.on('change', checkboxEl, function (e) {
        
            console.log('classrooms adding function on');
        
            e.preventDefault();
            
            var hook = $('.classroom-list'),
                action = $(checkboxEl).val();
            
            console.log('V- ' + $(checkboxEl).val());
            console.log('V- ' + $(checkboxEl).attr('name'));
            console.log('V- ' + $(checkboxEl).attr('id'));
            console.log('length- ' + $(checkedCheckboxEl).length);
            
            if ($(checkedCheckboxEl).length > 0) {//checked
                
                //remove existing esomo modal
                Modals_Events.cleanOutModal('#esomoModal' + modal_id);
                
                console.log('adding list');
                
                if ($(checkboxEl).val() === "GetSpecificTeacherClassrooms") {
                    Materialize.toast('Fetching your classrooms', 15000, 'white-text');

                    $.get('handlers/db_info.php', {"action" : action}, function (result) {
                        console.log('get results:- ');

                        result = JSON.parse(result);
                        console.log(result);
                        console.log(typeof result);

                        if (typeof result === 'undefined') {

                        }

                        if (typeof result === 'object') {
                            //loop
                            var output = '',
                                autocompletedata = {};//limit autocomplete dropdown to 20;

                            for (var key in result) {

                                result[key].totalstudents = result[key].selectedStudents.split(',').length - 1;
                                
                                output += Lists_Templates.classroomTableList(result[key]);

                                if (result[key].name != 'undefined') {
                                    autocompletedata[result[key].name] = null;
                                    
                                }

                            }

                            output += '';

                            console.log(autocompletedata);
    
                            var list = {
                                
                                "listData" : output
                            },
                                List = Lists_Templates.classroomTable(list),

                            //open the esomo modal Template
                            //append the list to esomo modal

                                modal_header = 'Send assignment to classrooms',
                                modal_body = List;

                            Modals_Events.loadEsomoModal(modal_id, modal_header, modal_body, 'add classrooms');
                            Modals_Events.updateEsomoModalAutocomplete(modal_id, autocompletedata);

                            $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                            console.log(List);

                            var action2 = 'morph-in';

                            $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-action').bind('click', function(e) {
                                e.preventDefault();
                                var el = $(this)[0].innerHTML;

                                $(this).addClass('disabled btn-loading')
                                    .text('adding...');
                                addToForm(action2, hook, modal_id); //when add students is clicked//

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
                    Materialize.toast('Fetching students', 15000, 'white-text');

                    $.get('handlers/db_info.php', { "action" : action/*, "class_id" : localStorage.getItem("cardId")*/ }, function (result) {
                        console.log('get results:- ');

                        result = JSON.parse(result);
                        console.log(result);
                        console.log(typeof result);

                        if (typeof result === 'object') {
                            //loop

                            var output = '',
                                autocompletedata = {};//limit autocomplete dropdown to 20;

                            for (var key in result) {

                                output += Forms_Templates.formOptionsTemplate(result[key]);

                                if (result[key].name != 'undefined') {
                                    autocompletedata[result[key].name] = null;
                                }
                            }

                            output += '';

                            console.log(autocompletedata);

                            //open the esomo modal Template
                            //append the list to esomo modal

                            var optionslist = output,
                                formList = Forms_Templates.makeStudentFormList(optionslist),
                                modal_header = 'Add students to the classroom',
                                modal_body = formList,
                                action2 = 'morph-in';

                            Modals_Events.loadEsomoModal(modal_id, modal_header, modal_body, 'add students');
                            
                            $('.modal#esomoModal' + modal_id).openModal({dismissible : false});

                            console.log(formList);

                            //Init functions needed for the esomo actions
                            Modals_Events.updateEsomoModalProgress(modal_id);
                            Modals_Events.updateEsomoModalAutocomplete(modal_id, autocompletedata);

                            $('.modal#esomoModal' + modal_id + ' a#modalFooterActionAdd.modal-action').bind('click', function(e) {
                                e.preventDefault();
                                $(this).addClass('disabled btn-loading')
                                    .text('adding...');
                                addToForm(action2, hook, modal_id); //when add students is clicked//

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
    
    var submitNewAssignment = function (str1, str2) {           
        
        //get form variables
        //validate the variables
        //submit the variables
        //prepend the new classroom card to the list
        //initialize tooltip
        //initialize classroom events
        //close modal
        
        $('main').on('click', '.main-tab#createAssignmentsTab button#createNewAssignment', function (e) {
            e.preventDefault();

            console.log('new assignment submit event handler ready');
            var $this = $(this),
                $thisEl = $this[0].innerHTML;
                
            if($this.hasClass('disabled')) {
                return (false);
            }

            var newAssignmentTitle = document.forms['createAssignmentForm']['newAssignmentName'].value,
                newAssignmentDescription = document.forms['createAssignmentForm']['assignmentInstructions'].value,
                newAssignmentDueDate = document.forms['createAssignmentForm']['ass_due_date'].value,
                newAssignmentDueDateFormatted = document.forms['createAssignmentForm']['assDueDate'].value,
                newAssignmentMaxGrade = document.forms['createAssignmentForm']['assMaxGrade'].value,
                newAssignmentResources = document.forms['createAssignmentForm']['ass_resources'].files,
                newAssignmentHasClassroom = $('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').length || false,
                newAssignmentCanComment = 0, totalClassrooms = 0, newAssignmentClassIds = ['0'],
                formData = new FormData();
                
            if(newAssignmentHasClassroom) {
                newAssignmentCanComment = document.forms['createAssignmentForm']['canComment'].value;
                newAssignmentClassIds = $('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').attr('data-selected-classrooms');
                totalClassrooms = $('#createAssignmentsTab form#createAssignmentForm .classroom-list .classrooms').attr('data-total-classrooms');
            }
            console.log(newAssignmentClassIds);  
            $this.addClass('disabled btn-loading')
                .text('creating...');
            
            for (var g = 0; g < newAssignmentResources.length; g++) {
                //Hoping the indexes will match
                formData.append('file-'+g, newAssignmentResources[g]);
            }
            

            if (newAssignmentCanComment === 'on') {
                
                newAssignmentCanComment = 1;
            }
            
            if (typeof newAssignmentClassIds !== 'undefined') {
                newAssignmentClassIds = newAssignmentClassIds.slice(0,-1);
                newAssignmentClassIds = newAssignmentClassIds.split(',');
            }
            
            //validate first
                //    return;

            if (newAssignmentTitle !== '' && newAssignmentDescription !== '' && newAssignmentDueDate !== '') {

                //Append the data and the action name
                var formResults = {
                    totalClassrooms: totalClassrooms,
                    assignmenttitle : newAssignmentTitle,
                    assignmentdescription : newAssignmentDescription,
                    classids : newAssignmentClassIds,
                    duedate : newAssignmentDueDate,
                    maxgrade : newAssignmentMaxGrade,
                    cancomment : newAssignmentCanComment
                };

                console.log(formResults);
                return;
                formData.append('data', JSON.stringify(formResults));
                formData.append('action', 'UpdateAssignmentInfo');
                    
                console.log(formData);
                $.ajax({
                    url: "handlers/db_handler.php",
                    data: formData,
        //                    xhr: function() {
        /*
                var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress', progress, false);
                    }
                return myXhr;
        */
        //                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    beforeSend : function () {
                        //Make the loader visible
                        $('#createAssignmentForm').find('.progress').fadeIn(240);

                    },
                    success: function (returndata) {
                        $this.removeClass('disabled btn-loading');
                        $this[0].innerHTML = $thisEl;

                        var returndata = jQuery.parseJSON(returndata),
                            message = '';
                        console.log(returndata);

                        if (returndata.failedFiles.length > 0 ) {
                            $.each(returndata.failedFiles, function (b,k) {
                                
                                message = 'Error in uploading ' + newAssignmentResources[k].name;
                                // Materialize.toast(message, displayLength, className, completeCallback);
                                Materialize.toast(message, 5000, 'red white-text name accent-3', function () {
                                    console.log('toast on file upload error');
                                });
                            });
                        }
                        
                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast(message, 5000, 'green white-text name accent-3', function () {
                            console.log('toast on mysql error');
                        });
                        
                        var c = 0;
                        for (var l =0; l < returndata.result.length; l++) {
                            if(!returndata.result[l]) {
                                message = 'Error in creating the assignment for class ' + newAssignmentClassIds[l] + '.';
                                // Materialize.toast(message, displayLength, className, completeCallback);
                                Materialize.toast(message, 5000, 'red white-text name accent-3', function () {
                                    console.log('toast on file upload error');
                                });
                                c++;
                            }
                        }

                        if(c === 0) {
                            message = 'Assignment created! <a href="" class="btn-inline">reload</a> to view the assignment';
                            // Materialize.toast(message, displayLength, className, completeCallback);
                            Materialize.toast(message, 5000, 'green white-text name accent-3', function () {
                                console.log('toast on file upload success');
                            });
                        }

                    },
                    error: function (e) {
                        $this.removeClass('disabled btn-loading');
                        $this[0].innerHTML = $thisEl;

                        var message = 'Assignment could not be created<br> Reason: ' + e;

                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast(message, 5000, 'red white-text name accent-3', function () {
                            console.log('toast on file upload error');
                        });

                        console.log(e);
                        console.log("Not Cool");
                    }
                }, 'json');

            } else {
                $this.removeClass('disabled btn-loading');
                $this[0].innerHTML = $thisEl;
                var errorMessage = 'Error in creating the assignment. Kindly see if you have filled all inputs.';
            
                console.log('empty form. Unable to create the assignment');

                
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(errorMessage, 2800, 'red white-text name accent-3', function () {
                    Modals_Events.cleanOutModals();
                });
            }

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

        var totalSelected = $('#esomoModal' + modal_id + ' .list').find('input[type="checkbox"]:checked').length,
            selectedArrayResult = $('#esomoModal' + modal_id + ' .list').find('input[type="checkbox"]:checked').map(function(){
            return $(this).attr('id');
        }).get(), // <----

            selectedStringFormat = selectedArrayResult.toString();
        
        selectedStringFormat += ',';//for database' sake, let the string end with a commar*

        if (typeof totalSelected === 'number' && totalSelected > 0) {

            console.log(hook.attr('class'));
            
            if(hook.hasClass('student-list')) {//classroom form
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

                    hook.append('<div class="col s12 rain-theme-primary students lighten-2 card-panel " data-total-students="' + (selectedStringFormat.split(',').length - 1) + '" data-selected-students="' + selectedStringFormat + '"><p class="white-text php-data">' + previousTotal + ' students are already in the classroom<br>' + ((selectedStringFormat.split(',').length - 1) - parseInt(previousTotal)) + ' more students will be added to the classroom on submit.<p></div>');

                } else {
                    
                    hook.append('<div class="col s12 rain-theme-primary students lighten-2 card-panel " data-total-students="' + totalSelected + '" data-selected-students="' + selectedStringFormat + '"><p class="white-text php-data">A total of ' + totalSelected + ' students to be added in the classroom.<p></div>');
                    
                }
                
            } else if(hook.hasClass('classroom-list')) {//student form

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

                    hook.append('<div class="col s12 rain-theme-primary classrooms lighten-2 card-panel " data-total-classrooms="' + (selectedStringFormat.split(',').length - 1) + '" data-selected-classrooms="' + selectedStringFormat + '"><p class="white-text php-data">' + previousTotal + ' classrooms have the assignment<br>' + ((selectedStringFormat.split(',').length - 1) - parseInt(previousTotal)) + ' more classrooms will receive this assignment on submit.<p><p><input id="canComment" class="filled-in" type="checkbox"><label for="canComment">Allow students to comment</label></p><br></div>');

                } else {

                    hook.append('<div class="col s12 rain-theme-primary classrooms lighten-2 card-panel " data-total-classrooms="' + totalSelected + '" data-selected-classrooms="' + selectedStringFormat + '"><p class="white-text php-data">A total of ' + totalSelected + ' classrooms to receive the assignment.</p><p><input id="canComment" class="filled-in" type="checkbox"><label for="canComment">Allow students to comment</label></p><br></div>');

                }

            }
            
            console.log(totalSelected);

            $('#esomoModal' + modal_id).closeModal();
            Modals_Events.cleanOutModal('#esomoModal' + modal_id);

            return true;

        } else {
            var toastMsg = 'Select atleast one ' + ((hook.hasClass('classroom-list')) ? 'classroom.' : 'student.' );

            console.log(totalSelected);

            Materialize.toast(toastMsg, 2200);

            // Modals_Events.cleanOutModal('#esomoModal' + modal_id);

            return null;
        }
    };

    //----------------------------      FUNCTIONS

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
    
    var progress = function (e, userInfo) {

        if(e.lengthComputable){
            var max = e.total,
                current = e.loaded,
                Percentage = Math.ceil((current * 100)/max);

            console.log(Percentage + '%');

            if (userInfo.account_type != 'student') {

                $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .progress .determinate').animate({
                    width : Percentage + '%'
                });
            } else {
                $('.js-upload-num-progress').html(Percentage + '%');
                $('.progress.js-progress-bar .determinate').css({
                    width : Percentage + '%'
                });
            }


            if(Percentage >= 100)
            {
               // process completed
                $('.js-upload-num-progress').html('100%');

                $('.progress.js-progress-bar .determinate').css({
                    width : '100%'
                });
            }
        }
    };

    //--------------------------------

    var assSubmissionCardEvents = function () {

        /*GLOBAL VARIABLES*/
        var selected_class = "selected"; //css class used for selected class
        var $ass_classroom_card = ".ass-classroom-card"; //css selector for an assignment submission classroom card ~ cards that appear at the top
        var $class_ass_container = ".classroom-ass-container"; //css selector for class_assignment container
        var $ass_grade_achieved = ".ass-grade-achieved";//css selector for grade achieved for an assignment submission
        var $return_ass_submission = ".return-ass-submission";
        var $ass_submission_item = ".ass-submission-item";

        //Hide all assignment containers
        function HideAllAssContainers()
        {
            $($class_ass_container).addClass("hide");
        }

        //Show only the assignments of the active classroom
        function ShowActiveAssContainer()
        {
            HideAllAssContainers();//Hide all assignment containers

            //Display the appropriate container for the currently selected classroom card
            var active_container_id = $($ass_classroom_card+".selected").attr("data-content-trigger");

            $($class_ass_container+"#"+active_container_id).removeClass("hide");
        }

        /*When a classroom card is clicked*/
        $($ass_classroom_card).click(function(){
            var trigger_id = $(this).attr("data-content-trigger");
            var $child_card_selector = ".card.tiny";

            //Remove class from all the other cards as well as their child cards
            $($ass_classroom_card).removeClass(selected_class);
            $($ass_classroom_card).children($child_card_selector).removeClass(selected_class);

            //Add the class to the clicked card as well as its immediate child
            $(this).children($child_card_selector).addClass(selected_class);
            $(this).addClass(selected_class);

            //Display assignments for the currently clicked card
            ShowActiveAssContainer();
        });

        ShowActiveAssContainer();

        /*Validate an input to check if it is a number. WORKING*/
        function ValidateAssGradeInput($ass_grade_input)
        {
            var min = parseInt($ass_grade_input.attr("min"));//Minimum valid input
            var max = parseInt($ass_grade_input.attr("max"));//Maximum valid input
            var curr_val = $ass_grade_input.val();

            //Regulate the current value
            if(curr_val>max)
                curr_val=max;
            else if(curr_val<min)//If input is less than min, make it equal to min
                curr_val=min;

            return curr_val;
        }

        /*Create assignment form submitted*/
        $("#createAssignmentForm").submit(function(e){

            e.preventDefault();/*Prevent page from reloading*/
            console.log("Form submitted.\nFile data is ",$("#assDueDate").val());

        });

        /*When the value of the assignment grade changes*/
        $($ass_grade_achieved).change(function(){
            var curr_val = ValidateAssGradeInput($(this));//Current value
            $(this).val(curr_val);
        });

    };

    //--------------------------------

    var returnAssSubmission = function () {

        /*Returning assignments to students*/
        $('.return-ass-submission').click(function(){
            console.log('Returning assignment to student');
            var $self = $(this), //Student name
                student_name = $(this).attr("data-student-name"), //Submission data
                sub_id = $(this).attr("data-submission-id"),
                sub_grade = $(this).siblings("span").children("input.ass-grade-achieved").val(),
                sub_data = {"grade":sub_grade,"submission_id":sub_id};
            
            if($self.hasClass('disabled')) {
                return (false);
            }
            
            $self.addClass('disabled');
            
            $.post("classes/teacher.php",{"action":"ReturnAssSubmission","submission_data":sub_data},function(response,status){
                var success_message = "Successfully returned the assignment to "+student_name,
                    failure_message = "Failed to return the assignment to "+student_name,
                    toast_time = 2500; //Duration the toast will last

                response = JSON.parse(response);

                //Successfully graded the assignment
                if(response["grade_status"]==1)
                {
                    //Successfully returned the assignment
                    if(response["return_status"]==1)
                    {
                        var $parent_ul = $self.parents("ul.row"),//Get the parent ul before removing the button from the dom
                            $grade_input = $parent_ul.find(".ass-grade-achieved"),
                            grade = $grade_input.val(),
                            student_data = $self.parent('span').siblings(".student-name"),
                            max_grade = $grade_input.attr("max"),
                            ass_sub_data = {
                                'id': sub_id,
                                'name': student_data[0].outerHTML.split('|')[0],
                                'grade': grade,
                                'maxgrade': max_grade
                            },
                            str = Lists_Templates.returnedAssignmentSubmissionTemplate(ass_sub_data),
                            old_sub_count = $self.parents('.submitted-assignment-list').siblings('.returned-assignment-list').find('ul.returned-ass li').length;

                        console.log(student_data);
                        console.log($self.parent());
                        console.log($self.parent('span'));


                        //Add the submitted info to the DOM under the returned assignments section
                        if (old_sub_count == 0){
                            $self.parents('.submitted-assignment-list').siblings('.returned-assignment-list').find('ul.returned-ass').html(str);
                        } else {
                            $self.parents('.submitted-assignment-list').siblings('.returned-assignment-list').find('ul.returned-ass').prepend(str);

                        }
                        //Remove the submission from the DOM
                        $self.parents('.ass-submission-item').remove();
                        var sub_count = $parent_ul.children("li").length;

                        //If there are no submissions left in the DOM
                        if(sub_count==0)
                        {
                            $parent_ul.html("<p>No new assignment submissions were found.</p>");
                        }

                        //Display success message
                        Materialize.toast(success_message,toast_time);

                    }
                    else //Failed to return the assignment
                    {
                        Materialize.toast(failure_message+". Error : Successfully graded but failed to return submission",toast_time);
                    }
                }
                else
                {
                    Materialize.toast(failure_message+". Error : Failed to grade submission",toast_time);
                }
                
                $self.removeClass('disabled');
            }, 'json');
        });

    }

    //--------------------------------

    var addAssignment = function () {
        var filesinfo, files, totalfiles, assignmentslisthook, assignments_errorshook, errorlist = '';

        // HEADER, NOT MAIN
        console.log('addAssignment');
        //Checks on file input change, updates, the modal infos
        $('main').on('change', "form#addAssignmentForm input[name='assignments']", function (e) {
            e.preventDefault();

            files = document.forms['addAssignmentForm']['assignments'].files;
            totalfiles = files.length;

            console.log(files);
            console.log(totalfiles);
            if (files.length > 0) {
                $('a.js-submit-assignment').removeClass('disabled');
                $('a.js-confirm-submit-assignment').removeClass('disabled');
            } else {
                $('a.js-submit-assignment').addClass('disabled');
                $('a.js-confirm-submit-assignment').addClass('disabled');

            }

            $('.progress.js-progress-bar .determinate').removeClass('red accent-3');

            console.log(files);

            $('.modal .modal-content').find('span.js-total-assignments').html(totalfiles);

            filesinfo = generateAssignmentList(files);

            assignmentslisthook = $('.modal .modal-content').children('.js-ass-submission-list');

            assignmentslisthook.fadeOut(300, function () {

                $(this).html(filesinfo);
                $(this).fadeIn();
            });

            var validateresult = validateFiles(files);
            assignments_errorshook = $('.modal .modal-content').children('.js-ass-error-container');

            console.log(validateresult);

            if(validateresult.length > 0) {
                //disable the upload button
                //show errors
                $('a.js-submit-assignment').addClass('disabled');
                $('a.js-confirm-submit-assignment').addClass('disabled');

                $.each(validateresult, function(b,x) {
                    errorlist += Lists_Templates.documentUploadsErrorListTemplate(files[x.index], x.errortype);

                });

                assignments_errorshook.find('ul:first').html(errorlist);

                errorlist = '';

                return;
            }

            assignments_errorshook.find('ul:first').html('');
            errorlist = '';


        });


        $('header').on('click', 'a.js-upload-assignment', function (e) {
            e.preventDefault();
            console.log('opening modal');
            $('#assignmentUpload').openModal({dismissible: false});

        });

        };

    //-----------
    //Will generate a new file input then prepend the added files
    //Update
    var addMoreAssignmentFiles = function () {
        //$('main').on('click', 'a.js-submit-assignment', function (e) {
        //    e.preventDefault();

        //});
    };

    //-----------
    //submit the assignments form
    var submitAssignment = function (userInfo) {

        console.log('click event true');
        console.log(userInfo);

        $('header, main').on('click', 'a.js-submit-assignment', function (e) {
            e.preventDefault();
            console.log(userInfo);
            console.log('submitting');

            if ($(this).hasClass('disabled') || $('header').attr('data-submitted') === '1') {
                console.log('cancelled');
                return;
            }
            //Check if there's any upload to be submitted.

            var fileinputs = document.forms['addAssignmentForm']['assignments'],
                files = fileinputs.files, totalfiles = files.length,
                assid = parseInt($('header').attr('data-assignment-id')),
                formData = new FormData(),
                asstitle = $('#myAssignment').find('input.js-myAssignment-title').val(),
                studentid = userInfo.user_id,
                submissiontext = '',
                attachments = '',
                submitted = 1, DATA = [],
                notitle_errormessage = '<span class="red-text name text-lighten-5">You need to give your document a title before saving it.</span>',
                comments_enabled =  parseInt($('header').attr('data-comments-enabled'));

            console.log($('#myAssignment > .assignment-tinymce #body.tinymce-document').html());

            if (asstitle === '') {
                Materialize.toast(notitle_errormessage, 5000, '', function () {
                    console.log('toast on file submit');
                });
                return;
            }

            for (var g = 0; g < files.length; g++) {
                //Append the files
                formData.append('file-'+g, files[g]);
                attachments += files[g].name + ',';

            }

            var data = {
                'submissiontitle' : asstitle,
                'assid' : assid,
                'studentid' : studentid,
                'submissiontext' : submissiontext,
                'attachments' : attachments,
                'submitted' : submitted,
                'commentsenabled' : comments_enabled
            };

            //open the confirm submission modal, parsing the data
            $('#assignmentUpload').closeModal();
            $('#assignmentUploadConfirm').openModal({dismissible: false});

            confirmedAssSubmit(formData,data,userInfo);

        });
    };

    //-----------
    //pass the data collected and the user info
    var confirmedAssSubmit = function (form, obj, i) {

        console.log('Final Submit click event set');

        $('main').on('click', 'a#submitAssignment_Confirm_Modal', function (e) {
            e.preventDefault();
            var formData = form,
                self = $(this),
                selfText = $(this).html(),
                data = obj,
                user = i;

            if(self.hasClass('disabled')) {
                return;
            }
            self.addClass('disabled btn-loading').text('submitting');
            
            //Get the submission text sent if comments are enabled
            if(data.commentsenabled === 1) {
                data.submissiontext = $('textarea.js-submission-text').val();
            } else {
                data.submissiontext = '';
            }

            console.log(data);

            //Append the data and the action name
            formData.append('data', JSON.stringify(data));
            formData.append('action', 'AssignmentSubmit');

            $.ajax({
                url: "handlers/db_handler.php",
                data: formData,
                xhr: function() {
                    //If the modal is open
                    var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress', progress, false);
                    }
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                beforeSend : function () {
                    //Make the loader visible
                    $('.js-upload-num-progress').html('0%');

                    $('.js-progress-bar').animate({
                        width:'100%'
                    });
                    $('.modal#assignmentUpload .modal-content').find('.js-assignment-total-info .progress.js-progress-bar').animate({
                        width:'50%'
                    });

                    $('.progress.js-progress-bar .determinate').animate({
                        width:'0%'
                    });

                    $('.num-progress').addClass('secondary-text-color').removeClass('hide red-text text-accent-1').html('<i>Uploading <span class="js-upload-num-progress">0%</span></i>');
                    $('.progress.js-progress-bar .determinate').removeClass('red accent-3');
                },
                success: function (returndata) {

                    console.log("Cool");
                    console.log(returndata);

                    var failedfiles = jQuery.parseJSON(returndata);

                    console.log(failedfiles);

                    //if success
                    if(failedfiles.status) {
                        $('.num-progress').html('Upload successful');
                        Materialize.toast('assignment submitted! <a href="" class="btn-inline">reload</a>', 3000, 'green accent-3');

                        setTimeout(function () {
                            location.reload();

                        }, 2000);

                    } else {
                        Materialize.toast('Oops. Error in submitting your assignment at this time. This was unexpected', 3000, 'red accent-3');

                        if(failedfiles.failed_files.length > 0) {
                            //File didn't upload
                            //Probably there was an error
                            $('.num-progress').html('Upload error<br>Check if you have any errors on the files list.');

                        } else {
                            $('.num-progress').html('Upload error<br>');

                        }
                        $('.num-progress').removeClass('secondary-text-color').addClass('red-text text-accent-1');
                        $('.progress.js-progress-bar .determinate').addClass('red accent-3');
                    }
         
                    self.removeClass('disabled btn-loading').html(selfText);
                },
                error: function (e) {
                    console.log("Not Cool");
                    console.log(e.statusText);
                }
                
            }, 'json');

            // Cancel click event.
            return( false );

        });

    };

    //--------------------------------

    var validateFiles = function (files) {
        var mimetypes = Array("application/pdf","image/jpeg","image/jpg","image/png","application/msword","application/vnd.ms-excel","application/vnd.ms-powerpoint","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.openxmlformats-officedocument.presentationml.presentation"),
            maxsize = 52428800,
            reportdata = [];

        for(var i = 0;  i < files.length; i++) {
            var errordata = {
                'index' : '',
                'errortype' : []//0 for mimetype, 1 for file exceeding its size
            };
            //console.log(jQuery.inArray(files[i].type, mimetypes));
            if(jQuery.inArray(files[i].type, mimetypes) < 0) {//if it is -1, then it's not part of the mimetype
                errordata.index = i;
                errordata.errortype.push(0);

                if (files[i].size > maxsize ) {
                    errordata.errortype.push(1);
                }
                reportdata.push(errordata);
            }
        }
        return reportdata;
    };

    //--------------------------------

    var myAssignmentSave = function (e) {
        var fileName, message;

        $('.js-save-myAssignment').click(function () {
            fileName = $('#myAssignment').find('input.js-myAssignment-title').val();

            if (fileName === '') {
                message = '<span class="red-text name text-lighten-5">You need to give your document a title before saving it.</span>';
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(message, 5000, '', function () {
                    console.log('toast on file save error');
                });
                return;
            } else {
                fileName = fileName + '.pdf';
            }

            $( ".assignment-tinymce #body" ).print();
            // Cancel click event.
            return( false );

        });
    };

    //-----------
    //Generates the assignments list.
    var generateAssignmentList = function (obj) {
        var str = '';

        for(var a = 0; a < obj.length; a++) {

            str += Lists_Templates.documentsListTemplate(obj[a], a);
        }

        return str;
    };

    //--------------------------------

    var assDueCheck = function () {
        console.log('Updating due text in real time');
    };

    //--------------------------------

    var getUserInfo = function () {

        var $req =  $.ajax({
            url: 'handlers/session_handler.php',
            data: {'action':'GetLoggedUserInfo'},
            type: 'GET',
            processData: true
        }, 'json');

        return $req;

    };

    var ajaxInit = function () {

        $.when(getUserInfo()).then(function (_1,_2,_3) {
        /*
            console.log(_1);
            console.log(_2);
            console.log(_3.responseText);
        */

            userInfo = jQuery.parseJSON(_1);

            if(userInfo.account_type !== 'student') {

                console.log('Admin account. Construct admin events for the page.');
                $this.__construct_Admin(userInfo);
            } else {

                console.log('Student account. Construct student events for the page.');
                $this.__construct(userInfo);

                return;
            }

        });

    };

    var ajaxDashboardInit = function (userInfo) {
        console.log(userInfo);
        if(userInfo.account_type != 'student') {

            console.log('Admin account. Construct admin events for the page.');
            $this.__construct_Admin(userInfo);
        } else {

            console.log('Student account. Construct student events for the page.');
            $this.__construct_Student(userInfo);

            return;
        }

    };

//    $this.__construct(userInfo);
//    ajaxInit();
    ajaxDashboardInit(userInfo);

//    this.__construct();
    
};
