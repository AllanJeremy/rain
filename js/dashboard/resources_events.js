/*global $, jQuery, alert, console*/

var ResourcesEvents = function () {
    'use strict';
    //--------------

    var res_on_edit;

    this.__construct = function () {
        console.log('Resources events created');

        //Resources inits
        addResources();
        UploadResources();
        editResource();
        uploadEditedResource();
        deleteResource();
        minimizeSubjectResources();

    };

    //------------------------------
    //--------------------------------  RESOURCES EVENTS AND FUNCTIONS
    //--------------------------------

    //Resources modal
    //temporary
    var addResources = function () {
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

            filesinfo = generateResourcesFormList(files);

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
                    errorlist += Lists_Templates.documentUploadsErrorListTemplate(files[x.index], x.errortype);

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
    var UploadResources = function () {

        $('main').on('click', 'a#uploadResource', function (e) {
            e.preventDefault();

            if ($(this).hasClass('disabled')) {
                return;
            }

            var files = document.forms['createResourcesForm']['resources'].files,
                filesdescription = '', subjectid,
                $This = $(this), failedfiles,
                DATA = [],
                uploadSuccess = '<p class="';
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
                    if (returndata === '') {
                        $This.trigger('click');

                        console.log('retrying');
                        return (false);

                    } else {
                        failedfiles = jQuery.parseJSON(returndata);

                        //if success
                        if(failedfiles.status) {
                            $('.num-progress').html('Upload successful');
                            Materialize.toast('<p class="white-text">Upload successful</p>', 1200, 'green accent-3', function (s) {
                                setTimeout(function () {
                                    location.reload();

                                }, 800);

                            });

                        } else {

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
                    }


                    console.log(failedfiles);
                    console.log(failedfiles.failed_files.length);

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

    //-----------

    //Generates the resources list, each with textareas.
    var generateResourcesFormList = function (obj) {
        var str = '';

        for(var a = 0; a < obj.length; a++) {

            str += Lists_Templates.resourcesListTemplate(obj[a], a);
        }

        return str;
    };

    //-----------

    var editResource = function () {

        $('main').on('click', 'a.js-edit-resource', function (e) {
            e.preventDefault();
            var resourceid = $(this).parents('.tr_res_container').attr('data-res-id'),
                subjectid = $(this).parents('.tr_res_container').attr('data-subject-id'),
                description = $(this).parents('.tr_res_container').find('span.js-res-description')[0].innerText;
            console.log(resourceid, subjectid);
            console.log(description);

            res_on_edit = Array(resourceid, subjectid);

            var template = {
                modalId: 'editResource_' + resourceid,
                templateHeader: 'Edit Resource',
                templateBody: Forms_Templates.editResourceForm(resourceid),
                extraActions: Lists_Templates.infoExtraFooterActions({
                    "Delete" : true,
                    "Archive" : false
                }, 'moreResources')
            };

//            load the modal in the DOM
            $('main').append(Lists_Templates.modalTemplate(template));

            $('select').material_select();

            $('#' + template.modalId).openModal({dismissible: false});

            $('.modal#' + template.modalId + ' form#editResourceForm')[0][1].value = description;
            $('.modal#' + template.modalId + ' form#editResourceForm')[0][0].value = subjectid;

            Materialize.updateTextFields();

        });
    }

    //-----------

    var uploadEditedResource = function () {

        $('main').on('click', 'a#updateResource', function (e) {
            e.preventDefault();
            var res_id = $(this).attr('data-res-id'),
                self = $(this),
                description = $('.modal#editResource_'+ res_id +' form#editResourceForm')[0][1].value,
                subjectid = $('.modal#editResource_'+ res_id +' form#editResourceForm')[0][0].value,
                data = {
                    'action' : 'UpdateResource',
                    'resource_id' : Number(res_id),
                    'description' : description,
                    'subject_id' : Number(subjectid)
                }, res_el;

            console.log(data);

            //ajax
            $.post('handlers/db_handler.php', data, function(returndata) {
                console.log(returndata.description);

                //close modal
                $('.modal#editResource_'+ res_id).closeModal();
//                return;
                //Change only the current card data if the subject id has not been changed
                //otherwise append eithe to a row uunder the chosen subject id
                //or create a row if not exist
                if(Number(res_on_edit[1]) === data['subject_id']) {
                    //Subject id was not changed, so no need for appending card
                    $('.tr_res_container[data-res-id=' + res_id + ']').find('span.js-res-description').html(returndata.description);
                } else {
                    //subject id was updated. APPEND CAR TO THE RIGHT ROW
                    $('.tr_res_container[data-res-id=' + res_id + ']').attr('data-subject-id', returndata.subject_id);
                    $('.tr_res_container[data-res-id=' + res_id + ']').addClass('new-class');
                    $('.tr_res_container[data-res-id=' + res_id + ']').find('span.js-res-description').html(returndata.description);

                    res_el = $('.tr_res_container[data-res-id=' + res_id + ']').parent('.col')[0].outerHTML;

                    $('.tr_res_container[data-res-id=' + res_id + ']').parent('.col').remove();

                    //If there are no cards left in the subject group, delete the subject group
                    if($('#teacherResourcesTab').find('.subject-group[data-subject-group=' + res_on_edit[1] + ']').children('.subject-group-body').children('.col').length === 0) {
                        console.log('skr skr');
                        $('#teacherResourcesTab').find('.subject-group[data-subject-group=' + res_on_edit[1] + ']').remove();
                        //console.log($('#teacherResourcesTab').find('.subject-group[data-subject-group=' + res_on_edit[1] + ']'));
                    }

                    //If there exist a subject group, simply append the card; else append the whole subject group element
                    if ($('#teacherResourcesTab').find('.subject-group[data-subject-group=' + returndata.subject_id + ']').length > 0) {
                        $('#teacherResourcesTab').find('.subject-group[data-subject-group=' + returndata.subject_id + ']').children('.subject-group-body').prepend(res_el);
                    } else {

                        var El = Lists_Templates.resourceSubjectGroup({'id':data.subject_id, 'el':res_el});
                        console.log('implanting this now');
                        console.log(El);

                        $('#teacherResourcesTab').children('.tab-content').children('.row').append(El);

                    }
                    setTimeout(function(t) {
                        $('.tr_res_container[data-res-id=' + res_id + ']').removeClass('new-class');

                    }, 500);
                }
            }, 'json');
        });
    };

    //-----------

    var deleteResource = function () {
        $('main').on('click', ' a#moreResourcesCardDelete', function (e) {
            e.preventDefault();
            console.log('will delete');

            var self = $(this), re,
                res_id = self.parents('.modal').attr('id').split('_').pop(),
                toastMessage = '<p class="white-text" data-ref-resource-id="' + res_id + '">Preparing to delete a resource file  <a href="#!" class="bold" id="toastUndoAction" >UNDO</a></p>';

            console.log('resource id ' + res_id + ' to be deleted.');

            //close modal
            $('.modal#' + self.parents('.modal').attr('id') ).closeModal();
            //remove modal from dom
            Modals_Events.cleanOutModals();

            $('.tr_res_container[data-res-id=' + res_id + ']').addClass('to-remove');
            //3
            var toastCall = Materialize.toast(toastMessage, 7200, '', function (s) {
                //4
                $.post("handlers/db_handler.php", {"action" : "DeleteResource", "resourceid" : res_id}, function (result) {

                    //5
                    if(result === '1') {
                        $('.tr_res_container[data-res-id=' + res_id + ']').parent('.col').remove();

                        //If there are no cards left in the subject group, delete the subject group
                        if($('#teacherResourcesTab').find('.subject-group[data-subject-group=' + res_on_edit[1] + ']').children('.subject-group-body').children('.col').length === 0) {
                            console.log('skr skr');
                            $('#teacherResourcesTab').find('.subject-group[data-subject-group=' + res_on_edit[1] + ']').remove();
                            //console.log($('#teacherResourcesTab').find('.subject-group[data-subject-group=' + res_on_edit[1] + ']'));
                        }
                    }

                    //6
                    //cleanOutModals();

                }, 'text');

            });

        });


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

                $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .js-num-progress').html('100%');
                $('.modal#uploadResource .modal-content').find('#resourcesTotalInfo .progress .determinate').css({
                    width : '100%'
                });

                // process completed
            }
        }
    };

    //--------------------------------

    var minimizeSubjectResources = function () {

        $('main').on('click', 'a.js-minimize-subject-resources', function (e) {
            e.preventDefault();
            console.log('minimizing div');
            console.log($(this)[0].innerText);

            var subjectgroup = $(this).parents('.subject-group'),
                subjectgroupbody = subjectgroup.find('.subject-group-body');

            if($(this).hasClass('hidden')) {
                subjectgroupbody.slideDown();
                $(this).children('i.material-icons')[0].innerText = 'expand_more';
                $(this).removeClass('hidden');

            } else {

                subjectgroupbody.slideUp();

                $(this).children('i.material-icons')[0].innerText = 'expand_less';
                $(this).addClass('hidden');

            }

        });

    };

    this.__construct();

};
