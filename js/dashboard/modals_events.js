/*global $, jQuery, alert, console*/

var Modals_Events = function () {
    'use strict';
    //--------------

    this.construct = function () {
        console.log('modals events created');

        this.closeModalsEvent();
        //this.updateEsomoModalProgress();

    };

    //--------------------------------------

    this.loadCommentModal = function (modal_id, id, comment_type, title, modal_body, comment_enabled, extra_info) {

        var args = {
            modalId: modal_id,
            id: id,
            commentType: comment_type,
            title: title,
            templateBody: modal_body,
            canComment: comment_enabled,
            extraInfo : extra_info
        },
            commentModal = Lists_Templates.commentsModal(args);
        console.log(commentModal);
        $('main').append(commentModal);

    };

    //--------------------------------------

    this.loadEsomoModal = function (modal_id, modal_header, modal_body, modal_action) {

        var args = {
            modalId: modal_id,
            modalActionType: {
                actionClose: 'close-hivi',
                actionAdd: 'add-hivi'
            },
            templateHeader: modal_header,
            templateBody: modal_body,
            modal_action: modal_action
        },
            esomoModal = Lists_Templates.esomoModalTemplate(args);

        $('main').append(esomoModal);

    };

    //--------------------------------

    this.cleanOutModal = function (str, i) {
        //return;
        console.log('cleaning out modal' + str);
        if(i === true) {
            // waste time
            console.log('cleaning out all modals');

            setTimeout(function () {
                $('.modal' + str).remove();

            }, 1400);
        } else {
            $('.modal' + str).remove();
            
        }
        
        if ($('main').find('.modal' + str).length > 0) {
            return true;
        } else {
            return false;
        }

    };

    //----------------------------

    this.cleanOutModals = function (i) {
        //return;

        if(i === true) {
            // waste time
            console.log('cleaning out all modals');

            setTimeout(function () {
                $('.modal ').remove();

            }, 1400);
        } else {
            $('.modal ').remove();

        }

    };

    
    //--------------------------------

    this.updateEsomoModalAutocomplete = function (modal_id, data, funC) {
        //return;
        console.log('updatingAutocomplete for ' + modal_id);
        var El = $('.modal#esomoModal'+modal_id+ ' input.autocomplete');
        // console.log(El);

        El.autocomplete({
            data: data,
            limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
            onAutocomplete: function(val) {
                // Callback function when value is autcompleted.
                console.log(val);
                Materialize.toast('Checked ' + val, 1300, 'white-text');
                // $('.modal'+modal_id+ ' input
            },
            minLength: 1 // The minimum length of the input for the autocomplete to start. Default: 1.
            
        });
    };

    
    //--------------------------------

    this.closeModalsEvent = function () {

        var $this = this;

        $('main').on('click', '.modal a#modalFooterCloseAction.modal-close', function (e) {

            e.preventDefault();

            console.log('closing then removing modal from DOM');

            var cardColor = localStorage.getItem("cardColor"),
                modalId = $(this).parents('.modal').attr('id');
               
            $('#' + modalId).closeModal();
            $this.cleanOutModal('#' + modalId, true);

            $('.to-edit').removeClass('grey z-depth-4')
                .addClass(cardColor);
            $('.card').removeClass('to-edit');

        });

    };

    //--------------------------------

    this.modalProgress = function (e, id) {

    };

    //--------------------------------

    this.construct();

};
