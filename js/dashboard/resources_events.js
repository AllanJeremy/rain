/*global $, jQuery, alert, console*/

var ResourcesEvents = function () {
    'use strict';
    //--------------

    this.__construct = function () {
        console.log('Resources events created');

        //Resources inits
        addResources();
        UploadResources();
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

    this.__construct();

};
