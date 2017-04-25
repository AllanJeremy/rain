/*global $, jQuery, alert, console*/

var StudentAssignmentEvents = function () {
    'use strict';
    //--------------

    this.__construct = function () {
        console.log('Assignments events created');

        //Resources inits
        addAssignment();
        uploadAssignment();
        myAssignmentSave();

    };

    var addAssignment = function () {
            var filesinfo, files, totalfiles, resourceslisthook, resources_errorshook, errorlist = '';

            //Checks on file input change, updates, the modal infos
            $('main').on('change', "form#createResourcesForm input:file", function (e) {
                e.preventDefault();

                files = document.forms['createResourcesForm']['resources'].files;
                totalfiles = files.length;

                if (files.length > 0) {
                    $('.modal#uploadResource').find('a#uploadResource').removeClass('disabled');
                } else {
                    $('.modal#uploadResource').find('a#uploadResource').addClass('disabled');

                }

                console.log(files);

                $('.modal#uploadResource .modal-content').find('span#totalResources').html(totalfiles);

                filesinfo = generateAssignmentFormList(files);

                resourceslisthook = $('.modal#uploadResource .modal-content').children('#resourcesList');

                resourceslisthook.fadeOut(300, function () {

                    $(this).html(filesinfo);

                    $(this).fadeIn();
                });

                var validateresult = validateFiles(files);
                resources_errorshook = $('.modal#uploadResource .modal-content').children('#errorContainer');

                console.log(validateresult);

                if(validateresult.length > 0) {
                    //disable the upload button
                    //show errors
                    $('.modal#uploadResource').find('a#uploadResource').addClass('disabled');

                    $.each(validateresult, function(b,x) {
                        errorlist += Lists_Templates.resourcesErrorListTemplate(files[x.index], x.errortype);

                    });

                    resources_errorshook.find('ul:first').html(errorlist);

                    errorlist = '';

                    return;
                }

                resources_errorshook.find('ul:first').html('');
                errorlist = '';

            });

            $('main').on('click', 'a#addResource', function (e) {
                e.preventDefault();

                var template = {
                    modalId: 'uploadResource',
                    templateHeader: 'Upload Resources',
                    templateBody: ''
                };

    //            load the modal in the DOM
                $('main').append(Lists_Templates.resourcesModalTemplate(template));

                $('select').material_select();

                $('#' + template.modalId).openModal({dismissible: false});

            });
        };

    //-----------
    //Uploads the resources form
    var uploadAssignment = function () {

        $('main').on('click', 'a#uploadResource', function (e) {
            e.preventDefault();

            if ($(this).hasClass('disabled')) {
                return;
            }

            var files = document.forms['createResourcesForm']['resources'].files,
                filesdescription = '', subjectid,
                DATA = [];
            console.log(files);

            //ajax
            // Create a new FormData object.
            var formData = new FormData();

            for (var g = 0; g < files.length; g++) {
                //Hoping the indexes will match
                formData.append('file-'+g, files[g]);
                var d = {
                    'description' : $('.modal#uploadResource .modal-content').children('#resourcesList').children('.row[data-index="'+ g +'"]').find('textarea#resourceDescription').val(),
                    'subjectid' : parseInt($('.modal#uploadResource .modal-content').children('#resourcesList').children('.row[data-index="'+ g +'"]').find('select#resourceSubjectType option:selected').val())
                }
                DATA.push(d);
            }

            console.log(DATA);

            //return;

            //Append the data and the action name
            formData.append('data', JSON.stringify(DATA));
            formData.append('action', 'ResourcesUpload');

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
                    $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .num-progress').removeClass('hide');
                    $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .js-num-progress').html('0%');

                    $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .progress').animate({
                        width:'50%'
                    },300);
                    $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .progress .determinate').animate({
                        width:'0%'
                    },300);

                },
                success: function (returndata) {

                    console.log("Cool");
                    console.log(returndata);
                    //$('#uploadResource').closeModal();

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

            $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .js-num-progress').html(Percentage + '%');
            $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .progress .determinate').css({
                width : Percentage + '%'
            });

            if(Percentage >= 100)
            {

                $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .js-num-progress').html(Percentage + '%');
                $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .num-progress').addClass('hide');

                // process completed
            }
        }
    };

    //--------------------------------

    var myAssignmentSave = function (e) {

    };

    //-----------

    //Generates the resources list, each with textareas.
    var generateResourcesFormList = function (obj) {
        var str = '';

        for(var a = 0; a < obj.length; a++) {

            str += Lists_Templates.resourcesListTemplate(obj[a], a);
        }

        return str;
    };

    this.__construct();

};
