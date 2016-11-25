/*global $, jQuery, alert, console*/

var Events = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('events created');
        
        cleanOutModals();
        createClassroom();
        editClassroomCard();
        //submitNewClassroom();
        
    };
    
    //----------------------------
    
    var cleanOutModals = function () {
        
        console.log('cleaning out modals');
        
        $('a#createClassroom').attr('data-target', '');
            
        $('.modal').remove();

    };
    
    //----------------------------
    
    var createClassroom = function () {
        //load form template
        
        $('a#createClassroom').click(function (e) {
            e.preventDefault();
            
            var newClassroomId = $('.new-class').attr('data-classroom-id');
            
            newClassroomId++;
            
            console.log('new classroom id:- ' + newClassroomId);
            
            $('.new-class').removeClass('new-class');
            
            console.log('fetching form template');
            
            //get list of subjects
            var formTemplateVars = {
                optgroupname: 'Sciences',
                optgroupname2: 'Languages',
                subjectoption1: 'Mathematics',
                subjectoption2: 'js Physics',
                subjectoption3: 'js Physi',
                subjectoption4: 'js Phcs'
            };
            
            var formTemplate = Forms_Templates.createClassroomForm(formTemplateVars);

            //variables for the modal
            var template = {
                modalId: 'createNewClassRoom',
                templateHeader: 'Create a new ClassRoom',
                templateBody: formTemplate,
                modalActionType: 'type="submit" onclick="submitNewClassroom()"',
                modalActionTypeText: 'Create classroom'
            };
            
            //console.log(Lists_Templates.modalTemplate(template));
            $('main').append(Lists_Templates.modalTemplate(template));
            
            $('select').material_select();
            
            $(this).attr('data-target', template.modalId);
            
            $('#' + template.modalId).openModal();
            
            console.log('modal create classroom form created.');
            
            $('.modal#' + template.modalId + ' a#createNewClassroomCard').bind('click', function(e) {
                e.preventDefault();
                var formResult = submitNewClassroom(template.modalId, newClassroomId);
                
                //console.log('FORM RESULT: ' + formResult);
                
                var classroomTab = $('#classroomTab #classroomCardList');
        
                var result = Lists_Templates.classRoomCard(formResult);
        
                classroomTab.prepend(result);
                
                //masonryGridInit();
                                 
            });
            
        });
        
        //put the form in a modal
        
    };
    
    //------------------------------
    
    var submitNewClassroom = function (str1, str2) {
        //ajax post
        console.log('submit event handler ready');
        
        var newClassTitle = $('.modal#createNewClassRoom input#newClassroomName').val();
        var newClass_stream = $('.modal#createNewClassRoom select#newClassroomStream').val();
        var newClass_subject = $('.modal#createNewClassRoom select#newClassroomSubject').val();
        
        var formResults = {
            classroomid : str2,
            studentnumbers: '21 ',
            assignmentnumbers: '17 ',
            classroomtitle : newClassTitle,
            classroomstream : newClass_stream,
            classroomsubject : newClass_subject
            
        }
        
        
        //var validationResult = validateInputs('createNewClassroomForm');
        
        //if (validationResult) {
            //this.createClassroomCard();
        
            $('#' + str1).closeModal();
            cleanOutModals();
            
        //} else {
            
          //  console.log('empty somewhere')
        //}
        
        console.log('New parameters: ' + newClassTitle + ', ' + newClass_stream + ', ' + newClass_subject);
        
        return formResults;
        
    };
    
    //-------------------------------
    
    var editClassroom = function (classroomId) {
        
        console.log('editing card id: ' + classroomId);
        
    };
    
    //-------------------------------
    
    var editClassroomCard = function () {
        
        var self = $('a#editClassroom');
        
        //self.click(function (e) {
        $('main').on('click', 'a#editClassroom', function (e) {
            e.preventDefault();
            
            var classroomId = $(this).parents('.card-col').attr('data-classroom-id');
            var cardColorClasses = $(this).parents('.card').attr('class').split('card');
            
            $(this).parents('.card').removeClass(cardColorClasses[1]);
            
            console.log(cardColorClasses[1]);
            
            $(this).parents('.card').addClass('grey z-depth-4 to-edit');
            
            editClassroom(classroomId);
            
            console.log('calling edit classroom functions');
        });
        
        //return false;
    };
    
    //--------------------------------
    
    var masonryGridInit = function (str) {
        var masonryContainer = 'classroomCardList';
        
        var $container = $('#' + masonryContainer);
                
                $container.masonry({
                    columnWidth: '.card-col',
                    itemSelector: '.col'
                });
    }
    
    this.__construct();
    
};