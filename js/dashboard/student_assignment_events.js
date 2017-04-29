/*global $, jQuery, alert, console*/

var StudentAssignmentEvents = function () {
    'use strict';
    //--------------

    var userInfo, $this = this;

    this.__construct = function (userInfo) {
        console.log('Assignments events created');

        //Assignments inits
        addAssignment();
        submitAssignment(userInfo);
        myAssignmentSave();

    };

    var addAssignment = function () {
        var filesinfo, files, totalfiles, assignmentslisthook, assignments_errorshook, errorlist = '';

        // HEADER, NOT MAIN

        //Checks on file input change, updates, the modal infos
        $('main').on('change', "form#addAssignmentForm input[name='assignments']", function (e) {
            e.preventDefault();

            files = document.forms['addAssignmentForm']['assignments'].files;
            totalfiles = files.length;

            console.log(files);
            console.log(totalfiles);
            if (files.length > 0) {
                $('a.js-submit-assignment').removeClass('disabled');
            } else {
                $('a.js-submit-assignment').addClass('disabled');

            }

            console.log(files);

            $('.modal#assignmentUpload .modal-content').find('span#totalAssignments').html(totalfiles);

            filesinfo = generateAssignmentList(files);

            assignmentslisthook = $('.modal#assignmentUpload .modal-content').children('#assignmentsList');

            assignmentslisthook.fadeOut(300, function () {

                $(this).html(filesinfo);

                $(this).fadeIn();
            });

            var validateresult = validateFiles(files);
            assignments_errorshook = $('.modal#assignmentUpload .modal-content').children('#errorContainer');

            console.log(validateresult);

            if(validateresult.length > 0) {
                //disable the upload button
                //show errors
                $('a.js-submit-assignment').addClass('disabled');

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

            console.log(fileinputs);

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

            //return;

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
                    $('.num-progress').removeClass('hide');
                    $('.js-upload-num-progress').html('0%');

                    $('.js-progress-bar').animate({
                        width:'100%'
                    },300);
                    $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .progress.js-progress-bar').animate({
                        width:'50%'
                    },300);

                    $('.progress.js-progress-bar .determinate').animate({
                        width:'0%'
                    },300);

                },
                success: function (returndata) {

                    console.log("Cool");
                    console.log(returndata);
                    //$('#uploadAssignment').closeModal();

                    var failedfiles = jQuery.parseJSON(returndata);

                    console.log(failedfiles);

                },
                error: function (e) {
                    console.log("Not Cool");
                    console.log(e.statusText);
                }
            }, 'json');

        });
    };

    //-----------

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

    var progress = function (e) {

        if(e.lengthComputable){
            var max = e.total;
            var current = e.loaded;

            var Percentage = Math.ceil((current * 100)/max);
            console.log(Percentage + '%');

            $('.js-upload-num-progress').html(Percentage + '%');
            $('.progress.js-progress-bar .determinate').css({
                width : Percentage + '%'
            });

            if(Percentage >= 100)
            {
                $('.js-upload-num-progress').html(Percentage + '%');
                $('.js-upload-num-progress').addClass('hide');
                // process completed
            }
        }
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

/*
            doc.fromHTML($('.assignment-tinymce #body').html(), 15, 15, {
                'width': 170,
                    'elementHandlers': specialElementHandlers
            });
            doc.save(fileName);
*/
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

    var ajaxInit = function () {

        $.when(getUserInfo()).then(function (_1,_2,_3) {
/*
            console.log(_1);
            console.log(_2);
            console.log(_3.responseText);
*/

            userInfo = jQuery.parseJSON(_1);

            //remove class disables on submit buttons
            $('a.js-submit-assignment').removeClass('disabled');

            $this.__construct(userInfo);

        });

    };

    //-----------

    var getUserInfo = function () {

        var $req =  $.ajax({
            url: 'handlers/session_handler.php',
            data: {'action':'GetLoggedUserInfo'},
            type: 'GET',
            processData: true
        }, 'json');

        return $req;

    };

    ajaxInit();

};
