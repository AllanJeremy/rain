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

    };

    //------------------------------
    //--------------------------------  RESOURCES EVENTS AND FUNCTIONS
    //--------------------------------

    //Resources modal
    //temporary
    var addResources = function () {
        var filesinfo, files, totalfiles, resourceslisthook;

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

            console.log(filesinfo);

            resourceslisthook = $('.modal#uploadResource .modal-content').children('#resourcesList');
            console.log($('.modal#uploadResource .modal-content').find('span#totalResources'));
            console.log(resourceslisthook);

            resourceslisthook.fadeOut(300, function () {

                $(this).html(filesinfo);

                $(this).fadeIn();
            });
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

    //Uploads the resources form
    var UploadResources = function () {

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
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (returndata) {
                    console.log("Cool");
                },
                error: function (e) {
                    console.log("Not Cool");
                }
            });

        });
    };

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
                    //subject id was updated.
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
                        var str = '<div class="subject-group row" data-subject-group="' + data.subject_id + '">';
                        str += '<h4 class="grey-text text-darken-2 subject-group-header">' + data.subject_id + '</h4>';
                        str += '<div class="subject-group-body row">';
                        str += res_el;
                        str += '</div><br><div class="divider"></div><br></div>';

                        $('#teacherResourcesTab').children('.tab-content').children('.row').append(str);

                    }
                    setTimeout(function(t) {
                        $('.tr_res_container[data-res-id=' + res_id + ']').removeClass('new-class');

                    }, 500);
                }
            }, 'json');
        });
    };

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
            cleanOutModals();

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

    var cleanOutModal = function (str) {

        console.log('cleaning out modal' + str);

        $('.modal' + str).remove();

        console.log($('main').find('.modal' + str).length);

    };

    //----------------------------

    var cleanOutModals = function () {

        console.log('cleaning out classrooms dialogs');

        //$('a#createClassroom').attr('data-target', '');

        $('.modal ').remove();

    };

    this.__construct();

};
