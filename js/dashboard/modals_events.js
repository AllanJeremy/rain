/*global $, jQuery, alert, console*/

var Modals_Events = function () {
    'use strict';
    //--------------

    this.construct = function () {
        console.log('modals events created');

        this.closeModalsEvent();
        this.selectAllModalFormData();
//        this.updateEsomoModalProgress();

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

            }, 400);
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

            }, 400);
        } else {
            $('.modal ').remove();

        }

    };

    
    //--------------------------------

    this.updateEsomoModalAutocomplete = function (modal_id, data, funC) {
        //return;
        console.log('updatingAutocomplete for ' + modal_id);
        var El = $('.modal#esomoModal'+modal_id+ ' input.autocomplete'),
            $modalEl = $('.modal#esomoModal'+modal_id),
            $modalDataEl = $('.modal#esomoModal'+modal_id+' #formData');
        // console.log(El);

        El.autocomplete({
            data: data,
            limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
            onAutocomplete: function(val) {
                // Callback function when value is autocompleted.
                
                console.log($('.modal#esomoModal'+modal_id+' #formData').find('label'));
                $modalDataEl.find('label').map(function(){
                    console.log($(this)[0].innerHTML);
                    
                    val = _.split(val, ' (', 2)[0];
                    if ($(this)[0].innerHTML === val) {
                        console.log($(this)[0].control.id);
                        var inputid = $(this)[0].control.id;
                        $modalDataEl.find('input[type="checkbox"]:not(:checked)#'+inputid).prop('checked', true);
                        Materialize.toast('Checked ' + val, 1300, 'white-text');
                        El.val('');
                    }
                }).get();;
                console.log($modalEl);
//                console.log(checks);
            },
            minLength: 1 // The minimum length of the input for the autocomplete to start. Default: 1.
            
        });
    };
    
    //--------------------------------

    this.selectAllModalFormData = function () {
        //return;
        var $modalCheckEl = 'input#selectAll:checkbox',
            $modalCheckedCheckEl = 'input#selectAll:checkbox:checked',
            $modalDataEl = $('.modal.esomo-modal #formData');
        
        $('main').on('change', '.modal.esomo-modal ' + $modalCheckEl, function (e) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) {
                return false;
            }
            
            if ($('.modal.esomo-modal ' + $modalCheckedCheckEl).length > 0 ) { // checked
                $modalDataEl.find('input[type="checkbox"]:not(:checked)').prop('checked', true);
            } else {
                $modalDataEl.find('input[type="checkbox"]:checked').prop('checked', false);
            }
            
        });
    };
    
    //--------------------------------

    this.resetEsomoModalTemplate = function (modal_id, def_id) {
        //resets the modal id and the body
        console.log('resetting modal for ' + modal_id);
        $('.modal#esomoModal' + modal_id + ' .modal-content').find('#formData').removeClass('morph-in new-class');
        $('.modal#esomoModal' + modal_id).find('input[type="checkbox"]:checked').prop('checked', false);
        $('.modal#esomoModal' + modal_id).attr('id', def_id);
        // console.log(El);

    };

    //--------------------------------

    this.closeModalsEvent = function () {

        var $this = this;

        $('main').on('click', '.modal a#modalFooterCloseAction.modal-close', function (e) {

            e.preventDefault();

            console.log('closing then removing modal from DOM');

            var cardColor = localStorage.getItem("cardColor"),
                modalId = $(this).parents('.modal').attr('id');
               
            $('#' + modalId).modal('close');
//            $this.cleanOutModal('#' + modalId, true);
            if (_.split(location.search, '=', 2)[1] == 'classrooms' && $('#' + modalId).hasClass('esomo-modal')) {
                $this.resetEsomoModalTemplate(_.split(modalId, 'esomoModal', 2)[1], 'esomoModalClassStudentList');
            }
            
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
