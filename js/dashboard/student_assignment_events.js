/*global $, jQuery, alert, console*/

var StudentAssignmentEvents = function () {
    'use strict';
    //--------------

    this.__construct = function () {
        console.log('Assignments events created');

        //Assignments inits
        addAssignment();
        submitAssignment();
        myAssignmentSave();

    };

    var addAssignment = function () {
        var filesinfo, files, totalfiles, assignmentslisthook, assignments_errorshook, errorlist = '';

        // HEADER, NOT MAIN

        //Checks on file input change, updates, the modal infos
        $('main').on('change', "form#createAssignmentForm input.js-assignments.active", function (e) {
            e.preventDefault();

            files = document.forms['createAssignmentForm']['.js-assignments'].files;
            totalfiles = files.length;

            console.log(files);
            console.log(totalfiles);
            if (files.length > 0) {
                $('.modal#assignmentUpload').find('a#uploadAssignment').removeClass('disabled');
            } else {
                $('.modal#assignmentUpload').find('a#uploadAssignment').addClass('disabled');

            }

            console.log(files);

            $('.modal#assignmentUpload .modal-content').find('span#totalAssignments').html(totalfiles);

            filesinfo = generateAssignmentList(files);

            assignmentslisthook = $('.modal#assignmentUpload .modal-content').children('#assignmentsList');

            assignmentslisthook.prepend(filesinfo);

/*
            assignmentslisthook.fadeOut(300, function () {

                $(this).html(filesinfo);

                $(this).fadeIn();
            });
*/

            var validateresult = validateFiles(files);
            assignments_errorshook = $('.modal#assignmentUpload .modal-content').children('#errorContainer');

            console.log(validateresult);

            if(validateresult.length > 0) {
                //disable the upload button
                //show errors
                $('.modal#assignmentUpload').find('a#uploadAssignment').addClass('disabled');

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
    var addMoreAssignmentFiles = function () {
        $('main').on('click', 'a.js-submit-assignment', function (e) {
            e.preventDefault();

        });
    };

    //-----------
    //submit the assignments form
    var submitAssignment = function () {

        $('main').on('click', 'a.js-submit-assignment', function (e) {
            e.preventDefault();

            if ($(this).hasClass('disabled')) {
                return;
            }

            var fileinputs = document.forms['addAssignmentForm']['.js-assignments'],
                files, filesdescription = '', subjectid;

            console.log(fileinputs);

            //ajax
            // Create a new FormData object.
            var formData = new FormData();

            for (var g = 0; g < files.length; g++) {
                //Hoping the indexes will match
                formData.append('file-'+g, files[g]);
            }


            //return;

            //Append the data and the action name
            formData.append('data', JSON.stringify(DATA));
            formData.append('action', 'AssignmentUpload');

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
                    $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .num-progress').removeClass('hide');
                    $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .js-num-progress').html('0%');

                    $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .progress').animate({
                        width:'50%'
                    },300);
                    $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .progress .determinate').animate({
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

            $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .js-num-progress').html(Percentage + '%');
            $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .progress .determinate').css({
                width : Percentage + '%'
            });

            if(Percentage >= 100)
            {

                $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .js-num-progress').html(Percentage + '%');
                $('.modal#assignmentUpload .modal-content').find('#assignmentsTotalInfo .num-progress').addClass('hide');

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

    this.__construct();

};
