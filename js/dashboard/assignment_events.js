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
        addClassroomToAssignmentValidates();
        submitNewAssignment();
        returnAssSubmission();
        assSubmissionCardEvents();
    };
    
    //-------------------------------
    //--------------------------------  TEACHER ASSIGNMENT EVENTS AND FUNCTIONS
    //--------------------------------
    
    var submitNewAssignment = function (userInfo) {           
        
        //get form variables
        //validate the variables
        //submit the variables
        // clear the form
        
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
                newAssignmentClassrooms = $('form#createAssignmentForm select#classroomToAssignment').val(),
                newAssignmentCanComment = $('form#createAssignmentForm #canComment:checked').length,
                totalClassrooms,
                subjectLogicError = $('form#createAssignmentForm #extraClassroomSelectInfo').find('.js-no-logic').length,
                formData = new FormData();
            
            console.log($('form#createAssignmentForm select#classroomToAssignment:selected').parent('optgoup').attr('data-subject-id'));
//            console.log(newAssignmentCanComment);
            
            //validate first
            if (newAssignmentTitle == '' || newAssignmentDueDate == '' || newAssignmentMaxGrade == '' || newAssignmentClassrooms == '' || subjectLogicError > 0) {
                var errorMessage = 'Error in creating the assignment. Kindly see if you have filled all inputs.';
                
                console.log('empty form. Unable to create the assignment');

                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(errorMessage, 2800);
                return;
                
            }
            totalClassrooms = newAssignmentClassrooms.length;
            
            $this.addClass('disabled btn-loading')
                .text('creating...');
            if (newAssignmentResources.length > 0) {
                
                for (var g = 0; g < newAssignmentResources.length; g++) {
                    //Hoping the indexes will match
                    formData.append('file-'+g, newAssignmentResources[g]);
                }
            }

            //Append the data and the action name
            var formResults = {
                totalClassrooms: totalClassrooms,
                assignmenttitle : newAssignmentTitle,
                assignmentdescription : newAssignmentDescription,
                classids : newAssignmentClassrooms,
                duedate : newAssignmentDueDate,
                maxgrade : newAssignmentMaxGrade,
                cancomment : newAssignmentCanComment
            };
            console.log(formResults);
            
            formData.append('data', JSON.stringify(formResults));
            formData.append('action', 'UpdateAssignmentInfo');

            $.ajax({
                url: "handlers/db_handler.php",
                data: formData,
                xhr: function() {
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
                    $('#createAssignmentForm').find('.progress').fadeIn(240);
                    $('.js-upload-num-progress').html('0%');

                    $('.js-progress-bar').animate({
                        width:'100%'
                    });

                    $('.progress.js-progress-bar .determinate').animate({
                        width:'0%'
                    });

                    $('.num-progress').addClass('secondary-text-color').removeClass('hide red-text text-accent-1').html('<i>Uploading <span class="js-upload-num-progress">0%</span></i>');
                    $('.progress.js-progress-bar .determinate').removeClass('red accent-3');
                    
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
                        message = 'Assignment created! <a href="./?section=sent-assignments" class="btn-inline">click here</a> to view the assignment';
                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast(message, 5000, 'green white-text name accent-3', function () {
                            console.log('toast on file upload success');
                        });
                        
                        $('form#createAssignmentForm').find('input[type="text"], textarea').val('');
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

        });

    };

    //--------------------------------
    
    var addClassroomToAssignmentValidates = function () {

        $('main').on('change', '.main-tab#createAssignmentsTab select#classroomToAssignment', function (e) {
            e.preventDefault();
            
            var selectedClassrooms = $('form#createAssignmentForm select#classroomToAssignment').val(),
                group = new Array(),
                logicWarning = '<div class="chip js-no-logic red accent-3 white-text">Warning: You have chosen classrooms of different subjects</div>',
                subjectSelected;
            $.each(selectedClassrooms, function(b,x) {
                var option = $('form#createAssignmentForm select#classroomToAssignment option[value="'+ x +'"]'),
                    optgroup = option.parent('optgroup').attr('data-subject-id');
                group.push(Number(optgroup));
                group = _.uniq(group);
                console.log(group[0]);
                
            });
            //if multiple classrooms of different subjects are selected, issue a warning because it's not logical
            // else shows the subject name
            if (group.length > 1) {
                $('form#createAssignmentForm #extraClassroomSelectInfo').find('.js-assignment-subject').html(logicWarning);
            } else {
                subjectSelected = $('form#createAssignmentForm select#classroomToAssignment optgroup[data-subject-id="'+ group[0] +'"]').attr('label');
                $('form#createAssignmentForm #extraClassroomSelectInfo').find('.js-assignment-subject').html(subjectSelected);
            }
        });
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
    
    var progress = function (e) {

        if(e.lengthComputable){
            var max = e.total,
                current = e.loaded,
                Percentage = Math.ceil((current * 100)/max);

            console.log(Percentage + '%');

            $('.js-upload-num-progress').html(Percentage + '%');
            $('.progress.js-progress-bar .determinate').css({
                width : Percentage + '%'
            });

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

    //-------------------------------
    //--------------------------------  STUDENT ASSIGNMENT EVENTS AND FUNCTIONS
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
            var $self = $(this), 
                $parent_li = $self.parents("li.ass-submission-item"),//Get the parent ul before removing the button from the dom
                $grade_input = $parent_li.find("input.ass-grade-achieved"),
                student_name = $(this).attr("data-student-name"), //Student name
                student_id = $parent_li.find(".student-name").attr('data-student-id'), //Student name
                sub_grade = $grade_input.val(),
                sub_id = $(this).attr("data-submission-id"),
                sub_data = {"grade":Number(sub_grade),"submission_id":Number (sub_id)}; //Submission data
            
            if($self.hasClass('disabled')) {
                return (false);
            }
            
            if (sub_grade === '') {
                Materialize.toast("please put a grade first before returning the assignment",1200);
                return;
            }
//            return;
            $self.addClass('disabled').html('returning...');
            
            $.post("classes/teacher.php",{"action":"ReturnAssSubmission","submission_data":sub_data},function(response,status){
                var success_message = "Successfully returned the assignment to "+student_name,
                    failure_message = "Failed to return the assignment to "+student_name,
                    toast_time = 2500; //Duration the toast will last
                console.log(response);
//                response = JSON.parse(response);

                //Successfully graded the assignment
                if(response["grade_status"]==1)
                {
                    //Successfully returned the assignment
                    if(response["return_status"]==1)
                    {
                        var max_grade = $grade_input.attr("max"),
                            $parent_ul = $self.parents("ul.ass-submission-container"),//Get the parent ul before removing the button from the dom
                            ass_sub_data = {
                                'id': sub_id,
                                'studentid': student_id,
                                'studentname': student_name,
                                'grade': sub_grade,
                                'maxgrade': max_grade
                            },
                            str = Lists_Templates.returnedAssignmentSubmissionTemplate(ass_sub_data),
                            old_sub_count = $self.parents('.submitted-assignment-list').siblings('.returned-assignment-list').find('ul.returned-ass-container li').length;

//                        console.log(student_data);
                        console.log(ass_sub_data);

                        //Add the submitted info to the DOM under the returned assignments section
                        if (old_sub_count == 0){
                            $self.parents('.submitted-assignment-list').siblings('.returned-assignment-list').find('ul.returned-ass-container').html(str);
                        } else {
                            $self.parents('.submitted-assignment-list').siblings('.returned-assignment-list').find('ul.returned-ass-container').prepend(str);

                        }
                        //Remove the submission from the DOM
                        $parent_li.remove();
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
                
                $self.removeClass('disabled').html('return');
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
            $('#assignmentUpload').modal('open');

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
            $('#assignmentUpload').modal('close');
            $('#assignmentUploadConfirm').modal('open');

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
         
                    self.html('DONE!');
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

    var uiInit = function (userInfo) {
        console.log(userInfo);
        function assSubs () {
            console.log('classroomCardsContainer pushpin');
            
            var $target = $('#classroomCardsContainer'),
                $zeroOffset = $target.offset().top;

            $target.pushpin({
                top: $target.offset().top,
                offset: 0
            });
        }
        
        $(document).ready(function () {
            
            if(userInfo.account_type != 'student') {
                if (location.search.split('=')[1] == 'assignment-submissions') {
                    assSubs();
                }
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
    uiInit(userInfo);
    ajaxDashboardInit(userInfo);

//    this.__construct();
    
};
