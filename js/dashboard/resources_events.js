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
            formData.append('action', 'resourcesUpload');

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
                modalId: 'editResource',
                templateHeader: 'Edit Resource',
                templateBody: Forms_Templates.editResourceForm(resourceid),
                extraActions: Lists_Templates.infoExtraFooterActions({
                    "Delete" : true,
                    "Archive" : false
                })
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
                description = $('.modal#editResource form#editResourceForm')[0][1].value,
                subjectid = $('.modal#editResource form#editResourceForm')[0][0].value,
                data = {
                    'action' : 'updateResource',
                    'resource_id' : Number(res_id),
                    'description' : description,
                    'subject_id' : Number(subjectid)
                };

            console.log(res_id, description, subjectid);
            console.log(data);
            console.log(typeof data['resource_id']);


            //ajax
            $.post('handlers/db_handler.php', data, function(returndata) {
                console.log(returndata);
            }, 'json');
            //Change only the current card data if the subject id has not been changed
            //otherwise append eithe to a row uunder the chosen subject id
            //or create a row if not exist

            if(res_on_edit[1] === subjectid) {

            }

        });
    };

    this.__construct();

};
