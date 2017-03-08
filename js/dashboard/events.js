/*global $, jQuery, alert, console*/

var Events = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('global events created');
        
        ClassroomEvents = new ClassroomEvents();
        AssignmentEvents = new AssignmentEvents();
        ScheduleEvents = new ScheduleEvents();
        ResourcesEvents = new ResourcesEvents();
        
        //global inits
        cleanOutModals();
        closeModalsEvent();
        
    };
    
    //----------------------------
    
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
    
    var masonryGridInit = function (str) {
        var masonryContainer = 'classroomCardList';
        
        var $container = $('#' + masonryContainer);
                
                $container.masonry({
                    columnWidth: '.card-col',
                    itemSelector: '.col'
                });
    };
    
    //--------------------------------
    //--------------------------------  MODAL EVENTS AND FUNCTIONS
    //--------------------------------
    
    var updateEsomoModalProgress = function (modal_id) {
        
        console.log('progress bar event listener fired on modal id: ' + modal_id);
        
        $('main').on('change', 'input[type="checkbox"]', function (o) {
            
            o.preventDefault();

            var totalCount = $('#' + modal_id).find('input[type="checkbox"]:checked').length;
            
            console.log('progress bar event listener on ' + totalCount + ' checkboxes.');
            
        });
    };
    
    //--------------------------------
    
    var closeModalsEvent = function () {
        
        $('main').on('click', '.modal a#modalFooterCloseAction.modal-close', function (e) {

            e.preventDefault();
            
            console.log('removing modal from DOM');
        
            $(this).parents('.modal').remove();
    
            var cardColor = localStorage.getItem("cardColor");
            
            $('.to-edit').removeClass('grey z-depth-4');
            $('.to-edit').addClass(cardColor)
            $('.card').removeClass('to-edit');
            
        });
        
        //$('a#createClassroom').attr('data-target', '');
            
        //$('.modal').remove();

    };
    
    //----------------------------      FUNCTIONS
    
    var loadEsomoModal = function (modal_id, modal_header, modal_body) {
        
        var args = {
            modalId: modal_id,
            modalActionType: {
                actionClose: 'close-hivi',
                actionAdd: 'add-hivi'
            },
            templateHeader: modal_header,
            templateBody: modal_body
            
        };
        
        var esomoModal = Lists_Templates.esomoModalTemplate(args);
        
        $('main').append(esomoModal);
        
    };
    
    //--------------------------------
    
    var cleanOutModal = function (str) {
        
        console.log('cleaning out modal' + str);
        
        $('.modal' + str).remove();

        console.log($('main').find('.modal' + str).length);
        
    };
    
    //----------------------------
    
    var cleanOutModals = function () {
        
        console.log('cleaning out global events dialogs');
        
        //$('a#createClassroom').attr('data-target', '');
        
        $('.modal ').remove();

    };
    
    //--------------------------------
    //--------------------------------  END OF MODAL EVENTS AND FUNCTIONS
    //--------------------------------
    
    this.__construct();
    
};
