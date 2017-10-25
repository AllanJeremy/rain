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

    this.cleanOutModal = function (str) {
        //return;
        console.log('cleaning out modal' + str);

        $('.modal' + str).remove();

        if ($('main').find('.modal' + str).length > 0) {
            return true;
        } else {
            return false;
        }

    };

    //----------------------------

    this.cleanOutModals = function () {
        //return;

        console.log('cleaning out all modals');

        setTimeout(function () {
            $('.modal ').remove();

        }, 1400);

    };

    //--------------------------------

    this.updateEsomoModalProgress = function (modal_id) {
        //return;
        console.log('progress bar event listener fired on modal id: ' + modal_id);

        $('main').on('change', 'input[type="checkbox"]', function (o) {

            o.preventDefault();

            var totalCount = $('#' + modal_id).find('input[type="checkbox"]:checked').length;

            console.log('progress bar event listener on ' + totalCount + ' checkboxes.');

        });
    };

    //--------------------------------

    this.closeModalsEvent = function () {

        $('main').on('click', '.modal a#modalFooterCloseAction.modal-close', function (e) {

            e.preventDefault();

            console.log('removing modal from DOM');

            $(this).parents('.modal').remove();

            var cardColor = localStorage.getItem("cardColor");

            $('.to-edit').removeClass('grey z-depth-4');
            $('.to-edit').addClass(cardColor);
            $('.card').removeClass('to-edit');

        });

    };

    //--------------------------------

    this.modalProgress = function (e, id) {

    };

    //--------------------------------

    this.construct();

};
